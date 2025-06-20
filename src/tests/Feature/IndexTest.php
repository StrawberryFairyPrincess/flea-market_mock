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
use Faker\Factory;
use Mail;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


class IndexTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    // 全データ表示
    public function test_all()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 商品一覧画面へのアクセス
        $response = $this->get('/');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // 登録された全商品が表示されているか
        $items = Item::all();
        foreach( $items as $item ){
            $response -> assertSee( $item['name'] );
        }
    }

    // 購入済み商品にSOLDの表記
    public function test_sold()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 購入データを作る
        $faker = Factory::create();
        for( $i = 0; $i < 3; $i++ ){
            $purchase = [
                'user_id' => $faker->numberBetween(1, 5),
                'item_id' => $faker->unique()->numberBetween(1, 10),
                'post_code' => $faker->postcode(),
                'address' => $faker->streetAddress(),
                'building' => $faker->secondaryAddress(),
                'payment' => $faker->randomElement(['convenience', 'credit'])
            ];
            Purchase::create($purchase);
        }

        // 商品一覧画面へのアクセス
        $response = $this->get('/');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // 商品一覧画面を文字列として取得
        $items = Item::all();
        $keyword = NULL;
        $contents = (string) $this->view('index', compact('items', 'keyword'));

        // SOLDの出現回数
        $count = substr_count( $contents, 'SOLD' );

        // SOLDの表示回数と購入済み商品数が等しいか
        $this->assertEquals( $count, Purchase::count() );
    }

    // 出品した商品を除外して表示
    public function test_except()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        // ログインしてない
        $this->assertFalse(Auth::check());

        // ログイン状態にする(id=1の人)
        $user = User::first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // 商品一覧画面へのアクセス
        $response = $this->get('/');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // 出品した商品が表示されていないか
        $items = Item::all();
        foreach( $items as $item ){
            // 出品した商品
            if( $item['user_id'] == Auth::user()->id ){
                $response->assertDontSee( $item['name'] );
            }
            // 他の人の商品
            else{
                $response->assertSee( $item['name'] );
            }
        }
    }
}
