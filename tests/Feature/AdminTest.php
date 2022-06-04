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
    
    /**
     * indexページ読み込み
     */
    public function test_index_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * 新規作成ページ読み込み
     */
    public function test_create_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/create');
        $response->assertStatus(200);
    }

    /**
     * 編集ページ読み込み
     */
    public function test_edit_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/edit/1');
        $response->assertStatus(200);
    }

    /**
     * カテゴリーindexページ読み込み
     */
    public function test_category_index_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category');
        $response->assertStatus(200);
    }

    /**
     * カテゴリー新規作成ページ読み込み
     */
    public function test_category_create_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/create');
        $response->assertStatus(200);
    }

    /**
     * カテゴリー編集ページ読み込み
     */
    public function test_category_edit_page_can_rendered()
    {
        $response = $this->actingAs(User::find(1))->get('/admin/category/edit/1');
        $response->assertStatus(200);
    }
}
