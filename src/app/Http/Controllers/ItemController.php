<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Comment;


class ItemController extends Controller
{

    public function index(Request $request)
    {
        // Itemsテーブルを全部取得
        $items = Item::all();
        // $items = Item::with('category')->get();

        $mylist = [
            // bladeで受け取るときの変数名をつける($paramとして受け取る)
            'param' => $request->tab
        ];



        // index.blade.phpを表示して、入力情報が入った変数$itemsを渡す
        return view('index', compact('items'));
    }

    public function item(Request $request)
    {
        return view('item');
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





}
