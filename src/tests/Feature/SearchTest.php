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
use App\Models\Like;


class SearchTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    // 商品名での部分一致検索
    public function test_partial_match()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ログイン状態にする(id=1の人)
        $user = User::where('id', 1)->first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // キーワド送信
        $keyword = 'ー'; // ノートPC(id=5)、ショルダーバッグ(id=7)、タンブラー(id=8)、コーヒーミル(id=9)
        $response = $this->get( '/?keyword=' . $keyword );
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // キーワードが部分一致するものだけ表示されているか
        $items = Item::all();
        foreach( $items as $item ){
            if( $item['id']==5 || $item['id']==7 || $item['id']==8 || $item['id']==9 ){
                $response->assertSeeText( $item['name'] );
                $this->assertStringContainsString( $keyword, $item['name'] );
            }
            else{
                $response->assertDontSeeText( $item['name'] );
            }
        }
    }

    // マイリスト遷移時のキーワード保持
    public function test_retain_keyword()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ログイン状態にする(id=1の人)
        $user = User::where('id', 1)->first();
        $this->actingAs($user);

        // ユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ユーザがメール認証しているか
        $this->assertTrue(Auth::user()->hasVerifiedEmail());

        // イイネを作る
        $faker = Factory::create();
        $ids = [];
        for( $i = 0; $i < 5; $i++ ){
            $like = [
                'item_id' => $faker->unique()->numberBetween(1, 10),
                'user_id' => Auth::id(),
            ];
            Like::create($like);
            $ids[] = $like['item_id'];
        }

        // キーワド送信
        $keyword = 'ー'; // ノートPC(id=5)、ショルダーバッグ(id=7)、タンブラー(id=8)、コーヒーミル(id=9)
        $response = $this->get( '/?keyword=' . $keyword );
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // キーワードを保持しているか
        $this->assertEquals( $keyword, $response['keyword']);

        // マイリストに遷移
        $response = $this->get( '/?tab=mylist&keyword=' . $response['keyword'] );
        $response->assertViewIs('index');
        $response->assertStatus(200);

        // キーワードが部分一致するものだけ表示されているか
        $likeItems = Item::where('id', $ids)->get();
        $dislikeItems = Item::whereNotIn('id', $ids)->get();
        foreach( $likeItems as $item ){
            if( $item['id']==5 || $item['id']==7 || $item['id']==8 || $item['id']==9 ){
                $response->assertSeeText( $item['name'] );
                $this->assertStringContainsString( $keyword, $item['name'] );
            }
            else{
                $response->assertDontSeeText( $item['name'] );
            }
        }
        foreach( $dislikeItems as $item ){
            $response->assertDontSeeText( $item['name'] );
        }
    }
}
