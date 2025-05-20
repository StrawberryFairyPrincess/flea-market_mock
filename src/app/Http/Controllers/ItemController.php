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

    public function index()
    {

        // Itemsテーブルを全部取得
        $items = Item::all();

        // index.blade.phpを表示して、入力情報が入った変数$itemsを渡す
        return view('index', compact('items'));

    }



}
