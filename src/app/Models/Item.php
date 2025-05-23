<?php
// Usersテーブルと多対1
// Conditionsテーブルと多対1
// Commentsテーブルと1対多
// Salesテーブルと1対1
// Purchasesテーブルと1対1
// Categoriesテーブルと1対多
// Likesテーブルと1対多

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'stock'
    ];

    // Usersテーブルとのリレーション定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Conditionsテーブルとのリレーション定義
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function category()
    {
        return $this->hasMany(category::class);
    }

    // キーワード検索
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            // 部分一致
            $query->where('name', 'like', '%' . $keyword . '%');
                // ->orWhere('brand', 'like', '%' . $keyword . '%')
                // ->orWhere('describe', 'like', '%' . $keyword . '%');
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

