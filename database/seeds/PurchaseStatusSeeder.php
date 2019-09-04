<?php

use Illuminate\Database\Seeder;

class PurchaseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchase_status')->insert([
          'name' => 'New'
          'description' => 'New'
          'user_id' => '1',
        ]);

        DB::table('purchase_status')->insert([
          'name' => 'New'
          'description' => 'New'
          'user_id' => '1',
        ]);
    }
}
