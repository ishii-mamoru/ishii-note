<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::GetLatePostList();
        $categories = Category::oldest('order')->get();

				foreach ($posts as $post) {
					$post->category = $post->category ? explode(',', $post->category) : null;
				}

				return view('blog.index', compact('posts', 'categories'));
    }

    public function show(int $postId)
		{
			$post = Post::GetPublicPost($postId);

			// 未公開・削除記事
			if (!$post->count())
			{
				return view('errors.404');
			}

			$categories = Category::oldest('order')->get();

			$post->category = $post->category ? explode(',', $post->category) : null;

			return view('blog.show', compact('post', 'categories'));
		}

    public function list()
		{
			$posts = Post::GetPublicPostList();
			$categories = Category::oldest('order')->get();

			foreach ($posts as $post) {
				$post->category = $post->category ? explode(',', $post->category) : null;
			}

			return view('blog.list', compact('posts', 'categories'));
		}

    public function category(int $categoryId)
		{
			$posts = Post::GetCategoryPostList($categoryId);
			$categories = Category::oldest('order')->get();

			foreach ($posts as $post) {
				$post->category = $post->category ? explode(',', $post->category) : null;
			}

			return view('blog.category', compact('posts', 'categoryId', 'categories'));
		}
}
