<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->length(1)->default(2)->index()->comment('投稿ステータス(1:公開, 2:非公開)');
            $table->string('category')->nullable()->default(null)->index()->comment('カテゴリー');
            $table->datetime('post_date')->nullable()->default(null)->comment('投稿日時');
            $table->string('title')->nullable()->default(null)->comment('タイトル');
            $table->string('subtitle')->nullable()->default(null)->comment('サブタイトル');
            $table->text('content')->nullable()->default(null)->comment('本文');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
