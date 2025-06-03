<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
// use App\Http\Controllers\MailSendController;


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

    // 商品出品画面の表示（ヘッダーのリンク）
    Route::get('/sell', [UserController::class, 'sell']);
    // 商品の出品
    Route::post('/sell', [ItemController::class, 'sell']);

    // 商品購入画面の表示
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);
    // 商品の購入
    Route::post('/purchase/{item_id}', [UserController::class, 'purchase']);

    // 送付先住所変更画面の表示
    Route::get('/purchase/address/{item_id}', [UserController::class, 'address']);
    // 送付先住所を更新
    Route::post('/purchase/address/{item_id}', [UserController::class, 'destination']);

    // コメント機能(商品詳細画面)
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);

});

// 商品一覧画面(トップ画面)の表示
Route::get('/', [ItemController::class, 'index']);

// 商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'item']);

// 検索機能(ヘッダー)
Route::get('/search', [ItemController::class, 'search']);

// いいね機能(商品詳細画面)
Route::get('/item/like/{item_id}', [ItemController::class, 'like'])->name('item.like');
// いいねを外す
Route::get('/item/dislike/{item_id}', [ItemController::class, 'dislike'])->name('item.dislike');

// メール送信確認
// Route::get('/mail', [MailSendController::class, 'mail']);
// メール確認の通知
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// メール確認のハンドラ
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // メールアドレス検証後のリダイレクト先
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
// メール確認の再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
