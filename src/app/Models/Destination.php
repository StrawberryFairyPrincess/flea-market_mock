<?php
// Usersテーブルと1対1

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    // Destinationsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',

        'post_code',
        'address',
        'building',
        'img_pass'
    ];

    // usersテーブルとのリレーション定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

