<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorization extends Model
{
    use HasFactory;

    // Categorizationsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'item_id',
        'category_id'
    ];

    // itemsテーブルとのリレーション定義
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Categorizationファクトリのルール
    public static $rules = array(
        'item_id' => 'required|min:1|max:10',
        'category_id' => 'required|min:1|max:14'
    );
}
