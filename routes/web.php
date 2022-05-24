<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


// トップページ
Route::get('/', [BlogController::class, 'index'])->name('home');

// ユーザー用
Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::get('/show/{postId}', [BlogController::class, 'show'])->name('show');
    Route::get('/list', [BlogController::class, 'list'])->name('list');
    Route::get('/category/{categoryId}', [BlogController::class, 'category'])->name('category');
});

// 管理画面
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'verified', 'can:isIshii']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/store', [AdminController::class, 'store'])->name('store');
    Route::get('/edit/{postId}', [AdminController::class, 'edit'])->name('edit');
    Route::post('/update/{postId}', [AdminController::class, 'update'])->name('update');
    Route::post('/destroy/{postId}', [AdminController::class, 'destroy'])->name('destroy');
});