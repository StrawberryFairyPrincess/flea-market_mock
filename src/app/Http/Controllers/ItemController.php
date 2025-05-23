<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Categorization;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Comment;


class ItemController extends Controller
{

    public function index(Request $request)
    {

        // $items = Item::with('like')->get();

        // $tab = [
        //     // bladeで受け取るときの変数名をつける($tabとして受け取る)
        //     'tab' => $request->tab
        // ];

        // /?tab=mylistだったらlikeで絞り込み検索
        if($request->tab == 'mylist'){
            


        }
        // /だったら全件取得
        else{
            // Itemsテーブルを全部取得
            $items = Item::all();
        }

        // index.blade.phpを表示して、入力情報が入った変数$itemsを渡す
        return view('index', compact('items'));
    }

    // 商品詳細ページ表示
    public function item(Request $request)
    {
        $item = Item::find($request->item_id);

        return view('item', compact('item'));
    }

    // 検索機能
    public function search(Request $request)
    {
        // キーワード
        if(!empty($request->keyword)) {
            // $items = Item::with('category')
            $items = Item::KeywordSearch($request->keyword)
                // ->CategorySearch($request->keyword)
                ->get();
        }

        return view('index', compact('items'));
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




}
