<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoryItemSeeder;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;


class ItemTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_item_data()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class); //4
        $this->seed(CategoriesTableSeeder::class); //14
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        $faker = Factory::create();

        // イイネを作る
        for( $i = 0; $i < 30; $i++ ){
            $like = [
                'item_id' => $faker->numberBetween(1, 10),
                'user_id' => $faker->numberBetween(1, 5),
            ];
            Like::create($like);
        }

        // コメントを作る
        for( $i = 1; $i <= 10; $i++ ){
            $count = $faker->numberBetween(1, 5);
            for( $j = 0; $j < $count; $j++ ){
                $comment = [
                    'user_id' => $faker->numberBetween(1, 5),
                    'item_id' => $i,
                    'comment' => $faker->sentence(),
                ];
                Comment::create($comment);
            }
        }

        $items = Item::all();
        foreach( $items as $item ){

            // 個別商品ページへのアクセス
            $response = $this->get( '/item/' . $item['id'] );
            $response->assertViewIs('item');
            $response->assertStatus(200);

            // 商品画像
            $response->assertSee( $item['img_url'] );

            // 商品名
            $response->assertSeeText( $item['name'] );

            // ブランド名
            $response->assertSeeText( $item['brand'] );

            // 価格
            $price = number_format( $item['price'], 0 );
            $response->assertSeeText( $price );

            // 商品説明
            $response->assertSeeText( $item['describe'] );

            // カテゴリ
            foreach( $item['category'] as $category ){
                $response->assertSeeText( $category['category'] );
            }

            // 商品の状態
            $response->assertSeeText( $item['condition']['condition'] );

            // イイネ数
            $response->assertSeeText( $item->likes->count() );

            // コメント数
            $response->assertSeeText( 'コメント( ' . $item->comments->count() . ' )' );

            foreach( $item->comments as $comment ){
                // コメントしたユーザ画像
                if(! empty($comment->user->destination->img_pass) ){
                    $response->assertSee( $comment->user->destination->img_pass );
                }

                // コメントしたユーザ名
                $response->assertSeeText( $comment['user']['name'] );

                // コメント内容
                $response->assertSeeText( $comment['comment'] );
            }
        }
    }
}