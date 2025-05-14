<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // contactsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'condition_id',

        'name',
        'brand',
        'price',
        'like',
        'describe',
        'img_url',
        'stock'
    ];

    // usersテーブルとのリレーション定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // conditionsテーブルとのリレーション定義
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // commentsテーブルとのリレーション定義
    // public function comment()
    // {
    //     return $this->hasMany(Comment::class);
    // }

}

