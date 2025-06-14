<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    // メールアドレスのバリデーション
    public function test_fail_email()
    {
        // ログイン画面へのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 入力項目（メールアドレス未入力）
        $requestParams = [
            'email' => '',
            'password' => '123456789',
        ];

        // 入力項目送信
        $response = $this->post('/login', $requestParams);
        $response->assertStatus(302);

        // メールアドレスのバリデーションがあるか
        $response->assertInvalid([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    // パスワードのバリデーション
    public function test_fail_password()
    {
        // ログイン画面へのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 入力項目（パスワード未入力）
        $requestParams = [
            'email' => 'test@coachtech',
            'password' => '',
        ];

        // 入力項目送信
        $response = $this->post('/login', $requestParams);
        $response->assertStatus(302);

        // パスワードのバリデーションがあるか
        $response->assertInvalid([
            'password' => 'パスワードを入力してください',
        ]);
    }

    // 未登録情報入力のバリデーション
    public function test_fail_typo()
    {
        // ログイン画面へのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 入力項目（未登録情報）
        $requestParams = [
            'email' => 'test@coachtech',
            'password' => '123456789',
        ];

        // 入力項目送信
        $response = $this->post('/login', $requestParams);
        $response->assertStatus(302);

        // 未登録のバリデーションがあるか
        $response->assertInvalid([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    // バリデーションを通過したとき
    public function test_success()
    {
        // 登録項目
        $registered = [
            'name' => 'コーチテック',
            'email' => 'test@coachtech',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

         // 登録項目送信
        $response = $this->post('/register', $registered);
        $response->assertStatus(302);

        // バリデーションエラーなし
        $response->assertValid(['name', 'email', 'password', 'password_confirmation']);

        // データベースに登録されたか
        $this->assertDatabaseHas('users', [
            'name' => 'コーチテック',
            'email' => 'test@coachtech',
        ]);

        // ログアウト処理
        $response = $this->post('/logout');

        // ユーザが現時点でログインしていないか
        $this->assertFalse(Auth::check());

        // ログイン画面へのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 入力項目（登録情報）
        $requestParams = [
            'email' => 'test@coachtech',
            'password' => '123456789',
        ];

        // 入力項目送信
        $response = $this->post('/login', $requestParams);
        $response->assertStatus(302);

        // バリデーションエラーなし
        $response->assertValid(['email', 'password']);

        // ユーザがログインできたか
        $this->assertTrue(Auth::check());

        // 商品一覧画面のマイリストに遷移するか
        $response->assertRedirect('/?tab=mylist');
    }
}
