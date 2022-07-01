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
        $this
            ->get('/')
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])
            ->assertDontSee('タイトル2（非公開）');
    }

    // ========================================
    // 詳細ページ
    // ========================================

    /** @test */
    public function 詳細ページのURLにアクセスしたときに、正常に画面が表示される()
    {
        $this
            ->get('/blog/show/1')
            ->assertStatus(200)
            ->assertViewIs('blog.show')
            ->assertSee('タイトル1（公開）');
    }

    /** @test */
    public function 非公開記事の詳細ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $this
            ->get('/blog/show/2')
            ->assertStatus(200)
            ->assertViewIs('errors.404');
    }

    /** test */
    public function 存在しない記事の詳細ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $this
            ->get('/blog/show/4')
            ->assertStatus(200)
            ->assertViewIs('errors.404');
    }

    // ========================================
    // 一覧ページ
    // ========================================

    /** @test */
    public function 一覧ページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $this
            ->get('/blog/list')
            ->assertStatus(200)
            ->assertViewIs('blog.list')
            ->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])
            ->assertDontSee('タイトル2（非公開）');
    }

    // ========================================
    // カテゴリーページ
    // ========================================

    /** @test */
    public function カテゴリーページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $this
            ->get('/blog/category/1')
            ->assertStatus(200)
            ->assertViewIs('blog.category')
            ->assertSeeInOrder(['タイトル1（公開）', 'タイトル3（公開）'])
            ->assertDontSee('タイトル2（非公開）');
    }

    /** @test */
    public function 存在しないカテゴリーのページのURLにアクセスしたとき、正常に画面が表示される()
    {
        $this
            ->get('/blog/category/4')
            ->assertStatus(200)
            ->assertViewIs('errors.404');
    }
}
