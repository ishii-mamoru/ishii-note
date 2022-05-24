<?php

namespace App\Http\Controllers;

use App\Models\Post;
// use Illuminate\Http\Request;
// use App\Http\Requests\BlogPostRequest;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminController extends Controller
{
  public function index()
	{
		$posts = Post::GetPostList();
		return view('admin.index', compact('posts'));
	}

  public function create()
	{
		return view('admin.create');
	}

  public function store(PostRequest $request)
	{
		$params = $request->all();
		$params['user_id'] = Auth::id();
		$params['category'] = implode(',', $params['category']);

		try {
			$post = Post::create($params);
			return redirect()->route('admin.edit', ['postId' => $post->id])->withSuccess('データを登録しました。');

		} catch (Exception $e) {

			return redirect()->route('admin.create')->withError('データの登録に失敗しました。');
		}	
	}

  public function edit(int $postId)
	{
		$post = Post::GetPost($postId);

		// 未公開・削除記事
		if (!$post->count())
		{
			return view('errors.404');
		}

		$post->category = explode(',', $post->category);
		return view('admin.edit', compact('post'));
	}

  public function update(BlogPostRequest $request, int $postId)
	{
		$params = $request->except('_token');
		$params['category'] = implode(',', $params['category']);

		try {
			Post::where('id', $postId)->update($params);
			return redirect()->route('admin.edit', ['postId' => $postId])->withsuccess('データを更新しました。');

		} catch (Exception $e) {

			return redirect()->route('admin.edit', ['postId' => $postId])->withError('データの更新に失敗しました。');
		}
	}

  public function destroy(int $postId)
	{
		try {
			Post::destroy($postId);
			return redirect()->route('admin.index')->withsuccess('データを削除しました。');

		} catch (Exception $e) {

			return redirect()->route('admin.edit', ['postId' => $postId])->withError('データの削除に失敗しました。');
		}
	}
}
