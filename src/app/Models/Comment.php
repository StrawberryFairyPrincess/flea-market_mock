<?php
// Usersテーブルと多対1
// Itemsテーブルと多対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Comment extends Model
{
    use HasFactory;

    // Commentsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'item_id',

        'comment'
    ];

    // Usersテーブルとのリレーション定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Itemsテーブルとのリレーション定義
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}

