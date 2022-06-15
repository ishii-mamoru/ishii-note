<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BlogTest extends DuskTestCase
{
    // use DatabaseMigrations;
    // use RefreshDatabase;

    // public function setUp()
    // {
    //     parent::setUp();
    //     $this->artisan('db:seed');
    // }
    // public function setUp(): void
    // {
    //     parent::setUp();
    //     $this->artisan('db:seed');
    // }

    // use RefreshDatabase;
    // private $seed = true;

    // ========================================
    // トップページ
    // ========================================

    /** @test */
    // public function トップページにアクセスしたとき、トップページが表示される()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertPathIs('/')
    //                 ->assertSee('技術メモ')
    //                 ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
    //     });
    // }

    /** @test */
    // public function トップページの「ホーム」をクリックしたとき、トップページに遷移する()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->clickLink('ホーム')
    //                 ->assertPathIs('/')
    //                 ->assertSee('技術メモ')
    //                 ->assertSee('いろいろ試したことやわかったことを書いていく場所です。');
    //     });
    // }

    /** @test */
    // public function トップページの「メモ一覧」をクリックしたとき、トップページに遷移する()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->clickLink('メモ一覧')
    //                 ->assertPathIs('/blog/list')
    //                 ->assertSee('技術メモ')
    //                 ->assertSee('技術メモの一覧です。');
    //     });
    // }

    /** @test */
    // public function テスト()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/blog/show/1')
    //                 ->assertSee('本文');
    //     });
    // }

    
    // public function トップページの「タイトル1（公開）」をクリックしたとき、詳細ページに遷移する()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->clickLink('もっと見る →')
    //                 ->assertPathIs('/blog/category/1')
    //                 ->assertSee('タイトル1（公開）')
    //                 ->assertSee('サブタイトル1');
    //     });
    // }

    // public function トップページの「タイトル1（公開）」をクリックしたとき、詳細ページに遷移する()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->clickLink('タイトル1（公開）')
    //                 ->assertPathIs('/blog/show/1')
    //                 ->assertSee('タイトル1（公開）')
    //                 ->assertSee('サブタイトル1');
    //     });
    // }
}
