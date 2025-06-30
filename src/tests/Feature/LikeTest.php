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
use App\Models\Like;


class LikeTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_like()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class); //4
        $this->seed(CategoriesTableSeeder::class); //14
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        // ログイン状態にする(id=1の人)
        $user = User::where( 'id', 1 )->first();
        $this->actingAs( $user );

        // ユーザがログインしているか
        $this->assertTrue( Auth::check() );

        // ユーザがメール認証しているか
        $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // 個別商品ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // 初期のイイネ数は0のはず
        $this->assertEquals( $item->likes()->count(), 0 );

        // 初期はグレーのアイコン
        $response->assertSee( '/img/icon/dislike.png' );

        // イイネアイコンをクリック
        $response = $this->get( route('item.like', ['item_id' => $item->id]) );
        $response->assertRedirect( '/item/' . $item['id'] );
        $response->assertStatus(302);
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // イイネ数は1に増加
        $this->assertEquals( $item->likes()->count(), 1 );

        // イイネしたら黄色のアイコン
        $response->assertSee( '/img/icon/like.png' );

        // 再度イイネアイコンクリック
        $response = $this->get( route('item.dislike', ['item_id' => $item->id]) );
        $response->assertRedirect( '/item/' . $item['id'] );
        $response->assertStatus(302);
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // イイネ数は0に減少
        $this->assertEquals( $item->likes()->count(), 0 );

        // アイコンはグレーに戻る
        $response->assertSee( '/img/icon/dislike.png' );
    }
}
