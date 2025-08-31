<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Usersテーブルのファクトリからのシーディング
        User::factory()->count(5)->create();

        // Conditionsテーブルへのシーディング処理
        $this->call(ConditionsTableSeeder::class);

        // Categoriesテーブルへのシーディング処理
        $this->call(CategoriesTableSeeder::class);

        // Itemsテーブルへのシーディング処理（user_idとcondition_idが存在しないとだめ）
        $this->call(ItemsTableSeeder::class);

        // Category_Itemテーブルへのシーディング（item_idとcategory_idが存在しないとだめ）
        $this->call(CategoryItemSeeder::class);

    }
}
