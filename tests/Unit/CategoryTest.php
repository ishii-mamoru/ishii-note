<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Tests\TestCase;
use App\Models\Category;
use Exception;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        RefreshDatabaseState::$migrated = false;
    }

    /** @test */
    public function カテゴリーを新規作成できる()
    {
        $params = [
            'order' => 4,
            'name' => 'カテゴリー4',
        ];

        Category::create($params);
        $category = Category::find(4);

        $this->assertSame($category->order, 4);
        $this->assertSame($category->name, 'カテゴリー4');
    }

    /** @test */
    public function すべてNULLでも、カテゴリーを新規作成できる()
    {
        Category::create();
        $category = Category::find(4);

        $this->assertNull($category->order);
        $this->assertNull($category->name);
    }

    /** @test */
    public function 順番が文字列だったら、カテゴリーを新規作成できない()
    {
        $params = [
            'order' => 'テスト',
            'name' => 'カテゴリー4',
        ];

        $this->expectException(Exception::class);
        Category::create($params);
    }

    /** @test */
    public function 順番が‐129だったら、カテゴリーを新規作成できない()
    {
        $params = [
            'order' => -129,
            'name' => 'カテゴリー4',
        ];

        $this->expectException(Exception::class);
        Category::create($params);
    }

    /** @test */
    public function 順番が‐128だったら、カテゴリーを新規作成できる()
    {
        $params = [
            'order' => -128,
            'name' => 'カテゴリー4',
        ];

        Category::create($params);
        $category = Category::find(4);

        $this->assertSame($category->order, -128);
        $this->assertSame($category->name, 'カテゴリー4');
    }

    /** @test */
    public function 順番が127だったら、カテゴリーを新規作成できる()
    {
        $params = [
            'order' => 127,
            'name' => 'カテゴリー4',
        ];

        Category::create($params);
        $category = Category::find(4);

        $this->assertSame($category->order, 127);
        $this->assertSame($category->name, 'カテゴリー4');
    }

    /** @test */
    public function 順番が128だったら、カテゴリーを新規作成できない()
    {
        $params = [
            'order' => 128,
            'name' => 'カテゴリー4',
        ];

        $this->expectException(Exception::class);
        Category::create($params);
    }

    /** @test */
    public function カテゴリー名が255文字だったら、カテゴリーを新規作成できる()
    {
        $params = [
            'order' => 4,
            'name' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
        ];

        Category::create($params);
        $category = Category::find(4);

        $this->assertSame($category->order, 4);
        $this->assertSame($category->name, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
    }

    /** @test */
    public function カテゴリー名が256文字だったら、カテゴリーを新規作成できない()
    {
        $params = [
            'order' => 4,
            'name' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
        ];

        $this->expectException(Exception::class);
        Category::create($params);
    }

    /** @test */
    public function カテゴリーを更新できる()
    {
        $params = [
            'order' => 4,
            'name' => 'カテゴリー1 更新',
        ];

        Category::where('id', 1)->update($params);
        $category = Category::find(1);

        $this->assertSame($category->order, 4);
        $this->assertSame($category->name, 'カテゴリー1 更新');
    }

    /** @test */
    public function 順番が文字列だったら、カテゴリーを更新できない()
    {
        $params = [
            'order' => 'テスト',
            'name' => 'カテゴリー1 更新',
        ];

        $this->expectException(Exception::class);
        Category::where('id', 1)->update($params);
    }

    /** @test */
    public function 順番が‐129だったら、カテゴリーを更新できない()
    {
        $params = [
            'order' => -129,
            'name' => 'カテゴリー1 更新',
        ];

        $this->expectException(Exception::class);
        Category::where('id', 1)->update($params);
    }

    /** @test */
    public function 順番が‐128だったら、カテゴリーを更新できる()
    {
        $params = [
            'order' => -128,
            'name' => 'カテゴリー1 更新',
        ];

        Category::where('id', 1)->update($params);
        $category = Category::find(1);

        $this->assertSame($category->order, -128);
        $this->assertSame($category->name, 'カテゴリー1 更新');
    }

    /** @test */
    public function 順番が127だったら、カテゴリーを更新できる()
    {
        $params = [
            'order' => 127,
            'name' => 'カテゴリー1 更新',
        ];

        Category::where('id', 1)->update($params);
        $category = Category::find(1);

        $this->assertSame($category->order, 127);
        $this->assertSame($category->name, 'カテゴリー1 更新');
    }

    /** @test */
    public function 順番が128だったら、カテゴリーを更新できない()
    {
        $params = [
            'order' => 128,
            'name' => 'カテゴリー1 更新',
        ];

        $this->expectException(Exception::class);
        Category::where('id', 1)->update($params);
    }

    /** @test */
    public function カテゴリー名が255文字だったら、カテゴリーを更新できる()
    {
        $params = [
            'order' => 4,
            'name' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
        ];

        Category::where('id', 1)->update($params);
        $category = Category::find(1);

        $this->assertSame($category->order, 4);
        $this->assertSame($category->name, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
    }

    /** @test */
    public function カテゴリー名が256文字だったら、カテゴリーを更新できない()
    {
        $params = [
            'order' => 4,
            'name' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
        ];

        $this->expectException(Exception::class);
        Category::where('id', 1)->update($params);
    }

    /** @test */
    public function カテゴリーを削除できる()
    {
        Category::destroy(1);
        $category = Category::find(1);
        $this->assertNull($category);
    }

    /** @test */
    public function 存在しないIDだったら、カテゴリーを削除できない()
    {
        $count = Category::destroy(4);
        $this->assertSame($count, 0);
    }
}
