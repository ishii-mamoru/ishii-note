<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        RefreshDatabaseState::$migrated = false;
    }
    
    // ========================================
    // 記事の一覧ページ
    // ========================================

    /** @test */
    public function 記事の一覧ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.index')
            ->assertSeeInOrder(['タイトル1（公開）', 'タイトル2（非公開）', 'タイトル3（公開）']);
    }

    /** @test */
    public function 未ログインで記事の一覧ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外で記事の一覧ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin');
        $response->assertStatus(403);
    }

    // ========================================
    // 記事の新規作成ページ
    // ========================================

    /** @test */
    public function 記事の新規作成ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/create');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.create');
    }

    /** @test */
    public function 未ログインで記事の新規作成ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/create');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外で記事の新規作成ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin/create');
        $response->assertStatus(403);
    }

    /** @test */
    public function 正常なデータがポスト送信され、レコードが新規作成される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 

        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);

        $post = Post::where('id', 4)->first();

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
        $this->assertSame($post->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.edit', ['postId' => $post->id]));
    }

    /** @test */
    public function カテゴリーと本文がNULLのとき、レコードが新規作成される()
    {
        $params = [
            'status' => 1,
            'category' => NULL,
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => NULL,
        ];

        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        
        $post = Post::where('id', 4)->first();

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertNull($post->content);
        $this->assertSame($post->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.edit', ['postId' => $post->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時に新規作成処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->post(route('admin.store'), $params);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function 正常なデータがishiiユーザー以外で新規作成処理にポスト送信され、403エラーになる()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(2))->post(route('admin.store'), $params);
        $response->assertStatus(403);
    }

    /** @test */
    public function 新規作成処理でステータスがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => NULL,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['status' => '※ ステータスを選択して下さい']);
    }

    /** @test */
    public function 新規作成処理でステータスが文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 'テスト',
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['status' => '※ ステータスを選択して下さい']);
    }

    /** @test */
    public function 新規作成処理でカテゴリーが文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => 'テスト',
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['category' => '※ カテゴリーを選択して下さい']);
    }

    /** @test */
    public function 新規作成処理で投稿日時がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => NULL,
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['post_date' => '※ 投稿日時を入力して下さい']);
    }

    /** @test */
    public function 新規作成処理で投稿日時が文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => 'テスト',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['post_date' => '※ 投稿日時を入力して下さい']);
    }

    /** @test */
    public function 新規作成処理でタイトルがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => NULL,
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['title' => '※ タイトルを入力して下さい']);
    }

    /** @test */
    public function 新規作成処理でタイトルが300字のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'テストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテスト',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['title' => '※ タイトルは255字以下で入力して下さい']);
    }

    /** @test */
    public function 新規作成処理でサブタイトルがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => NULL,
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['subtitle' => '※ サブタイトルを入力して下さい']);
    }

    /** @test */
    public function 新規作成処理でサブタイトルが300字のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル4',
            'subtitle' => 'テストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテスト',
            'content' => '<p>本文4</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $response->assertSessionHasErrors(['subtitle' => '※ サブタイトルは255字以下で入力して下さい']);
    }

    // ========================================
    // 記事の編集ページ
    // ========================================

    /** @test */
    public function 記事の編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/1');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.edit')
            ->assertSee('タイトル1（公開）');
    }

    /** @test */
    public function 存在しない記事の編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/4');
        $response
            ->assertStatus(200)
            ->assertViewIs('errors.404');
    }

    /** @test */
    public function 未ログインで記事の編集ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/edit/1');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外で記事の編集ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin/edit/1');
        $response->assertStatus(403);
    }

    /** @test */
    public function 正常なデータがポスト送信され、レコードが編集される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 

        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        
        $post = Post::where('id', 1)->first();
    
        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1');
        $this->assertSame($post->subtitle, 'サブタイトル1');
        $this->assertSame($post->content, '<p>本文1</p>');
        $this->assertSame($post->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
        $this->assertSame($post->updated_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.edit', ['postId' => $post->id]));
    }

    /** @test */
    public function カテゴリーと本文がNULLのとき、レコードが編集される()
    {
        $params = [
            'status' => 1,
            'category' => NULL,
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => NULL,
        ];

        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        
        $post = Post::where('id', 1)->first();
        
        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1');
        $this->assertSame($post->subtitle, 'サブタイトル1');
        $this->assertNull($post->content);
        $this->assertSame($post->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.edit', ['postId' => $post->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時に編集処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->post(route('admin.update', ['postId' => 1]), $params);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function 正常なデータがishiiユーザー以外で編集処理にポスト送信され、403エラーになる()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(2))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertStatus(403);
    }

    /** @test */
    public function 編集処理でステータスがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => NULL,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['status' => '※ ステータスを選択して下さい']);
    }

    /** @test */
    public function 編集処理でステータスが文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 'テスト',
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['status' => '※ ステータスを選択して下さい']);
    }

    /** @test */
    public function 編集処理でカテゴリーが文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => 'テスト',
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['category' => '※ カテゴリーを選択して下さい']);
    }

    /** @test */
    public function 編集処理で投稿日時がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => NULL,
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['post_date' => '※ 投稿日時を入力して下さい']);
    }

    /** @test */
    public function 編集処理で投稿日時が文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => 'テスト',
            'title' => 'タイトル1',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ];
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['post_date' => '※ 投稿日時を入力して下さい']);
    }

    /** @test */
    public function 編集処理でタイトルがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => NULL,
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ];
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['title' => '※ タイトルを入力して下さい']);
    }

    /** @test */
    public function 編集処理でタイトルが300字のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'テストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテスト',
            'subtitle' => 'サブタイトル1',
            'content' => '<p>本文1</p>',
        ];
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['title' => '※ タイトルは255字以下で入力して下さい']);
    }

    /** @test */
    public function 編集処理でサブタイトルがNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => NULL,
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['subtitle' => '※ サブタイトルを入力して下さい']);
    }

    /** @test */
    public function 編集処理でサブタイトルが300字のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'status' => 1,
            'category' => [1, 2, 3],
            'post_date' => '2022-01-01 00:00',
            'title' => 'タイトル1',
            'subtitle' => 'テストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテストテスト',
            'content' => '<p>本文1</p>',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $response->assertSessionHasErrors(['subtitle' => '※ サブタイトルは255字以下で入力して下さい']);
    }

    // ========================================
    // 記事の削除処理
    // ========================================

    /** @test */
    public function レコードが削除される()
    {
        $response = $this->actingAs(User::find(1))->post(route('admin.destroy', ['postId' => 1]));
        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.index'));
    }

    /** @test */
    public function 未ログイン時にレコードを削除しようとすると、302エラーになる()
    {
        $response = $this->post(route('admin.destroy', ['postId' => 1]));
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でレコードを削除しようとすると、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->post(route('admin.destroy', ['postId' => 1]));
        $response->assertStatus(403);
    }

    // ========================================
    // カテゴリーの一覧ページ
    // ========================================

    /** @test */
    public function カテゴリーの一覧ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.category.index')
            ->assertSeeInOrder(['カテゴリー1', 'カテゴリー2', 'カテゴリー3']);
    }

    /** @test */
    public function 未ログインでカテゴリーの一覧ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの一覧ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin/category');
        $response->assertStatus(403);
    }

    // ========================================
    // カテゴリーの新規作成ページ
    // ========================================

    /** @test */
    public function カテゴリーの新規作成ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/create');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.category.create');
    }

    /** @test */
    public function 未ログインでカテゴリーの新規作成ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category/create');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの新規作成ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin/category/create');
        $response->assertStatus(403);
    }

    /** @test */
    public function 正常なデータがポスト送信され、カテゴリーのレコードが新規作成される()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー4',
        ];

        $response = $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        
        $category = Category::where('id', 4)->first();
        
        $this->assertSame($category->order, 1);
        $this->assertSame($category->name, 'カテゴリー4');
        $this->assertSame($category->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.category.edit', ['categoryId' => $category->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時にカテゴリーの新規作成処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->post(route('admin.category.store'), $params);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function 正常なデータがishiiユーザー以外でカテゴリーの新規作成処理にポスト送信され、403エラーになる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->actingAs(User::find(2))->post(route('admin.category.store'), $params);
        $response->assertStatus(403);
    }

    /** @test */
    public function カテゴリーの新規作成処理で順番がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => NULL,
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        $response->assertSessionHasErrors(['order' => '※ 順番を入力して下さい']);
    }

    /** @test */
    public function カテゴリーの新規作成処理で順番が文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => 'テスト',
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        $response->assertSessionHasErrors(['order' => '※ 順番は数字で入力して下さい']);
    }

    /** @test */
    public function カテゴリーの新規作成処理で名前がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => 1,
            'name' => NULL,
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        $response->assertSessionHasErrors(['name' => '※ カテゴリー名を入力して下さい']);
    }

    // ========================================
    // カテゴリーの編集ページ
    // ========================================

    /** @test */
    public function カテゴリーの編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/1');
        $response
            ->assertStatus(200)
            ->assertViewIs('admin.category.edit')
            ->assertSee('カテゴリー1');
    }

    /** @test */
    public function 存在しないカテゴリーの編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/4');
        $response
            ->assertStatus(200)
            ->assertViewIs('errors.404');
    }

    /** @test */
    public function 未ログインでカテゴリーの編集ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category/edit/1');
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーの編集ページのURLにアクセスしたとき、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->get('/admin/category/edit/1');
        $response->assertStatus(403);
    }

    /** @test */
    public function 正常なデータがポスト送信され、カテゴリーのレコードが編集される()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1 更新',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        
        $category = Category::where('id', 1)->first();

        $this->assertSame($category->order, 1);
        $this->assertSame($category->name, 'カテゴリー1 更新');
        $this->assertSame($category->created_at->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.category.edit', ['categoryId' => $category->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時にカテゴリー編集処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1 更新',
        ]; 
        $response = $this->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function 正常なデータがishiiユーザー以外でカテゴリー編集処理にポスト送信され、403エラーになる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1 更新',
        ]; 
        $response = $this->actingAs(User::find(2))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertStatus(403);
    }

    /** @test */
    public function カテゴリーの編集処理で順番がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => NULL,
            'name' => 'カテゴリー1 更新',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertSessionHasErrors(['order' => '※ 順番を入力して下さい']);
    }

    /** @test */
    public function カテゴリーの編集処理で順番が文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => 'テスト',
            'name' => 'カテゴリー1 更新',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertSessionHasErrors(['order' => '※ 順番は数字で入力して下さい']);
    }

    /** @test */
    public function カテゴリーの編集処理で名前がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => 1,
            'name' => NULL,
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertSessionHasErrors(['name' => '※ カテゴリー名を入力して下さい']);
    }

    // ========================================
    // カテゴリーの削除処理
    // ========================================

    /** @test */
    public function カテゴリーのレコードが削除される()
    {
        $response = $this->actingAs(User::find(1))->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.category.index'));
    }

    /** @test */
    public function 未ログイン時にカテゴリーのレコードを削除しようとすると、302エラーになる()
    {
        $response = $this->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーのレコードを削除しようとすると、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response->assertStatus(403);
    }
}