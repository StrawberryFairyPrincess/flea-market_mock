<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DestinationRequest;
use App\Http\Requests\PurchaseRequest;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Categorization;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Comment;


class UserController extends Controller
{

    // ヘッダーのリンク表示（マイページ(プロフィール画面)）
    public function mypage()
    {
        return view('mypage');
    }
    // ヘッダーのリンク表示（出品）
    public function sell()
    {
        return view('sell');
    }

    // プロフィール編集画面(設定画面)
    public function profile()
    {
        // ログイン中のユーザー情報を取得
        $user = \Auth::user();

        // ログイン中のユーザーのプロフィール情報（住所、画像）を取得
        if( DB::table('destinations')->where('id', \Auth::user()->id)->exists() ){
            $destination = Destination::where('id', \Auth::user()->id)->first();
        }
        // 初回ログイン時はレコードがないためとりあえずNULLを入れて空欄を表示
        else{
            $destination = [
                // 'id' => \Auth::user()->id,
                // 'user_id' => \Auth::user()->id,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'img_pass' => null
            ];
        }

        return view('profile', compact('user', 'destination'));
    }

    // プロフィール編集画面(設定画面)の更新
    public function update(DestinationRequest $request)
    {
        // 値を取得したいキー
        $form = $request->only([
            'img_pass',
            'name',
            'post_code',
            'address',
            'building'
        ]);

        // ユーザー名を更新
        $user = \Auth::user();
        $user['name'] = $form['name'];
        $user->save();

        // Destinationsテーブルに自分のidのレコードがあったら更新
        if( DB::table('destinations')->where('id', \Auth::user()->id)->exists() ){
            // レコードを検索
            $destination = Destination::where('id', \Auth::user()->id)->first();
        }
        // Destinationsテーブルに自分のidのレコードがなかったら作成（アカウント作成時の初回ログインを想定）
        else{
            // レコードを作成
            $destination = new Destination;
            $destination['id'] = \Auth::user()->id;
            $destination['user_id'] = \Auth::user()->id;
        }

        // フォームのデータを代入
        $destination['post_code'] = $form['post_code'];
        $destination['address'] = $form['address'];
        $destination['building'] = $form['building'];

        $image = $request->file('img_pass');
        //画像が送信されてきていたら保存処理
        if($image){
            $image_pass = Storage::disk('public')->put('profile_img', $image, 'public'); //画像の保存処理
            $destination['img_pass'] = $image_pass;
        }

        $destination->save();

        return redirect('/');
    }

    // 住所変更画面(商品購入画面から)
    public function address(Request $request)
    {
        // ログイン中のユーザーのプロフィール情報（住所、画像）を取得
        $destination = Destination::where('id', \Auth::user()->id)->first();

        $item_id = $request->item_id;

        return view('/address', compact('destination', 'item_id'));
    }

    // 商品の購入(商品購入画面から)
    public function purchase(PurchaseRequest $request)
    {
        // 購入履歴がない商品のみデータベースに追加
        if(! Purchase::where('item_id', $request->item_id)->exists() )
        {
            $purchase = new Purchase();
            $purchase->user_id = \Auth::user()->id;
            $purchase->item_id = $request->item_id;
            $purchase->save();
        }

        return redirect('/mypage?tab=buy');
    }
}
