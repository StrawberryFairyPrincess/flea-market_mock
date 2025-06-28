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
use App\Models\Destination;
use App\Models\Item;
use App\Models\Purchase;


class MypageTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_mypage()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // ログインさせるユーザ(id=1の人)の画像を登録
        $faker = Factory::create();
        $destination = [
            'id' => 1,
            'user_id' => 1,
            'post_code' => $faker->postcode(),
            'address' => $faker->streetAddress(),
            'building' => $faker->secondaryAddress(),
            'img_pass' => 'profile_img/profileimage_1.png'
        ];
        Destination::create( $destination );

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 購入データを作る
        $ids = [];
        for( $i = 0; $i < 5; $i++ ){
            $purchase = [
                'user_id' => 1,
                'item_id' => $faker->unique()->numberBetween(1, 10),
                'post_code' => $faker->postcode(),
                'address' => $faker->streetAddress(),
                'building' => $faker->secondaryAddress(),
                'payment' => $faker->randomElement(['convenience', 'credit'])
            ];
            Purchase::create( $purchase );
            $ids[] = $purchase['item_id'];
        }

        // 未ログイン
        $this->assertFalse( Auth::check() );

        // ログイン状態にする(id=1の人)
        $user = User::where( 'id', 1 )->first();
        $this->actingAs( $user );

        // ユーザがログインできたか
        $this->assertTrue( Auth::check() );

        // ユーザがメール認証できているか
        $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // プロフィール画面(マイページ)へのアクセス
        $response = $this->get( '/mypage' );
        $response->assertViewIs('mypage');
        $response->assertStatus(200);

        // プロフィール画像が表示されているか
        $response->assertSee( Auth::user()['destination']['img_pass'] );

        // ユーザ名が表示されているか
        $response->assertSeeText( Auth::user()->name );

        // プロフィール画面(マイページ)/出品した商品へのアクセス
        $response = $this->get( '/mypage?tab=sell' );
        $response->assertViewIs('mypage');
        $response->assertStatus(200);

        // 出品した商品のみ表示されているか
        $items = Item::all();
        foreach( $items as $item ){
            if( $item['user_id'] == Auth::id() ){
                $response->assertSeeText( $item['name'] );
            }
            else{
                $response->assertDontSeeText( $item['name'] );
            }
        }

        // プロフィール画面(マイページ)/購入した商品へのアクセス
        $response = $this->get( '/mypage?tab=buy' );
        $response->assertViewIs('mypage');
        $response->assertStatus(200);

        // 購入した商品のみ表示されているか
        foreach( $items as $item ){
            if( Purchase::where('item_id', $item['id'])->exists() ){
                $response->assertSeeText( $item['name'] );
            }
            else{
                $response->assertDontSeeText( $item['name'] );
            }
        }
    }
}
