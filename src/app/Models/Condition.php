<?php
// Itemsテーブルと1対多

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Condition extends Model
{
    use HasFactory;

    // Conditionsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'condition'
    ];

    // Itemsテーブルとのリレーション定義
    public function items()
    {
        return $this->hasMany(Item::class);
    }

}

