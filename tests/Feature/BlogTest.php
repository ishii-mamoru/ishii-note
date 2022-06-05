<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;

    // ========================================
    // トップページ
    // ========================================

    /** @test */
    public function トップページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function トップページのURLにアクセスしたとき、トップページが表示される()
    {
        $response = $this->get('/');
        $response->assertViewIs('blog.index');
    }

    /** @test */
    public function トップページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->get('/');
        $response->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])->assertDontSee('タイトル2（非公開）');
    }

    // ========================================
    // 詳細ページ
    // ========================================

    /** @test */
    public function 詳細ページのURLにアクセスしたときに、正常に画面が表示される()
    {
        $response = $this->get('/blog/show/1');
        $response->assertStatus(200);
    }

    /** @test */
    public function 詳細ページのURLにアクセスしたときに、記事の詳細ページが表示される()
    {
        $response = $this->get('/blog/show/1');
        $response->assertViewIs('blog.show');
    }

    /** @test */
    public function 詳細ページのURLにアクセスしたとき、ページに表示したい内容が表示される()
    {
        $response = $this->get('/blog/show/1');
        $response->assertSee('タイトル1（公開）');
    }

    /** @test */
    public function 非公開記事の詳細ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/blog/show/2');
        $response->assertStatus(200);
    }

    /** @test */
    public function 非公開記事の詳細ページのURLにアクセスしたとき、404ページが表示される()
    {
        $response = $this->get('/blog/show/2');
        $response->assertViewIs('errors.404');
    }

    /** @test */
    public function 存在しない記事の詳細ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/blog/show/4');
        $response->assertStatus(200);
    }

    /** @test */
    public function 存在しない記事の詳細ページのURLにアクセスしたとき、404ページが表示される()
    {
        $response = $this->get('/blog/show/4');
        $response->assertViewIs('errors.404');
    }

    // ========================================
    // 一覧ページ
    // ========================================

    /** @test */
    public function 一覧ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/blog/list');
        $response->assertStatus(200);
    }

    /** @test */
    public function 一覧ページのURLにアクセスしたとき、一覧ページが表示される()
    {
        $response = $this->get('/blog/list');
        $response->assertViewIs('blog.list');
    }

    /** @test */
    public function 一覧ページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->get('/blog/list');
        $response->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])->assertDontSee('タイトル2（非公開）');
    }

    // ========================================
    // カテゴリーページ
    // ========================================

    /** @test */
    public function カテゴリーページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/blog/category/1');
        $response->assertStatus(200);
    }

    /** @test */
    public function カテゴリーページのURLにアクセスしたとき、カテゴリーページが表示される()
    {
        $response = $this->get('/blog/category/1');
        $response->assertViewIs('blog.category');
    }

    /** @test */
    public function カテゴリーページのURLにアクセスしたとき、ページに表示したい内容が存在する()
    {
        $response = $this->get('/blog/category/1');
        $response->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])->assertDontSee('タイトル2（非公開）');
    }

    /** @test */
    public function 存在しないカテゴリーのページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $response = $this->get('/blog/category/4');
        $response->assertStatus(200);
    }

    /** @test */
    public function 存在しないカテゴリーのページのURLにアクセスしたとき、404ページが表示される()
    {
        $response = $this->get('/blog/category/4');
        $response->assertViewIs('errors.404');
    }
}
