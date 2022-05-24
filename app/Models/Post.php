<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'category',
        'post_date',
        'title',
        'subtitle',
        'content',
    ];

    protected $dates = [
        'post_date',
        'deleted_at',
    ];

    /**
     * ユーザー用
     */
    // 最新記事5件
    public function scopeGetLatePostList(Builder $query)
    {
        return $query
            ->select('id', 'status', 'category', 'post_date', 'title', 'subtitle')
            ->where('status', config('consts.post.status_id.public'))
			->orderBy('post_date', 'desc')
            ->limit(5)
			->get();
    }

    // 公開記事
    public function scopeGetPublicPost(Builder $query, int $postId)
    {
        return $query
            ->select('id', 'status', 'category', 'post_date', 'title', 'subtitle', 'content')
            ->where('id', $postId)
            ->where('status', config('consts.post.status_id.public'))
			->first();
    }

    // 公開記事一覧
    public function scopeGetPublicPostList(Builder $query)
    {
        return $query
            ->select('id', 'status', 'category', 'post_date', 'title', 'subtitle')
            ->where('status', config('consts.post.status_id.public'))
			->orderBy('id', 'desc')
			->get();
    }

    // カテゴリー別記事一覧
    public function scopeGetCategoryPostList(Builder $query, int $categoryId)
    {
        return $query
            ->select('id', 'status', 'category', 'post_date', 'title', 'subtitle')
            ->where('status', config('consts.post.status_id.public'))
            ->whereRaw('FIND_IN_SET(' . $categoryId . ',category)')
			->orderBy('id', 'desc')
			->get();
    }

    /**
     * 管理画面用
     */
    // 記事一覧
    public function scopeGetPostList(Builder $query)
    {
        return $query
            ->select('id', 'status', 'post_date', 'title', 'updated_at')
			->orderBy('id', 'desc')
			->get();
    }

    // 編集用 記事を取得 
    public function scopeGetPost(Builder $query, int $postId)
    {
        return $query
            ->select('id', 'status', 'category', 'post_date', 'title', 'subtitle', 'content')
            ->where('id', $postId)
			->first();
    }
}
