<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categorization;

class CategorizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    private static int $sequence = 1;
    public function definition()
    {
        return [
            // 'item_id' => self::$sequence++,
            'item_id' => $this->faker->numberBetween(1, 10),
            'category_id' => $this->faker->numberBetween(1, 14),
        ];
    }
}

// 1: ファッション
// 2: 家電
// 3: インテリア
// 4: レディース
// 5: メンズ
// 6: コスメ
// 7: 本
// 8: ゲーム
// 9: スポーツ
// 10: キッチン
// 11: ハンドメイド
// 12: アクセサリー
// 13: おもちゃ
// 14: ベビー・キッズ