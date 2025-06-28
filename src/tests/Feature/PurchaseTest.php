<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
// use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
// use Database\Seeders\CategoryItemSeeder;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


class PurchaseTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_purchase()
    {
        // ユーザを作る
        $users = User::factory(6)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 未ログイン
        $this->assertFalse( Auth::check() );

        // ログイン状態にする(id=6の人)
        $user = User::where( 'id', 6 )->first();
        $this->actingAs( $user );

        // ユーザがログインできたか
        $this->assertTrue( Auth::check() );

        // ユーザがメール認証できているか
        $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // 購入ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/purchase/' . $item['id'] );
        $response->assertViewIs('purchase');
        $response->assertStatus(200);

        // 送信項目
        $faker = Factory::create();
        $purchase = [
            'post_code' => $faker->postcode(),
            'address' => $faker->address(),
            'building' => $faker->secondaryAddress(),
            'payment' => 'convenience'
        ];

        // 入力内容送信
        $response = $this->post( '/purchase/' . $item['id'], $purchase );
        $response->assertViewIs('.thanks');

        // バリデーションエラーなし
        $response->assertValid(['address', 'payment']);

        // データベースに登録されているか
        $this->assertDatabaseHas('purchases', [
            'user_id' => Auth::id(),
            'item_id' => $item['id'],
            'post_code' => $purchase['post_code'],
            'address' => $purchase['address'],
            'building' => $purchase['building'],
            'payment' => $purchase['payment']
        ]);

        // 商品一覧画面を文字列として取得
        $items = Item::all();
        $keyword = NULL;
        $contents = (string) $this->view('index', compact('items', 'keyword'));

        // SOLDの表示回数と購入済み商品数が等しいか
        $count = substr_count( $contents, 'SOLD' );
        $this->assertEquals( $count, Purchase::count() ); //1

        // プロフィール/購入した商品にアクセス
        $response = $this->get( '/mypage?tab=buy' );
        $response->assertViewIs('mypage');
        $response->assertStatus(200);

        // 購入した商品が表示されているか
        $response->assertSeeText( $item['name'] );
    }
}
