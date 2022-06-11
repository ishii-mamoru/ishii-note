<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminController extends Controller
{
  public function index()
	{
		$posts = Post::all();
		return view('admin.index', compact('posts'));
	}

  public function create()
	{
		$categories = Category::oldest('order')->get();
		return view('admin.create', compact('categories'));
	}

  public function store(PostRequest $request)
	{
		$params = $request->all();
		$params['user_id'] = Auth::id();
		$params['category'] = $params['category'] ? implode(',', $params['category']) : '';
		$log = ['name' => 'store'];

		try {
			$post = Post::create($params);
			$log['data'] = $post;
			Log::channel('blog')->info($log);
			return redirect()->route('admin.edit', ['postId' => $post->id])->withSuccess('データを登録しました。');

		} catch (Exception $e) {

			$log[] = [
				'data' => $params,
				'exception' => $e,
			];
			Log::channel('blog')->error($log);
			return redirect()->route('admin.create')->withError('データの登録に失敗しました。');
		}	
	}

  public function edit(int $postId)
	{
		$post = Post::find($postId);

		// 未公開・削除記事
		if (!$post)
		{
			return view('errors.404');
		}

		$categories = Category::oldest('order')->get();

		$post->category = explode(',', $post->category);
		
		return view('admin.edit', compact('post', 'categories'));
	}

  public function update(PostRequest $request, int $postId)
	{
		$params = $request->except('_token');
		$params['category'] = $params['category'] ? implode(',', $params['category']) : '';
		$log = [
			'name' => 'update',
			'data' => $params,
		];

		try {
			Post::where('id', $postId)->update($params);
			Log::channel('blog')->info($log);
			return redirect()->route('admin.edit', ['postId' => $postId])->withsuccess('データを更新しました。');

		} catch (Exception $e) {

			$log['exception'] = $e;
			Log::channel('blog')->error($log);
			return redirect()->route('admin.edit', ['postId' => $postId])->withError('データの更新に失敗しました。');
		}
	}

  public function destroy(int $postId)
	{
		$log = [
			'name' => 'destroy',
			'data' => $postId,
		];

		try {
			Post::destroy($postId);
			Log::channel('blog')->info($log);
			return redirect()->route('admin.index')->withsuccess('データを削除しました。');

		} catch (Exception $e) {

			$log['exception'] = $e;
			Log::channel('blog')->error($log);
			return redirect()->route('admin.edit', ['postId' => $postId])->withError('データの削除に失敗しました。');
		}
	}
}
