<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class AdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

		// loginAsメソッドのユーザーセッションを削除
		foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
    }

    // ========================================
    // 記事の一覧ページ
    // ========================================

    /** @test */
    public function 記事の一覧ページにアクセスしたとき、記事一覧ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

    /** @test */
    public function 未ログイン時に記事の一覧ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外で記事の一覧ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin')
                    ->assertPathIs('/admin')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function 記事の一覧ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の一覧ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の一覧ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function 記事の一覧ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function 記事の一覧ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function 記事の一覧ページの記事の「表示」をクリックしたとき、記事の表示ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="show-1"]');

			// target_blankの新しいウィンドウを取得
			$target_window = collect($browser->driver->getWindowHandles())->last();
			$browser->driver->switchTo()->window($target_window);

            $browser->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

	/** @test */
    public function 記事の一覧ページの記事の「編集」をクリックしたとき、記事の編集ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin')
					->click('[data-test="edit-1"]')
                    ->assertPathIs('/admin/edit/1')
					->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertInputValue('input[name="title"]', 'タイトル1（公開）')
                    ->assertSelected('select[name="status"]', "1")
					->assertInputValue('input[name="post_date"]', date('Y-m-d\TH:i'))
					->assertChecked('input#category1')
					->assertChecked('input#category2')
					->assertChecked('input#category3')
					->assertInputValue('input[name="subtitle"]', 'サブタイトル1')
					->assertInputValue('textarea[name="content"]', '<p>本文1</p>');
        });
    }

    // ========================================
    // 記事の新規作成ページ
    // ========================================

	/** @test */
    public function 記事の新規作成ページにアクセスしたとき、記事の新規作成ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function 未ログイン時に記事の新規作成ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin/create')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外で記事の新規作成ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin/create')
                    ->assertPathIs('/admin/create')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function 記事の新規作成ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の新規作成ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の新規作成ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function 記事の新規作成ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function 記事の新規作成ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function 記事の新規作成ページで、記事を新規作成できる()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/create')
                    ->type('title', 'タイトル4（公開）')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル4');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文4</p>")');
            
            $browser->click('input[type="submit"]')
                    ->assertPathIs('/admin/edit/4')
                    ->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertSee('データを登録しました。')
                    ->assertInputValue('input[name="title"]', 'タイトル4（公開）')
                    ->assertSelected('select[name="status"]', '1')
					->assertInputValue('input[name="post_date"]', date('Y-m-d\TH:i'))
					->assertChecked('input#category1')
					->assertChecked('input#category2')
					->assertChecked('input#category3')
					->assertInputValue('input[name="subtitle"]', 'サブタイトル4')
					->assertInputValue('textarea[name="content"]', '<p>本文4</p>');
        });
    }

    /** @test */
    public function 記事の新規作成ページでタイトルが未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/create')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル4');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文4</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ タイトルを入力して下さい');
        });
    }

    /** @test */
    public function 記事の新規作成ページで投稿日時が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/create')
                    ->type('title', 'タイトル4（公開）')
                    ->select('status', '1')
                    ->value('input[name="post_date"]', '')
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル4');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文4</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ 投稿日時を設定して下さい');
        });
    }

    /** @test */
    public function 記事の新規作成ページでサブタイトルが未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/create')
                    ->type('title', 'タイトル4（公開）')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文4</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ サブタイトルを入力して下さい');
        });
    }

    // ========================================
    // 記事の編集ページ
    // ========================================

	/** @test */
    public function 記事の編集ページにアクセスしたとき、記事の編集ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
                    ->assertPathIs('/admin/edit/1')
                    ->assertSee('技術メモ')
                    ->assertSee('編集')
					->assertInputValue('input[name="title"]', 'タイトル1（公開）')
                    ->assertSelected('select[name="status"]', "1")
					->assertInputValue('input[name="post_date"]', date('Y-m-d\TH:i'))
					->assertChecked('input#category1')
					->assertChecked('input#category2')
					->assertChecked('input#category3')
					->assertInputValue('input[name="subtitle"]', 'サブタイトル1')
					->assertInputValue('textarea[name="content"]', '<p>本文1</p>');
        });
    }

    /** @test */
    public function 未ログイン時に記事の編集ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin/edit/1')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外で記事の編集ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin/edit/1')
                    ->assertPathIs('/admin/edit/1')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function 記事の編集ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の編集ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function 記事の編集ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function 記事の編集ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function 記事の編集ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function 記事の編集ページで、記事を編集できる()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/edit/1')
                    ->type('title', 'タイトル1（公開） 更新')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル1 更新');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文1 更新</p>")');
            
            $browser->click('input[type="submit"]')
                    ->assertPathIs('/admin/edit/1')
                    ->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertSee('データを更新しました。')
                    ->assertInputValue('input[name="title"]', 'タイトル1（公開） 更新')
                    ->assertSelected('select[name="status"]', '1')
					->assertInputValue('input[name="post_date"]', date('Y-m-d\TH:i'))
					->assertChecked('input#category1')
					->assertChecked('input#category2')
					->assertChecked('input#category3')
					->assertInputValue('input[name="subtitle"]', 'サブタイトル1 更新')
					->assertInputValue('textarea[name="content"]', '<p>本文1 更新</p>');
        });
    }

    /** @test */
    public function 記事の編集ページでタイトルが未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/edit/1')
                    ->type('title', '')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル1 更新');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文1 更新</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ タイトルを入力して下さい');
        });
    }

    /** @test */
    public function 記事の編集ページで投稿日時が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/edit/1')
                    ->type('title', 'タイトル1（公開） 更新')
                    ->select('status', '1')
                    ->value('input[name="post_date"]', '')
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', 'サブタイトル1 更新');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文1 更新</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ 投稿日時を設定して下さい');
        });
    }

    /** @test */
    public function 記事の編集ページでサブタイトルが未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/edit/1')
                    ->type('title', 'タイトル1（公開） 更新')
                    ->select('status', '1')
                    ->keys('input[name="post_date"]', date('Y'), '{tab}', date('m'), date('d'), date('H'), date('i'))
                    ->check('#category1')
                    ->check('#category2')
                    ->check('#category3')
                    ->type('subtitle', '');

            $browser->driver->executeScript('tinyMCE.activeEditor.setContent("<p>本文1 更新</p>")');

            $browser->click('input[type="submit"]')
                    ->assertSee('※ サブタイトルを入力して下さい');
        });
    }

	// ========================================
    // カテゴリーの一覧ページ
    // ========================================

	/** @test */
    public function カテゴリーの一覧ページにアクセスしたとき、カテゴリーの一覧ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function 未ログイン時にカテゴリーの一覧ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin/category')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの一覧ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin/category')
                    ->assertPathIs('/admin/category')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function カテゴリーの一覧ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの一覧ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの一覧ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function カテゴリーの一覧ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function カテゴリーの一覧ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function カテゴリーの一覧ページのカテゴリーの「編集」をクリックしたとき、カテゴリーの編集ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category')
					->click('[data-test="edit-1"]')
                    ->assertPathIs('/admin/category/edit/1')
					->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertInputValue('input[name="name"]', 'カテゴリー1')
					->assertInputValue('input[name="order"]', "1");
        });
    }

	// ========================================
    // カテゴリーの新規作成ページ
    // ========================================

	/** @test */
    public function カテゴリーの新規作成ページにアクセスしたとき、カテゴリーの新規作成ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function 未ログイン時にカテゴリーの新規作成ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin/category/create')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの新規作成ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin/category/create')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function カテゴリーの新規作成ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの新規作成ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの新規作成ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function カテゴリーの新規作成ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function カテゴリーの新規作成ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function カテゴリーの新規作成ページで、カテゴリーを新規作成できる()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
                    ->type('name', 'カテゴリー4')
                    ->type('order', '4')
					->click('input[type="submit"]')
                    ->assertPathIs('/admin/category/edit/4')
                    ->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertInputValue('input[name="name"]', 'カテゴリー4')
					->assertInputValue('input[name="order"]', "4");
        });
    }

    /** @test */
    public function カテゴリーの新規作成ページでカテゴリー名が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
                    ->type('order', '4')
					->click('input[type="submit"]')
                    ->assertSee('※ カテゴリー名を入力して下さい');
        });
    }

    /** @test */
    public function カテゴリーの新規作成ページで順番が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/create')
                    ->type('name', 'カテゴリー4')
					->click('input[type="submit"]')
                    ->assertSee('※ 順番を入力して下さい');
        });
    }

    // ========================================
    // カテゴリーの編集ページ
    // ========================================

    /** @test */
    public function カテゴリーの編集ページにアクセスしたとき、カテゴリーの編集ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
                    ->assertPathIs('/admin/category/edit/1')
                    ->assertSee('技術メモ')
                    ->assertSee('編集');
        });
    }

    /** @test */
    public function 未ログイン時にカテゴリーの編集ページにアクセスしたとき、ログインページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->visit('/admin/category/edit/1')
                    ->assertPathIs('/login')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの編集ページにアクセスしたとき、403ページが表示される()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
					->visit('/admin/category/edit/1')
                    ->assertPathIs('/admin/category/edit/1')
                    ->assertSee('403 ERROR')
                    ->assertSee('このページは許可されていません。');
        });
    }

    /** @test */
    public function カテゴリーの編集ページの技術メモのアイコンをクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
					->click('[data-test="index-icon"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの編集ページのブログの「一覧」をクリックしたとき、記事の一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
					->click('[data-test="blog-index"]')
                    ->assertPathIs('/admin')
                    ->assertSee('技術メモ')
                    ->assertSee('記事一覧');
        });
    }

	/** @test */
    public function カテゴリーの編集ページのブログの「新規作成」をクリックしたとき、記事の新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
					->click('[data-test="blog-create"]')
                    ->assertPathIs('/admin/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

	/** @test */
    public function カテゴリーの編集ページのカテゴリーの「一覧」をクリックしたとき、カテゴリーの一覧ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
					->click('[data-test="category-index"]')
                    ->assertPathIs('/admin/category')
                    ->assertSee('技術メモ')
                    ->assertSee('カテゴリー一覧');
        });
    }

	/** @test */
    public function カテゴリーの編集ページのカテゴリーの「新規作成」をクリックしたとき、カテゴリーの新規作成ページに遷移する()
    {
		$this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
					->click('[data-test="category-create"]')
                    ->assertPathIs('/admin/category/create')
                    ->assertSee('技術メモ')
                    ->assertSee('新規作成');
        });
    }

    /** @test */
    public function カテゴリーの編集ページで、カテゴリーを編集できる()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
                    ->type('name', 'カテゴリー1 更新')
                    ->type('order', '4')
					->click('input[type="submit"]')
                    ->assertPathIs('/admin/category/edit/1')
                    ->assertSee('技術メモ')
                    ->assertSee('編集')
                    ->assertInputValue('input[name="name"]', 'カテゴリー1 更新')
					->assertInputValue('input[name="order"]', "4");
        });
    }

    /** @test */
    public function カテゴリーの編集ページでカテゴリー名が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
                    ->type('name', '')
                    ->type('order', '4')
					->click('input[type="submit"]')
                    ->assertSee('※ カテゴリー名を入力して下さい');
        });
    }

    /** @test */
    public function カテゴリーの編集ページで順番が未入力だったら、エラーメッセージが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
					->visit('/admin/category/edit/1')
                    ->type('name', 'カテゴリー1 更新')
                    ->type('order', '')
					->click('input[type="submit"]')
                    ->assertSee('※ 順番を入力して下さい');
        });
    }
}