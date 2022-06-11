<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;
    
    // ========================================
    // 記事の一覧ページ
    // ========================================

    /** @test */
    public function 記事の一覧ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function 記事の一覧ページのURLにアクセスしたとき、記事の一覧ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin');
        $response->assertViewIs('admin.index');
    }

    /** @test */
    public function 記事の一覧ページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->actingAs(User::find(1))->get('/admin');
        $response->assertSeeInOrder(['タイトル1（公開）', 'タイトル2（非公開）', 'タイトル3（公開）']);
    }

    /** @test */
    public function 未ログインで記事の一覧ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインで記事の一覧ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(200);
    }

    /** @test */
    public function 記事の新規作成ページのURLにアクセスしたとき、記事の新規作成ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/create');
        $response->assertViewIs('admin.create');
    }

    /** @test */
    public function 未ログインで記事の新規作成ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/create');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインで記事の新規作成ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin/create');
        $response->assertRedirect(route('login'));
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
        $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $data = Post::where('title', 'タイトル4')->first();
        $this->assertNotNull($data);
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
        $this->actingAs(User::find(1))->post(route('admin.store'), $params);
        $data = Post::where('title', 'タイトル4')->first();
        $this->assertNotNull($data);
    }

    /** @test */
    public function 新規作成処理完了後に、編集ページにリダイレクトされる()
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
        $data = Post::where('title', 'タイトル4')->first();
        $response->assertRedirect(route('admin.edit', ['postId' => $data->id]));
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
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(200);
    }

    /** @test */
    public function 記事の編集ページのURLにアクセスしたとき、記事の編集ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/1');
        $response->assertViewIs('admin.edit');
    }

    /** @test */
    public function 記事の編集ページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/1');
        $response->assertSee('タイトル1（公開）');
    }

    /** @test */
    public function 存在しない記事の編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/4');
        $response->assertStatus(200);
    }

    /** @test */
    public function 存在しない記事の編集ページのURLにアクセスしたとき、404ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/4');
        $response->assertViewIs('errors.404');
    }

    /** @test */
    public function 未ログインで記事の編集ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/edit/1');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインで記事の編集ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin/edit/1');
        $response->assertRedirect(route('login'));
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
        $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $data = Post::where('title', 'タイトル1')->first();
        $this->assertNotNull($data);
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
        $this->actingAs(User::find(1))->post(route('admin.update', ['postId' => 1]), $params);
        $data = Post::where('title', 'タイトル1')->first();
        $this->assertNotNull($data);
    }

    /** @test */
    public function 編集処理完了後に、編集ページにリダイレクトされる()
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
        $response->assertRedirect(route('admin.edit', ['postId' => 1]));
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
        $response->assertRedirect(route('login'));
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
        // ToDo 200じゃないのかな？？
        $response->assertStatus(302);
    }

    /** @test */
    public function レコードを削除したとき、一覧ページにリダイレクトされる()
    {
        $response = $this->actingAs(User::find(1))->post(route('admin.destroy', ['postId' => 1]));
        $response->assertRedirect(route('admin.index'));
    }

    /** @test */
    public function 未ログイン時にレコードを削除しようとすると、302エラーになる()
    {
        $response = $this->post(route('admin.destroy', ['postId' => 1]));
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログイン時にレコードを削除しようとすると、ログインページにリダイレクトされる()
    {
        $response = $this->post(route('admin.destroy', ['postId' => 1]));
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(200);
    }

    /** @test */
    public function カテゴリーの一覧ページのURLにアクセスしたとき、カテゴリーの一覧ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category');
        $response->assertViewIs('admin.category.index');
    }

    /** @test */
    public function カテゴリーの一覧ページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category');
        $response->assertSeeInOrder(['カテゴリー1', 'カテゴリー2', 'カテゴリー3']);
    }

    /** @test */
    public function 未ログインでカテゴリーの一覧ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインでカテゴリーの一覧ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin/category');
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(200);
    }

    /** @test */
    public function カテゴリーの新規作成ページのURLにアクセスしたとき、カテゴリーの新規作成ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/create');
        $response->assertViewIs('admin.category.create');
    }

    /** @test */
    public function 未ログインでカテゴリーの新規作成ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category/create');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインでカテゴリーの新規作成ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin/category/create');
        $response->assertRedirect(route('login'));
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
        $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        $data = Category::where('name', 'カテゴリー4')->first();
        $this->assertNotNull($data);
    }

    /** @test */
    public function カテゴリー新規作成処理完了後に、編集ページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.store'), $params);
        $data = Category::where('name', 'カテゴリー4')->first();
        $response->assertRedirect(route('admin.category.edit', ['categoryId' => $data->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時にカテゴリーの新規作成処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー4',
        ]; 
        $response = $this->post(route('admin.category.store'), $params);
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(200);
    }

    /** @test */
    public function カテゴリーの編集ページのURLにアクセスしたとき、カテゴリーの編集ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/1');
        $response->assertViewIs('admin.category.edit');
    }

    /** @test */
    public function カテゴリーの編集ページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/1');
        $response->assertSee('カテゴリー1');
    }

    /** @test */
    public function 存在しないカテゴリーの編集ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/4');
        $response->assertStatus(200);
    }

    /** @test */
    public function 存在しないカテゴリーの編集ページのURLにアクセスしたとき、404ページが表示される()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/4');
        $response->assertViewIs('errors.404');
    }

    /** @test */
    public function 未ログインでカテゴリーの編集ページのURLにアクセスしたとき、302エラーになる()
    {
        $response = $this->get('/admin/category/edit/1');
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログインでカテゴリーの編集ページのURLにアクセスしたとき、ログインページにリダイレクトされる()
    {
        $response = $this->get('/admin/category/edit/1');
        $response->assertRedirect(route('login'));
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
            'name' => 'カテゴリー1',
        ]; 
        $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $data = Category::where('name', 'カテゴリー1')->first();
        $this->assertNotNull($data);
    }

    /** @test */
    public function カテゴリー編集処理完了後に、編集ページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $data = Category::where('name', 'カテゴリー1')->first();
        $response->assertRedirect(route('admin.category.edit', ['categoryId' => $data->id]));
    }

    /** @test */
    public function 正常なデータが未ログイン時にカテゴリー編集処理にポスト送信され、ログインページにリダイレクトされる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1',
        ]; 
        $response = $this->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 正常なデータがishiiユーザー以外でカテゴリー編集処理にポスト送信され、403エラーになる()
    {
        $params = [
            'order' => 1,
            'name' => 'カテゴリー1',
        ]; 
        $response = $this->actingAs(User::find(2))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertStatus(403);
    }

    /** @test */
    public function カテゴリーの編集処理で順番がNULLのとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => NULL,
            'name' => 'カテゴリー1',
        ]; 
        $response = $this->actingAs(User::find(1))->post(route('admin.category.update', ['categoryId' => 1]), $params);
        $response->assertSessionHasErrors(['order' => '※ 順番を入力して下さい']);
    }

    /** @test */
    public function カテゴリーの編集処理で順番が文字列のとき、バリデーションメッセージが表示される()
    {
        $params = [
            'order' => 'テスト',
            'name' => 'カテゴリー1',
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
    // 記事の削除処理
    // ========================================

    /** @test */
    public function カテゴリーのレコードが削除される()
    {
        $response = $this->actingAs(User::find(1))->post(route('admin.category.destroy', ['categoryId' => 1]));
        // ToDo 200じゃないのかな？？
        $response->assertStatus(302);
    }

    /** @test */
    public function カテゴリーのレコードを削除したとき、一覧ページにリダイレクトされる()
    {
        $response = $this->actingAs(User::find(1))->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response->assertRedirect(route('admin.category.index'));
    }

    /** @test */
    public function 未ログイン時にカテゴリーのレコードを削除しようとすると、302エラーになる()
    {
        $response = $this->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response->assertStatus(302);
    }

    /** @test */
    public function 未ログイン時にカテゴリーのレコードを削除しようとすると、ログインページにリダイレクトされる()
    {
        $response = $this->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function ishiiユーザー以外でカテゴリーのレコードを削除しようとすると、403エラーになる()
    {
        $response = $this->actingAs(User::find(2))->post(route('admin.category.destroy', ['categoryId' => 1]));
        $response->assertStatus(403);
    }
}