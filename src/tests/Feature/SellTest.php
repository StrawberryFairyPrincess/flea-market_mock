<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;


class SellTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_sell()
    {
        // ユーザを作る
        $users = User::factory(1)->create();

        // ログイン状態にする(id=1の人)
        $user = User::where( 'id', 1 )->first();
        $this->actingAs( $user );

        // ユーザがログインできたか
        $this->assertTrue( Auth::check() );

        // ユーザがメール認証できているか
        $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // 商品出品画面へのアクセス
        $response = $this->get( '/sell' );
        $response->assertViewIs('sell');
        $response->assertStatus(200);

        // 商品の状態とカテゴリーを定義
        $this->seed(ConditionsTableSeeder::class); //4
        $this->seed(CategoriesTableSeeder::class); //14

        // アップロードされた画像をシミュレート
        $imagePath = __DIR__ . '/image/test.png'; // テスト用画像のパス(/var/www/tests/Feature/image/test.png)
        $file = new UploadedFile(
            $imagePath,
            'test.png',
            'image/png',
            null,
            true // test mode
        );

        // 登録する商品
        $faker = Factory::create();
        $number = $faker->numberBetween(1, 14); // category_idの生成数
        $item = [
            'user_id' => Auth::id(),
            'condition_id' => $faker->numberBetween(1, 4),
            'category_id' => $faker->randomElements( range(1, 14), $number ),
            'name' => $faker->word(),
            'brand' => $faker->word(),
            'price' => $faker->numberBetween(1000, 50000),
            'describe' => $faker->sentence(),
            'img_url' => $file
        ];

        // 登録内容送信
        $response = $this->post( '/sell', $item );
        $response->assertRedirect( '/mypage?tab=sell' );
        $response->assertStatus(302);

        // dd(session('errors'));

        // バリデーションエラーなし
        $response->assertValid([ 'name', 'brand', 'describe', 'price', 'category_id', 'condition_id', 'img_url' ]);

        // データベースに登録されたか
        $this->assertDatabaseHas('items', [
            'user_id' => $item['user_id'],
            'condition_id' => $item['condition_id'],
            'name' => $item['name'],
            'brand' => $item['brand'],
            'price' => $item['price'],
            'describe' => $item['describe'],
            'img_url' => 'item_img/' . $file->hashName()
        ]);
        foreach( $item['category_id'] as $category ){
            $this->assertDatabaseHas('category_item', [
                'item_id' => 1,
                'category_id' => $category
            ]);
        }

        // 画像が保存されたことを確認
        Storage::disk('public')->assertExists( 'item_img/' . $file->hashName() );
    }
}
