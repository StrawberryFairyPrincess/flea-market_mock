<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    // contactsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'user_id',

        'post_code',
        'address',
        'building'
    ];

    // usersテーブルとのリレーション定義
    public function user()
    {
        return $this->hasOne(User::class);
    }

}

