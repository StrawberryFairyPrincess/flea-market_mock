<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;


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


// ログイン済みの場合のみ表示
Route::middleware('auth')->group(function () {

    Route::prefix('/mypage')->group(function () {

        // ヘッダーのリンク表示（マイページ(プロフィール画面)）
        Route::get('', [UserController::class, 'mypage']);

        Route::prefix('/profile')->group(function () {

            // プロフィール編集画面(設定画面)を表示
            Route::get('', [UserController::class, 'profile']);

            // プロフィール編集画面(設定画面)を更新
            Route::post('', [UserController::class, 'update']);

        });

    });

    // ヘッダーのリンク表示（商品出品画面）
    Route::get('/sell', [UserController::class, 'sell']);

    // 商品購入画面の表示
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);

    // 送付先住所変更画面の表示

    // コメント機能
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);

});

// 商品一覧画面(トップ画面)の表示
Route::get('/', [ItemController::class, 'index']);

// 商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'item']);

// ヘッダーからの検索機能
Route::get('/search', [ItemController::class, 'search']);

// いいね機能
Route::get('/item/like/{item_id}', 'App\Http\Controllers\ItemController@like')->name('item.like');
Route::get('/item/dislike/{item_id}', 'App\Http\Controllers\ItemController@dislike')->name('item.dislike');

