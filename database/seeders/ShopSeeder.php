<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('shops')->insert([
            [
                'owner_id' => 1,
                'name' => '店名です',
                'information' => '店名infoです店名infoです店名infoです店名infoです',
                'filename' => '',
                'is_selling' => true
            ],
            [
                'owner_id' => 2,
                'name' => '店名です2',
                'information' => '店名infoです2店名infoです2店名infoです2店名infoです2',
                'filename' => '',
                'is_selling' => true
            ],
        ]);
    }
}
