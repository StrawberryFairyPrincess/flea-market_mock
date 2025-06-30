<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Destination;


class ProfileTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_profile()
    {
        // ユーザを作る
        $users = User::factory(1)->create();

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

        // ログイン状態にする(id=1の人)
        $user = User::where( 'id', 1 )->first();
        $this->actingAs( $user );

        // ユーザがログインできたか
        $this->assertTrue( Auth::check() );

        // ユーザがメール認証できているか
        $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // プロフィール画面(マイページ)へのアクセス
        $response = $this->get( '/mypage/profile' );
        $response->assertViewIs('profile');
        $response->assertStatus(200);

        // プロフィール画像が表示されているか
        $response->assertSee( $destination['img_pass'] );

        // ユーザ名が表示されているか
        $response->assertSee( Auth::user()->name );

        // 郵便番号が表示されているか
        $response->assertSee( $destination['post_code'] );

        // 住所が表示されているか
        $response->assertSee( $destination['address'] );

        // 建物名が表示されているか
        $response->assertSee( $destination['building'] );
    }
}
