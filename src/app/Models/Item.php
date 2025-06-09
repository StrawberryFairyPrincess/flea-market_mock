<?php
// Usersテーブルと多対1
// Conditionsテーブルと多対1
// Commentsテーブルと1対多
// Salesテーブルと1対1
// Purchasesテーブルと1対1
// Categoriesテーブルと多対多
// Likesテーブルと1対多

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;


class Item extends Model
{
    use HasFactory;

    // Itemsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'condition_id',

        'name',
        'brand',
        'price',
        'describe',
        'img_url',
    ];

    // // Usersテーブルとのリレーション定義
    // public function user()
    // {
    //     return $this->belongsToMany(User::class, 'likes');
    // }

    // Conditionsテーブルとのリレーション定義
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // Categoriesテーブルとのリレーション定義
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    // Likesテーブルとのリレーション定義(いいね機能)
    public function likes(){
        return $this->hasMany(Like::class, 'item_id');
    }

    // Commentsテーブルとのリレーション定義
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Purchasesテーブルとのリレーション定義
    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'item_id');
    }

    // // キーワード検索
    // public function scopeKeywordSearch($query, $keyword)
    // {
    //     if (!empty($keyword)) {
    //         // 部分一致
    //         $query->where('name', 'like', '%' . $keyword . '%');
    //             // ->orWhere('brand', 'like', '%' . $keyword . '%')
    //             // ->orWhere('describe', 'like', '%' . $keyword . '%');
    //     }
    // }

    /**
     * 商品にLIKEが付いているかの判定
    * @return bool
    * true:Likeがついてる false:Likeがついてない
    */
    public function is_liked_by_auth_user()
    {
        if(\Auth::check()){
            $id = \Auth::user()->id;
        }
        else{
            $id = 0;
        }

        $likers = array();
        foreach($this->likes as $like) {
            array_push($likers, $like->user_id);
        }

        if (in_array($id, $likers)) {
            return true;
        }
        else {
            return false;
        }
    }

    // // キーワードがカテゴリーに一致するとき
    // public function scopeCategorySearch($query, $keyword)
    // {
    //     if (!empty($keyword)) {
    //         if($keyword == 'ファッション'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 1);
    //                 });
    //             });
    //         }
    //         else if($keyword == '家電'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 2);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'インテリア'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 3);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'レディース'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 4);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'メンズ'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 5);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'コスメ'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 6);
    //                 });
    //             });
    //         }
    //         else if($keyword == '本'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 7);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'ゲーム'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 8);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'スポーツ'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 9);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'キッチン'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 10);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'ハンドメイド'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 11);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'アクセサリー'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 12);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'おもちゃ'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 13);
    //                 });
    //             });
    //         }
    //         else if($keyword == 'ベビー・キッズ'){
    //             $items->where(function ($q) use ($request) {
    //                 $q->withWhereHas('category', function ($subquery1) {
    //                     $subquery1->where('category', '=', 14);
    //                 });
    //             });
    //         }
    //     }
    // }


}

