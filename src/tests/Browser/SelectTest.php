<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
// use Faker\Factory;
use App\Models\User;
use App\Models\Item;


class SelectTest extends DuskTestCase
{
    // テストごとにデータベースを再マイグレーション
    use DatabaseMigrations;

    public function test_select()
    {
        // ユーザを作る
        $users = User::factory(5)->create();

        // 商品を設定
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 未ログイン
        $this->assertFalse( Auth::check() );

        // セレクトボックス
        $item = Item::first();
        $this->browse( function (Browser $browser) {
            $browser->loginAs( User::where( 'id', 1 ) ) // ログインする
                ->visit( '/purchase/' . $item['id'] ) // セレクトボックスのあるページに移動
                ->select( 'payment', 'credit' ) // セレクトボックスを選択
                ->assertSelected( 'payment', 'credit' ) // 選択されたことを確認
                ->assertSee('カード払い'); // 選択項目が表示されることを確認
                // ->press('submit_button') // フォームを送信
        });
    }
}
