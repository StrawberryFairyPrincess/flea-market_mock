<?php
// Usersテーブルと多対1
// Itemsテーブルと1対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Purchase extends Model
{
    use HasFactory;

    // Purchasesテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',
        'item_id',
        'post_code',
        'address',
        'building',
        'payment'
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

