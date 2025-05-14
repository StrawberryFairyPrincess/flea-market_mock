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

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            $table->integer('fashion');
            $table->integer('appliance');
            $table->integer('interior');
            $table->integer('lady');
            $table->integer('man');
            $table->integer('cosmetic');
            $table->integer('book');
            $table->integer('game');
            $table->integer('sport');
            $table->integer('kitchen');
            $table->integer('handmade');
            $table->integer('accessory');
            $table->integer('toy');
            $table->integer('child');

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
