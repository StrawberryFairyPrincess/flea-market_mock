<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Destination;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Comment;


class UserController extends Controller
{
    // プロフィール編集画面(設定画面)
    public function profile()
    {
        // ログイン中のユーザー情報を取得
        $users = User::where('id', \Auth::user()->id)->get();
        foreach( $users as $user ){
            if( $user['id'] == \Auth::user()->id ){
                break;
            }
        }

        // ログイン中のユーザーのプロフィール情報（住所、画像）を取得
        if( DB::table('destinations')->where('id', \Auth::user()->id)->exists() ){
            $destinations = Destination::where('id', \Auth::user()->id)->get();
            foreach( $destinations as $destination ){
                if( $destination['id'] == \Auth::user()->id ){
                    break;
                }
            }
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
    public function update(Request $request)
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
        $user = User::where('id', \Auth::user()->id)->first();
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






}
