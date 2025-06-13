<?php
// Itemsテーブルと多対多

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory;

    // Categoriesテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'category'
    ];

    // Itemsテーブルとのリレーション定義
    public function item()
    {
        return $this->belongsToMany(Item::class);
    }

}


// 1: ファッション
// 2: 家電
// 3: インテリア
// 4: レディース
// 5: メンズ
// 6: コスメ
// 7: 本
// 8: ゲーム
// 9: スポーツ
// 10: キッチン
// 11: ハンドメイド
// 12: アクセサリー
// 13: おもちゃ
// 14: ベビー・キッズ