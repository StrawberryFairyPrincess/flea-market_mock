<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category;


class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 25件シーディングする
        for ($i = 0; $i < 25; $i++){

            // ItemsとCategoriesテーブルのidカラムをランダムに並び替え、先頭の値を取得
            $set_item_id = Item::select('id')->orderByRaw("RAND()")->first()->id;
            $set_category_id = Category::select('id')->orderByRaw("RAND()")->first()->id;

            // クエリビルダを利用し、上記のモデルから取得した値が、現在までの複合主キーと重複するかを確認
            $category_item = DB::table('category_item')
                            ->where([
                                ['item_id', '=', $set_item_id],
                                ['category_id', '=', $set_category_id]
                            ])->get();

            // 上記のクエリビルダで取得したコレクションが空の場合、外部キーに上記のモデルから取得した値をセット
            if($category_item->isEmpty()){
                DB::table('category_item')->insert(
                    [
                        'item_id' => $set_item_id,
                        'category_id' => $set_category_id,
                    ]
                );
            }else{
                $i--;
            }
        }
    }
}
