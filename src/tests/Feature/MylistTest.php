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
use App\Models\Purchase;
use App\Models\Like;


class MylistTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    // いいねした商品の全データ表示
    public function test_like_all()
    {
        // ユーザを作る
        for( $i = 1; $i <= 6; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ログインしてない
        $this->assertFalse(Auth::check());

        // ログイン状態にする(id=6の人)
        $user = User::where('id', 6)->first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // イイネを作る
        $faker = Factory::create();
        for( $i = 1; $i <= 6; $i++ ){
            for( $j = 0; $j < 5; $j++ ){
                if($i == 6){
                    $like = [
                        'item_id' => $faker->unique()->numberBetween(1, 10),
                        'user_id' => Auth::id(),
                    ];
                }
                else{
                    $like = [
                        'item_id' => $faker->numberBetween(1, 10),
                        'user_id' => $i,
                    ];
                }
                Like::create($like);
            }
        }

        // マイリストへのアクセス
        $response = $this->get('/?tab=mylist');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // いいねした商品が表示されているか
        $items = Auth::user()->likeItems;
        $ids = [];
        foreach( $items as $item ){
            $response->assertSeeText( $item['name'] );
            $ids[] = $item['id'];
        }

        // いいねしいてない商品が表示されていないか
        $items = Item::whereNotIn('id', $ids)->get();
        foreach( $items as $item ){
            $response->assertDontSeeText( $item['name'] );
        }
    }

    // いいねの中で購入済み商品にSOLDの表記
    public function test_sold()
    {
        // ユーザを作る
        for( $i = 1; $i <= 6; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 購入データを作る
        $faker = Factory::create();
        for( $i = 0; $i < 5; $i++ ){
            $purchase = [
                'user_id' => $faker->numberBetween(1, 6),
                'item_id' => $faker->unique()->numberBetween(1, 10),
                'post_code' => $faker->postcode(),
                'address' => $faker->streetAddress(),
                'building' => $faker->secondaryAddress(),
                'payment' => $faker->randomElement(['convenience', 'credit'])
            ];
            Purchase::create($purchase);
        }

        // ログインしてない
        $this->assertFalse(Auth::check());

        // ログイン状態にする(id=6の人)
        $user = User::where('id', 6)->first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // イイネを作る
        $faker = Factory::create();
        for( $i = 1; $i <= 6; $i++ ){
            for( $j = 0; $j < 5; $j++ ){
                if($i == 6){
                    $like = [
                        'item_id' => $faker->unique()->numberBetween(1, 10),
                        'user_id' => Auth::id(),
                    ];
                }
                else{
                    $like = [
                        'item_id' => $faker->numberBetween(1, 10),
                        'user_id' => $i,
                    ];
                }
                Like::create($like);
            }
        }

        // マイリストへのアクセス
        $response = $this->get('/?tab=mylist');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // マイリストを文字列として取得
        $items = Auth::user()->likeItems;
        $keyword = NULL;
        $contents = (string) $this->view('index', compact('items', 'keyword'));

        // SOLDの出現回数
        $count = substr_count( $contents, 'SOLD' );

        // いいねしている中で購入済みの数
        $purchases = Purchase::all();
        $i = 0;
        foreach( $purchases as $purchase ){
            foreach( $items as $item ){
                if( $purchase['item_id'] == $item['id'] ){
                    $i++;
                }
            }
        }

        // SOLDの表示回数と購入済み商品数が等しいか
        $this->assertEquals( $count, $i );
    }

    // いいねの中から出品した商品を除外して表示
    public function test_mine()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ログインしてない
        $this->assertFalse(Auth::check());

        // ログイン状態にする(id=1の人:商品ID=1,6を出品してる)
        $user = User::where('id', 1)->first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // イイネを作る
        $faker = Factory::create();
        for( $i = 1; $i <= 5; $i++ ){
            for( $j = 0; $j < 5; $j++ ){
                if($i == 1){
                    $like = [
                        'item_id' => $faker->unique()->numberBetween(1, 10),
                        'user_id' => Auth::id(),
                    ];
                }
                else{
                    $like = [
                        'item_id' => $faker->numberBetween(1, 10),
                        'user_id' => $i,
                    ];
                }
                Like::create($like);
            }
        }

        // マイリストへのアクセス
        $response = $this->get('/?tab=mylist');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // 出品した商品が表示されていないか
        $items = Auth::user()->likeItems;
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

    // ログインかつメール未認証状態
    public function test_unauth()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ログインしてない
        $this->assertFalse(Auth::check());

        // ログインさせるけどメール認証しないユーザ
        $requestParams = [
            'name' => 'コーチテック',
            'email' => 'test@coachtech',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        // 入力項目送信
        $response = $this->post('/register', $requestParams);
        $response->assertStatus(302);

        // バリデーションエラーなし
        $response->assertValid(['name', 'email', 'password', 'password_confirmation']);

        // データベースに登録されているか
        $this->assertDatabaseHas('users', [
            'name' => 'コーチテック',
            'email' => 'test@coachtech',
        ]);

        // ユーザがログインしたか
        $this->assertTrue(Auth::check());

        // メール認証していないか
        $this->assertFalse(Auth::user()->hasVerifiedEmail());

        // イイネを作る
        $faker = Factory::create();
        for( $i = 0; $i < 5; $i++ ){
            $like = [
                'item_id' => $faker->numberBetween(1, 10),
                'user_id' => Auth::id(),
            ];
            Like::create($like);
        }

        // マイリストへのアクセス
        $response = $this->get('/?tab=mylist');
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // 商品が非表示か
        $items = Item::all();
        foreach( $items as $item ){
            $response->assertDontSeeText( $item['name'] );
        }
    }
}
