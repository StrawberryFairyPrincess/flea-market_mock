<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Item;


class DestinationTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_destination()
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

        // 住所変更ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/purchase/address/' . $item['id'] );
        $response->assertViewIs('.address');
        $response->assertStatus(200);

        // 送信項目
        $faker = Factory::create();
        $destination = [
            'name' => Auth::user()['name'],
            'post_code' => $faker->numberBetween(100, 999) . '-' . $faker->numberBetween(1000, 9999),
            'address' => $faker->address(),
            'building' => $faker->secondaryAddress(),

        ];

        // 入力内容送信
        $response = $this->post( '/purchase/address/' . $item['id'], $destination );
        $response->assertRedirect( '/purchase/' . $item['id'] );

        // セッションに値が渡せてるか
        // $this->assertEquals( $destination['post_code'], session('post_code') );
        // $this->assertEquals( $destination['address'], session('address') );
        // $this->assertEquals( $destination['building'], session('building') );
        $response->assertSessionHas( 'post_code', $destination['post_code'] );
        $response->assertSessionHas( 'address', $destination['address'] );
        $response->assertSessionHas( 'building', $destination['building'] );

        // バリデーションエラーなし
        $response->assertValid( ['post_code', 'address'] );

        // 購入ページへのアクセス
        $response = $this->get( '/purchase/' . $item['id'] );
        $response->assertViewIs('purchase');
        $response->assertStatus(200);

        // 登録した住所が反映されているか
        $response->assertSee( $destination['post_code'] );
        $response->assertSee( $destination['address'] );
        $response->assertSee( $destination['building'] );

        // 送信項目
        $purchase = [
            'post_code' => $destination['post_code'],
            'address' => $destination['address'],
            'building' => $destination['building'],
            'payment' => 'convenience'
        ];

        // 入力内容送信
        $response = $this->post( '/purchase/' . $item['id'], $purchase );
        $response->assertViewIs('.thanks');
        $response->assertStatus(200);

        // バリデーションエラーなし
        $response->assertValid(['address', 'payment']);

        // データベースに住所も登録されているか
        $this->assertDatabaseHas('purchases', [
            'user_id' => Auth::id(),
            'item_id' => $item['id'],
            'post_code' => $purchase['post_code'],
            'address' => $purchase['address'],
            'building' => $purchase['building'],
            'payment' => $purchase['payment']
        ]);
    }
}
