<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisteredUserController;


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

    // 商品出品画面
    Route::get('/sell', [ItemController::class, 'sell']);

    Route::prefix('/mypage')->group(function () {

        // マイページ(プロフィール画面)
        Route::get('', [ItemController::class, 'mypage']);

        // プロフィール編集画面(設定画面)
        // Route::get('/profile', [ItemController::class, 'profile']);

    });

});

// 商品一覧画面(トップ画面)
Route::get('/', [ItemController::class, 'index']);

// 会員登録画面
// 登録後の遷移先を上書き
// Route::post('/register',[RegisteredUserController::class,'store']);

// ログイン画面

// 商品詳細画面


