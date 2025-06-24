<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoryItemSeeder;
use Tests\TestCase;
use Faker\Factory;
// use App\Http\Requests\CommentRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;


class CommentTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    // 未ログインはコメント送信できない
    public function test_fail_unregistered()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class); //4
        $this->seed(CategoriesTableSeeder::class); //14
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        // 未ログイン
        $this->assertFalse( Auth::check() );

        // 個別商品ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // コメント内容
        $faker = Factory::create();
        $comment = [
            'comment' => $faker->sentence()
        ];

        // コメント送信
        $response = $this->post( '/comment/' . $item['id'], $comment );
        $response->assertRedirect( '/login' );
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');

        // コメントは登録されない
        $this->assertEquals( $item->comments->count(), 0 );

        // 登録されていないためコメントは表示されない
        $response->assertDontSeeText( $comment['comment'] );
    }

    // ログインするとコメント送信できる
    public function test_login()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class); //4
        $this->seed(CategoriesTableSeeder::class); //14
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoryItemSeeder::class);

        // 未ログイン
        $this->assertFalse( Auth::check() );

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

        // コメント内容
        $faker = Factory::create();
        $comment = [
            'comment' => $faker->sentence()
        ];

        // // バリデーションルール取得
        // $request = new CommentRequest();
        // $rules = $request->rules();

        // // コメントをバリデーションに通す
        // $validator = Validator::make( $comment, $rules );
        // $result = $validator->passes(); // バリデーションに通ったか(true or false)
        // $this->assertTrue( $result );

        // コメント送信
        $response = $this->post( '/comment/' . $item['id'], $comment );
        $response->assertRedirect( '/item/' . $item['id'] );
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');

        // 送信内容が表示されているか
        $response->assertSeeText( $comment['comment'] );
    }

    // 未入力のバリデーション
    public function test_fail_blank()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

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

        // コメント未入力
        $comment = [
            'comment' => ''
        ];

        // 個別商品ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // コメント送信
        $response = $this->post( '/comment/' . $item['id'], $comment );
        $response->assertRedirect( '/item/' . $item['id'] );

        // コメントのバリデーションエラーが発生しているか
        $response->assertInvalid([
            'comment' => 'コメントを入力してください',
        ]);

        // セッションから取得されるバリデーションメッセージ
        // $errors = $response->getSession()->get('errors');
        // $errorMessage = $errors->get('comment')[0];
        // $this->assertEquals( 'コメントを入力してください', $errorMessage );

        // 個別商品ページへのアクセス
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // バリデーションメッセージが表示されているか
        $response->assertSeeText( 'コメントを入力してください' );
    }

    // 255文字以上のバリデーション
    public function test_fail_char_count()
    {
        // ユーザを作る
        for( $i = 1; $i <= 5; $i++ ){
            User::factory()->create(['id' => $i]);
        }

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

        // コメント255文字以上(100単語の文章)
        $faker = Factory::create();
        $comment = [
            'comment' => $faker->sentence( 100, false )
        ];

        // 個別商品ページへのアクセス
        $item = Item::first();
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // コメント送信
        $response = $this->post( '/comment/' . $item['id'], $comment );
        $response->assertRedirect( '/item/' . $item['id'] );

        // コメントのバリデーションエラーが発生しているか
        $response->assertInvalid([
            'comment' => 'コメントは255文字以内で入力してください',
        ]);

        // // セッションから取得されるバリデーションメッセージ
        // $errors = $response->getSession()->get('errors');
        // $errorMessage = $errors->get('comment')[0];
        // $this->assertEquals( 'コメントは255文字以内で入力してください', $errorMessage );

        // 個別商品ページへのアクセス
        $response = $this->get( '/item/' . $item['id'] );
        $response->assertViewIs('item');
        $response->assertStatus(200);

        // バリデーションが表示されているか
        $response->assertSeeText( 'コメントは255文字以内で入力してください' );
    }
}
