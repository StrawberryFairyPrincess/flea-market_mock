<?php
// Usersテーブルと多対1
// Itemsテーブルと多対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Commentsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'item_id',

        'comment'
    ];

    // usersテーブルとのリレーション定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // itemsテーブルとのリレーション定義
    public function item()
    {
        return $this->belongsTo(Item::class);
    }


}

