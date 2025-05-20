<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
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

    // 商品購入画面

    // 送付先住所変更画面

    // ヘッダーのリンク表示（商品出品画面）
    Route::get('/sell', [AuthController::class, 'sell']);

    Route::prefix('/mypage')->group(function () {

        // ヘッダーのリンク表示（マイページ(プロフィール画面)）
        Route::get('', [AuthController::class, 'mypage']);

        Route::prefix('/profile')->group(function () {

            // プロフィール編集画面(設定画面)を表示
            Route::get('', [UserController::class, 'profile']);

            // プロフィール編集画面(設定画面)を更新
            Route::post('', [UserController::class, 'update']);

        });


    });

});

// 商品一覧画面(トップ画面)
Route::get('/', [ItemController::class, 'index']);

// 会員登録画面

// ログイン画面

// 商品詳細画面


