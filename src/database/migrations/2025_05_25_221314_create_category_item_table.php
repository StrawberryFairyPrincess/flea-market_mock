<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// use App\Models\Item;
// use App\Models\Category;

class CreateCategoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_item', function (Blueprint $table) {
            // $table->id();

            // $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // $table->foreignIdFor(Item::class)->constrained();
            // $table->foreignIdFor(Category::class)->constrained();

            // カラムを追加
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('category_id');
            // 複合主キーを定義
            $table->primary(['item_id','category_id']);
            // 指定したカラムに外部キー制約を定義
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');


            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_item');
    }
}
