<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoryItemSeeder;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


class MylistTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_like_all()
    {
        // // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        // 商品一覧画面へのアクセス
        $response = $this->get('/?tab=mylist');
        $response->assertViewIs('index');
        $response->assertStatus(200);

// // これだけでエラーになる
// $response = $this->get('/');
// $response->assertStatus(200);

    }

    // public function test_sold()
    // {

    // }

    // public function test_mine()
    // {

    // }

    // public function test_unauth()
    // {

    // }
}
