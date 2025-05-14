<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // usersテーブルのファクトリからのシーディング
        User::factory()->count(5)->create();

        // conditionsテーブルへのシーディング処理
        $this->call(ConditionsTableSeeder::class);

        // itemsテーブルへのシーディング処理（user_idとcondition_idが存在しないとだめ）
        $this->call(ItemsTableSeeder::class);

        // categoriesテーブルのファクトリからのシーディング（item_idが存在しないとだめ）
        Category::factory()->count(10)->create();

    }
}
