<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;

    /**
     * トップページ読み込み
     */
    public function test_index_page_can_rendered()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * 詳細ページ読み込み
     */
    public function test_show_page_can_rendered()
    {
        $response = $this->get('/blog/show/1');
        $response->assertStatus(200);
    }

    /**
     * 一覧ページ読み込み
     */
    public function test_list_page_can_rendered()
    {
        $response = $this->get('/blog/list');
        $response->assertStatus(200);
    }

    /**
     * カテゴリーページ読み込み
     */
    public function test_category_page_can_rendered()
    {
        $response = $this->get('/blog/category/1');
        $response->assertStatus(200);
    }
}
