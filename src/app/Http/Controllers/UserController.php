<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DestinationRequest;
use App\Http\Requests\PurchaseRequest;

use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Comment;


class UserController extends Controller
{
    // マイページ(プロフィール画面)の表示(ヘッダーのリンクから)
    public function mypage(Request $request)
    {
        $user = \Auth::user();

        // mypage?tab=buyのとき
        if( $request->tab == 'buy' ){
            // 購入した商品に絞る
            $items = $user->purchaseItems;
        }
        // mypage?tab=sellか/mypageのとき
        else{
            // 販売した商品に絞る
                $items = Item::where('user_id', \Auth::user()->id)->get();
        }

        return view('mypage', compact('user', 'items'));
    }

    // 商品出品画面の表示(ヘッダーのリンクから)
    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    // プロフィール編集画面(設定画面)の表示
    public function profile()
    {
        // ログイン中のユーザー情報を取得
        $user = \Auth::user();

        // ログイン中ユーザのプロフィール情報（住所、画像）を取得
        if( Destination::where('id', \Auth::user()->id)->exists() ){
            $destination = Destination::where('id', \Auth::user()->id)->first();
        }
        // 初回ログイン時はレコードがないためとりあえずNULLを入れて空欄を表示
        else{
            $destination = [
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
        if( Destination::where('id', \Auth::user()->id)->exists() ){
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

    // 送付先住所変更画面の表示(商品購入画面から)
    public function address(Request $request)
    {
        // ログイン中ユーザのプロフィール情報を取得
        if( Destination::where('id', \Auth::user()->id)->exists() ){
            $destination = Destination::where('id', \Auth::user()->id)->first();
        }
        // プロフィールを登録してなかったら空欄を表示
        else{
            $destination = [
                'post_code' => null,
                'address' => null,
                'building' => null,
            ];
        }

        $item_id = $request->item_id;

        return view('/address', compact('destination', 'item_id'));
    }

    // 変更した送付先を商品購入画面に渡す(送付先住所変更画面から)、プロフィールの変更はしない
    public function destination(DestinationRequest $request)
    {
        // 値を取得したいキー
        $form = $request->only([
            'item_id',
            'post_code',
            'address',
            'building'
        ]);

        return redirect('/purchase/' . $request->item_id)
            ->with([ //セッションで値を渡す
                'post_code' => $form['post_code'],
                'address' => $form['address'],
                'building' => $form['building']
            ]);
    }

    // 商品の購入(商品購入画面から)
    public function purchase(PurchaseRequest $request)
    {
        // 購入履歴がない商品（在庫あり）
        if( !Purchase::where('item_id', $request->item_id)->exists() ){

            $purchase = [
                'user_id' => \Auth::user()->id,
                'item_id' => $request->item_id,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
                'payment' => $request->payment
            ];

            // カード支払いのとき
            if( $request->payment == 'credit' ){

                $item = Item::where('id', $purchase['item_id'])->first();
                $price = $item->price;

                // Stripe決済画面の表示
                return view('payment', compact( 'price', 'purchase' ));
            }
            // コンビニ払いのとき
            else if( $request->payment == 'convenience' ){
                // データベースに追加
                Purchase::create($purchase);

                return view('/thanks');
            }
        }
        // 売り切れのとき（バリデーション通ってるからないはずだけど一応）
        else{
            return redirect('/mypage?tab=buy');
        }
    }
}
