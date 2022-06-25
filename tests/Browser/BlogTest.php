<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BlogTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    // ========================================
    // トップページ
    // ========================================

    /** @test */
    public function トップページにアクセスしたとき、トップページが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/')
                    ->assertSee('技術メモ')
                    ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
        });
    }

    /** @test */
    public function トップページの「ホーム」をクリックしたとき、トップページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('ホーム')
                    ->assertPathIs('/')
                    ->assertSee('技術メモ')
                    ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
        });
    }

    /** @test */
    public function トップページの「メモ一覧」をクリックしたとき、一覧ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('メモ一覧')
                    ->assertPathIs('/blog/list')
                    ->assertSee('技術メモ')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    /** @test */
    public function トップページの「タイトル1（公開）」をクリックしたとき、詳細ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('タイトル1（公開）')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function トップページの記事内の「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('[data-test="category-tag-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function トップページのカテゴリーメニューの「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('[data-test="category-menu-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function トップページの「メモ一覧へ→」をクリックしたとき、一覧ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('メモ一覧へ →')
                    ->assertPathIs('/blog/list')
                    ->assertSee('メモ一覧')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    // ========================================
    // 一覧ページ
    // ========================================

    /** @test */
    public function 一覧ページにアクセスしたとき、一覧ページが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->assertPathIs('/blog/list')
                    ->assertSee('メモ一覧')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    /** @test */
    public function 一覧ページの「ホーム」をクリックしたとき、トップページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->clickLink('ホーム')
                    ->assertPathIs('/')
                    ->assertSee('技術メモ')
                    ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
        });
    }

    /** @test */
    public function 一覧ページの「メモ一覧」をクリックしたとき、一覧ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->clickLink('メモ一覧')
                    ->assertPathIs('/blog/list')
                    ->assertSee('技術メモ')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    /** @test */
    public function 一覧ページの「タイトル1（公開）」をクリックしたとき、詳細ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->clickLink('タイトル1（公開）')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function 一覧ページの記事内の「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->click('[data-test="category-tag-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function 一覧ページの記事内の「もっと見る→」をクリックしたとき、詳細ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->click('[data-test="show-1"]')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function 一覧ページのカテゴリーメニューの「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/list')
                    ->click('[data-test="category-menu-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    // ========================================
    // カテゴリーページ
    // ========================================

    /** @test */
    public function カテゴリーページにアクセスしたとき、カテゴリーページが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function カテゴリーページの「ホーム」をクリックしたとき、トップページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->clickLink('ホーム')
                    ->assertPathIs('/')
                    ->assertSee('技術メモ')
                    ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
        });
    }

    /** @test */
    public function カテゴリーページの「メモ一覧」をクリックしたとき、一覧ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->clickLink('メモ一覧')
                    ->assertPathIs('/blog/list')
                    ->assertSee('技術メモ')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    /** @test */
    public function カテゴリーページの「タイトル1（公開）」をクリックしたとき、詳細ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->clickLink('タイトル1（公開）')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function カテゴリーページの記事内の「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->click('[data-test="category-tag-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function カテゴリーページの記事内の「もっと見る→」をクリックしたとき、詳細ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->click('[data-test="show-1"]')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function カテゴリーページのカテゴリーメニューの「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/category/1')
                    ->click('[data-test="category-menu-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    // ========================================
    // 詳細ページ
    // ========================================

    /** @test */
    public function 詳細ページにアクセスしたとき、詳細ページが表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/show/1')
                    ->assertPathIs('/blog/show/1')
                    ->assertSee('タイトル1（公開）')
                    ->assertSee('サブタイトル1');
        });
    }

    /** @test */
    public function 詳細ページの「ホーム」をクリックしたとき、トップページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/show/1')
                    ->clickLink('ホーム')
                    ->assertPathIs('/')
                    ->assertSee('技術メモ')
                    ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
        });
    }

    /** @test */
    public function 詳細ページの「メモ一覧」をクリックしたとき、一覧ページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/show/1')
                    ->clickLink('メモ一覧')
                    ->assertPathIs('/blog/list')
                    ->assertSee('技術メモ')
                    ->assertSee('技術メモの一覧です。');
        });
    }

    /** @test */
    public function 詳細ページの記事内の「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/show/1')
                    ->click('[data-test="category-tag-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }

    /** @test */
    public function 詳細ページのカテゴリーメニューの「カテゴリー1」をクリックしたとき、カテゴリーページに遷移する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog/show/1')
                    ->click('[data-test="category-menu-1"]')
                    ->assertPathIs('/blog/category/1')
                    ->assertSee('カテゴリー1')
                    ->assertSee('カテゴリー1の一覧です。');
        });
    }
}