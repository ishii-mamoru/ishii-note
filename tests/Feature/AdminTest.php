<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

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
}