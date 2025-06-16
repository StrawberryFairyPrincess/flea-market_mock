<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Condition;


class ConditionsTableSeeder extends Seeder
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
            'condition' => '良好',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'id' => 2,
            'condition' => '目立った傷や汚れなし',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'id' => 3,
            'condition' => 'やや傷や汚れあり',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'id' => 4,
            'condition' => '状態が悪い',
        ];
        DB::table('conditions')->insert($param);

    }
}
