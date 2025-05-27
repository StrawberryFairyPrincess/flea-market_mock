<?php
// Usersテーブルと多対1
// Itemsテーブルと多対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;


class Like extends Model
{
    use HasFactory;

    // Likesテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'item_id'
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
