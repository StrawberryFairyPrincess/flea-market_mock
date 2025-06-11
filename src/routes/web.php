<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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
// Route::middleware('auth')->group(function () {
Route::middleware(['auth', 'verified'])->group(function () {

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

    Route::prefix('/sell')->group(function () {

        // 商品出品画面の表示（ヘッダーのリンク）
        Route::get('', [UserController::class, 'sell']);

        // 商品の出品
        Route::post('', [ItemController::class, 'sell']);

    });

    Route::prefix('/purchase/{item_id}')->group(function () {

        // 商品購入画面の表示
        Route::get('', [ItemController::class, 'purchase']);

        // 商品の購入
        Route::post('', [UserController::class, 'purchase']);

    });

    Route::prefix('/purchase/address/{item_id}')->group(function () {

        // 送付先住所変更画面の表示
        Route::get('', [UserController::class, 'address']);

        // 送付先住所を更新
        Route::post('', [UserController::class, 'destination']);

    });

    // コメント機能(商品詳細画面)
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);

    // Stripeのクレジットカード決済
    Route::post('/payment/{item_id}', [ItemController::class, 'payment']);

});

// 商品一覧画面(トップ画面)の表示
Route::get('/', [ItemController::class, 'index']);

Route::prefix('/item')->group(function () {

    // 商品詳細画面の表示
    Route::get('/{item_id}', [ItemController::class, 'item']);

    // いいね機能(商品詳細画面)
    Route::get('/like/{item_id}', [ItemController::class, 'like'])->name('item.like');
    // いいねを外す
    Route::get('/dislike/{item_id}', [ItemController::class, 'dislike'])->name('item.dislike');

});

// メール認証
Route::prefix('/email')->group(function () {

    Route::prefix('/verify')->group(function () {

        // メール確認の通知
        Route::get('', function () {
            return view('auth.verify-email');
        })->middleware('auth')->name('verification.notice');

        // メール確認のハンドラ
        Route::get('/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            // メールアドレス検証後のリダイレクト先
            return redirect('/mypage/profile');
        })->middleware(['auth', 'signed'])->name('verification.verify');

    });

    // メール確認の再送信
    Route::post('/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを送信しました');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

});
