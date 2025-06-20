<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Database\Eloquent\Collection;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Comment;


class ItemController extends Controller
{
    // 商品一覧画面表示、検索機能
    public function index(Request $request)
    {
        // /?tab=mylistだったらlikeで絞り込み
        if($request->tab == 'mylist'){
            // ログインしているとき
            if( \Auth::check() ){
                // キーワードが入力されていたとき
                if( !empty( $request->keyword ) && ( $request->keyword != NULL ) ){

                    // 部分一致検索
                    $items = Item::where('name', 'like', '%' . $request->keyword . '%')
                        ->get();

                    // イイネしたものだけ抽出
                    // 参照方法: $items[0]->likes[0]->user->id
                    $array = [];
                    foreach( $items as $item ){
                        foreach( $item->likes as $like ){
                            if( $like->user->id == \Auth::user()->id ){
                                $array[] = $item;
                            }
                        }
                    }

                    $items = $array;
                    $keyword = $request->keyword;
                }
                // キーワードがないとき
                else{
                    // イイネした商品だけにする
                    $items = \Auth::user()->likeItems;
                    $keyword = NULL;
                }
            }
            // ログインしていないときはボタンが押せないが万が一アクセスしたとき
            else{
                $items = Item::where('id', 0)->get();
                // $items[0] = [
                //     'id' => 0,
                //     'user_id' => 0,
                //     'condition_id' => 0,
                //     'name' => NULL,
                //     'brand' => NULL,
                //     'price' => NULL,
                //     'describe' => NULL,
                //     'img_url' => NULL,
                // ];
                $keyword = NULL;
            }
        }
        // /だったとき
        else{
            // キーワードが入力されていたとき
            if( !empty( $request->keyword ) && ( $request->keyword != NULL ) ){
                // 部分一致検索
                $items = Item::where('name', 'like', '%' . $request->keyword . '%')
                    ->get();
                $keyword = $request->keyword;
            }
            // キーワードがないとき
            else{
                // Itemsテーブルを全件取得
                $items = Item::all();
                $keyword = NULL;
            }
        }

        // index.blade.phpを表示して、入力情報が入った変数$items、$keywordを渡す
        return view('index', compact('items', 'keyword'));
    }

    // 商品詳細画面表示
    public function item(Request $request)
    {
        $item = Item::find($request->item_id);
        $categories = $item['category'];
        $condition = $item['condition']['condition'];
        $sold = Purchase::where('item_id', $request->item_id)->exists();

        return view('item', compact('item', 'categories', 'condition', 'sold'));
    }

    // いいね機能
    // only()の引数内のメソッドはログイン時のみ有効
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'dislike']);
    }
    // 引数のIDに紐づくリプライにLIKEする
    public function like($item_id)
    {
        Like::create([
            'item_id' => $item_id,
            'user_id' => \Auth::user()->id,
        ]);

        session()->flash('success', 'You Liked the item.');

        return redirect()->back();
    }
    // 引数のIDに紐づくリプライにDISLIKEする
    public function dislike($item_id)
    {
        $like = Like::where('item_id', $item_id)->where('user_id', \Auth::user()->id)->first();
        $like->delete();

        session()->flash('success', 'You Disliked the item.');

        return redirect()->back();
    }

    // コメント機能
    public function comment(CommentRequest $request)
    {
        $comment = [
            'user_id' => \Auth::user()->id,
            'item_id' => $request->item_id,
            'comment' => $request->comment,
        ];
        Comment::create($comment);

        return redirect()->back();
    }

    // 商品購入画面表示
    public function purchase(Request $request)
    {
        $item = Item::find($request->item_id);
        $user = \Auth::user();
        $sold = Purchase::where('item_id', $request->item_id)->exists();//true or false

        return view('purchase', compact('item', 'user', 'sold'));
    }

    // 商品を出品(商品出品画面から)
    public function sell(ExhibitionRequest $request)
    {
        // 新規Itemテーブルレコード
        $item = [
            'user_id' => \Auth::user()->id,
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'describe' => $request->describe,
            'img_url' => '',
        ];

        $image = $request->file('img_url');
        //画像が送信されてきていたら保存処理
        if($image){
            $image_pass = Storage::disk('public')->put('item_img', $image, 'public'); //画像の保存処理
            $item['img_url'] = $image_pass;
        }

        Item::create($item);

        $item = Item::latest('id')->first();

        // 新規Category_Itemテーブルレコード登録
        // syncは配列OK
        $item->category()->sync($request->category_id);

        return redirect('/mypage?tab=sell');
    }

    // Stripe決済実行とデータベースに追加
    public function payment(Request $request)
    {
        // 購入履歴がない商品のみデータベースに追加
        if(! Purchase::where('item_id', $request->item_id)->exists() )
        {
            $purchase = [
                'user_id' => \Auth::user()->id,
                'item_id' => $request->item_id,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
                'payment' => 'credit'
            ];
            Purchase::create($purchase);
        }

        // シークレットキーの設定
        \Stripe\Stripe::setApiKey(config('stripe.stripe_secret_key'));

        try {
            // 決済実行
            \Stripe\Charge::create([
                'source' => $request->stripeToken, // クレジットカードのトークン
                'amount' => $request->price, // 金額
                'currency' => 'jpy',
            ]);
        }
        // 決済に失敗したとき
        catch (Exception $e) {
            return redirect('/mypage?tab=buy');
        }
        return view('/thanks');
    }
}
