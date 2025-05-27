<?php
// Destinationsテーブルと1対1
// Salesテーブルと1対多
// Purchasesテーブルと1対多
// Itemsテーブルと1対多
// Commentsテーブルと1対多
// Likesテーブルと1対多

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Destination;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Destinationsテーブルとのリレーション定義
    public function destination()
    {
        return $this->hasOne(Destination::class);
    }

    // Itemsテーブルとのリレーション定義
    // Likesテーブル経由
    public function likeItems()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }

    // Commentsテーブルとのリレーション定義
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
