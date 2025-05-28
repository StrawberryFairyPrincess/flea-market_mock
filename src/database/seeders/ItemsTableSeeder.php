<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Item;


class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $param = [
            'user_id' => '1',
            'condition_id' => '1',
            'name' => '腕時計',
            'price' => '15000',
            'describe' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '2',
            'name' => 'HDD',
            'price' => '5000',
            'describe' => '高速で信頼性の高いハードディスク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '3',
            'name' => '玉ねぎ3束',
            'price' => '300',
            'describe' => '新鮮な玉ねぎ3束のセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '4',
            'name' => '革靴',
            'price' => '4000',
            'describe' => 'クラシックなデザインの革靴',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '1',
            'name' => 'ノートPC',
            'price' => '45000',
            'describe' => '高性能なノートパソコン',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '2',
            'name' => 'マイク',
            'price' => '8000',
            'describe' => '高音質のレコーディング用マイク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '3',
            'name' => 'ショルダーバッグ',
            'price' => '3500',
            'describe' => 'おしゃれなショルダーバッグ',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '4',
            'name' => 'タンブラー',
            'price' => '500',
            'describe' => '使いやすいタンブラー',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '1',
            'name' => 'コーヒーミル',
            'price' => '4000',
            'describe' => '手動のコーヒーミル',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'condition_id' => '2',
            'name' => 'メイクセット',
            'price' => '2500',
            'describe' => '便利なメイクアップセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
        ];
        DB::table('items')->insert($param);

    }
}
