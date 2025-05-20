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


class AuthController extends Controller
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



}
