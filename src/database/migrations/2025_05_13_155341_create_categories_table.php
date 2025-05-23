<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('category');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
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