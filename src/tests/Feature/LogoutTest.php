<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_success()
    {
        // ユーザを作る
        $user = User::factory()->create();

        // ログイン状態にする
        $this->actingAs($user);

        // 現時点でユーザがログインしているか
        $this->assertTrue(Auth::check());

        // ログアウト
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $response->assertStatus(302);

        // ログアウトできたか
        $this->assertFalse(Auth::check());
    }
}
