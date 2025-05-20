<?php
// Itemsテーブルと1対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // contactsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'item_id',

        'fashion',
        'appliance',
        'interior',
        'lady',
        'man',
        'cosmetic',
        'book',
        'game',
        'sport',
        'kitchen',
        'handmade',
        'accessory',
        'toy',
        'child'
    ];

    // itemsテーブルとのリレーション定義
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Categoryファクトリのルール
    public static $rules = array(
        'item_id' => 'required',

        'fashion' => 'required|min:0|max:1',
        'appliance' => 'required|min:0|max:1',
        'interior' => 'required|min:0|max:1',
        'lady' => 'required|min:0|max:1',
        'man' => 'required|min:0|max:1',
        'cosmetic' => 'required|min:0|max:1',
        'book' => 'required|min:0|max:1',
        'game' => 'required|min:0|max:1',
        'sport' => 'required|min:0|max:1',
        'kitchen' => 'required|min:0|max:1',
        'handmade' => 'required|min:0|max:1',
        'accessory' => 'required|min:0|max:1',
        'toy' => 'required|min:0|max:1',
        'child' => 'required|min:0|max:1'

    );

}

