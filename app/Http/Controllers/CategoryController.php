<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::oldest('order')->get();
		return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryRequest $request)
    {
        $params = $request->all();
        $log = ['name' => 'store'];
		
		try {
			$category = Category::create($params);
            $log['data'] = $category;
            Log::channel('category')->info($log);
			return redirect()->route('admin.category.edit', ['categoryId' => $category->id])->withSuccess('データを登録しました。');

		} catch (Exception $e) {

            $log[] = [
                'data' => $params,
                'exception' => $e,
            ];
            Log::channel('category')->error($log);
			return redirect()->route('admin.category.create')->withError('データの登録に失敗しました。');
		}
    }

    public function edit(int $categoryId)
    {
        $category = Category::find($categoryId);

        // 存在しないカテゴリー
		if (!$category)
		{
			return view('errors.404');
		}

        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request, int $categoryId)
    {
        $params = $request->except('_token');
        $log = [
            'name' => 'update',
            'data' => $params,
        ];

		try {
			Category::where('id', $categoryId)->update($params);
            Log::channel('category')->info($log);
			return redirect()->route('admin.category.edit', ['categoryId' => $categoryId])->withsuccess('データを更新しました。');

		} catch (Exception $e) {

            $log['exception'] = $e;
            Log::channel('category')->error($log);
			return redirect()->route('admin.category.edit', ['categoryId' => $categoryId])->withError('データの更新に失敗しました。');
		}
    }

    public function destroy(int $categoryId)
    {
        $log = [
            'name' => 'destroy',
            'data' => $categoryId,
        ];

        try {
			Category::destroy($categoryId);
            Log::channel('category')->info($log);
			return redirect()->route('admin.category.index')->withsuccess('データを削除しました。');

		} catch (Exception $e) {

            $log['exception'] = $e;
            Log::channel('category')->error($log);
			return redirect()->route('admin.category.edit', ['categoryId' => $categoryId])->withError('データの削除に失敗しました。');
		}
    }
}
