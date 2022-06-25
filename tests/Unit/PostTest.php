<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Tests\TestCase;
use App\Models\Post;
use Exception;
use TypeError;

class PostTest extends TestCase
{
    use RefreshDatabase;
    private $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        RefreshDatabaseState::$migrated = false;
    }

    /** @test */
    public function 記事を新規作成できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function すべてNULLでも、記事を新規作成できる()
    {
        Post::create();
        $post = Post::find(4);
        $this->assertSame($post->status, 2);
    }

    /** @test */
    public function 投稿ステータスが文字列だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 'テスト',
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function 投稿ステータスが‐129だったら、記事を新規作成できない()
    {
        $params = [
            'status' => -129,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function 投稿ステータスが‐128だったら、記事を新規作成できる()
    {
        $params = [
            'status' => -128,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, -128);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function 投稿ステータスが127だったら、記事を新規作成できる()
    {
        $params = [
            'status' => 127,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, 127);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function 投稿ステータスが128だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 128,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function カテゴリーが255文字だったら、記事を新規作成できる()
    {
        $params = [
            'status' => 1,
            'category' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function カテゴリーが256文字だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 1,
            'category' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function 投稿日時が文字列だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => 'テスト',
            'title' => 'タイトル4',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function タイトルが255文字だったら、記事を新規作成できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->subtitle, 'サブタイトル4');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function タイトルが256文字だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'subtitle' => 'サブタイトル4',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    /** @test */
    public function サブタイトルが255文字だったら、記事を新規作成できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'content' => '<p>本文4</p>',
        ];

        Post::create($params);
        $post = Post::find(4);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル4');
        $this->assertSame($post->subtitle, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->content, '<p>本文4</p>');
    }

    /** @test */
    public function サブタイトルが256文字だったら、記事を新規作成できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル4',
            'subtitle' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'content' => '<p>本文4</p>',
        ];

        $this->expectException(Exception::class);
        Post::create($params);
    }

    // TODO 本文の文字数のテストは今後検討

    /** @test */
    public function 記事を更新できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1 更新');
        $this->assertSame($post->subtitle, 'サブタイトル1 更新');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function 投稿ステータスが文字列だったら、記事を更新できない()
    {
        $params = [
            'status' => 'テスト',
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function 投稿ステータスが‐129だったら、記事を更新できない()
    {
        $params = [
            'status' => -129,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function 投稿ステータスが‐128だったら、記事を更新できる()
    {
        $params = [
            'status' => -128,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, -128);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1 更新');
        $this->assertSame($post->subtitle, 'サブタイトル1 更新');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function 投稿ステータスが127だったら、記事を更新できる()
    {
        $params = [
            'status' => 127,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, 127);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1 更新');
        $this->assertSame($post->subtitle, 'サブタイトル1 更新');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function 投稿ステータスが128だったら、記事を更新できない()
    {
        $params = [
            'status' => 128,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function カテゴリーが255文字だったら、記事を更新できる()
    {
        $params = [
            'status' => 1,
            'category' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1 更新');
        $this->assertSame($post->subtitle, 'サブタイトル1 更新');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function カテゴリーが256文字だったら、記事を更新できない()
    {
        $params = [
            'status' => 1,
            'category' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function 投稿日時が文字列だったら、記事を更新できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => 'テスト',
            'title' => 'タイトル1 更新',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function タイトルが255文字だったら、記事を更新できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->subtitle, 'サブタイトル1 更新');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function タイトルが256文字だったら、記事を更新できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'subtitle' => 'サブタイトル1 更新',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    /** @test */
    public function サブタイトルが255文字だったら、記事を更新できる()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字',
            'content' => '<p>本文1</p>',
        ];

        Post::where('id', 1)->update($params);
        $post = Post::find(1);

        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), '2022-01-01 00:00');
        $this->assertSame($post->title, 'タイトル1 更新');
        $this->assertSame($post->subtitle, '255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字255文字');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function サブタイトルが256文字だったら、記事を更新できない()
    {
        $params = [
            'status' => 1,
            'category' => '1,2,3',
            'post_date' => date('Y-m-d\TH:i', strtotime('2022-01-01 00:00')),
            'title' => 'タイトル1 更新',
            'subtitle' => '256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字256文字2',
            'content' => '<p>本文1</p>',
        ];

        $this->expectException(Exception::class);
        Post::where('id', 1)->update($params);
    }

    // TODO 本文の文字数のテストは今後検討

    /** @test */
    public function 記事を削除できる()
    {
        Post::destroy(1);
        $post = Post::find(1);
        $this->assertNull($post);
    }

    /** @test */
    public function 存在しないIDだったら、記事を削除できない()
    {
        $count = Post::destroy(4);
        $this->assertSame($count, 0);
    }

    /** @test */
    public function 最新記事5件を取得できる()
    {
        $posts = Post::GetLatePostList();
        $i = 1;

        foreach ($posts as $post) {
            if ($i === 1) {
                $this->assertSame($post->id, 1);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル1（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル1');

            } elseif ($i === 2) {
                $this->assertSame($post->id, 3);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル3（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル3');
            }

            $i++;
        }
    }

    // TODO ちゃんと5件だけ取得できるかどうかも検証した方がいい？
    // Seederで5件以上入れるようにする？

    /** @test */
    public function 公開記事を取得できる()
    {
        $post = Post::GetPublicPost(1);

        $this->assertSame($post->id, 1);
        $this->assertSame($post->status, 1);
        $this->assertSame($post->category, '1,2,3');
        $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
        $this->assertSame($post->title, 'タイトル1（公開）');
        $this->assertSame($post->subtitle, 'サブタイトル1');
        $this->assertSame($post->content, '<p>本文1</p>');
    }

    /** @test */
    public function 未公開記事だと、公開記事を取得できない()
    {
        $post = Post::GetPublicPost(2);
        $this->assertSame($post->count(), 0);
    }

    /** @test */
    public function 引数が文字列だと、公開記事を取得できない()
    {
        $this->expectException(TypeError::class);
        Post::GetPublicPost('テスト');
    }

    /** @test */
    public function 引数が存在しないIDだと、公開記事を取得できない()
    {
        $post = Post::GetPublicPost(4);
        $this->assertSame($post->count(), 0);
    }

    /** @test */
    public function 公開記事一覧を取得する()
    {
        $posts = Post::GetPublicPostList();
        $i = 1;

        foreach ($posts as $post) {
            if ($i === 1) {
                $this->assertSame($post->id, 1);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル1（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル1');

            } elseif ($i === 2) {
                $this->assertSame($post->id, 3);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル3（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル3');
            }

            $i++;
        }
    }

    /** @test */
    public function カテゴリー別記事一覧を取得できる()
    {
        $posts = Post::GetCategoryPostList(1);
        $i = 1;

        foreach ($posts as $post) {
            if ($i === 1) {
                $this->assertSame($post->id, 1);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル1（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル1');

            } elseif ($i === 2) {
                $this->assertSame($post->id, 3);
                $this->assertSame($post->status, 1);
                $this->assertSame($post->category, '1,2,3');
                $this->assertSame($post->post_date->format('Y-m-d H:i'), date('Y-m-d H:i'));
                $this->assertSame($post->title, 'タイトル3（公開）');
                $this->assertSame($post->subtitle, 'サブタイトル3');
            }

            $i++;
        }
    }

    /** @test */
    public function 引数が文字列だと、カテゴリー別記事一覧を取得できない()
    {
        $this->expectException(TypeError::class);
        Post::GetCategoryPostList('テスト');
    }

    /** @test */
    public function 引数が存在しないIDだと、カテゴリー別記事一覧を取得できない()
    {
        $posts = Post::GetCategoryPostList(4);
        $this->assertSame($posts->count(), 0);
    }
}