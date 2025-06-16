<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'id' => 1,
            'category' => 'ファッション',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 2,
            'category' => '家電',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 3,
            'category' => 'インテリア',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 4,
            'category' => 'レディース',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 5,
            'category' => 'メンズ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 6,
            'category' => 'コスメ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 7,
            'category' => '本',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 8,
            'category' => 'ゲーム',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 9,
            'category' => 'スポーツ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 10,
            'category' => 'キッチン',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 11,
            'category' => 'ハンドメイド',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 12,
            'category' => 'アクセサリー',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 13,
            'category' => 'おもちゃ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'id' => 14,
            'category' => 'ベビー・キッズ',
        ];
        DB::table('categories')->insert($param);
    }
}
