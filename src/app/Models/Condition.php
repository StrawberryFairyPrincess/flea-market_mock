<?php
// Itemsテーブルと1対多

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    // Conditionsテーブルのカラムのうち操作可能にするもの
    protected $fillable = [
        'condition'
    ];


}

