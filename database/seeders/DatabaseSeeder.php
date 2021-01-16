<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Dish seeders
        DB::table('dishes')->insert([
            'name' => "Veg Biryani",
            'available' => 5,
            'price' => 7000,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
        DB::table('dishes')->insert([
            'name' => "Chicken Biryani",
            'available' => 15,
            'price' => 10000,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
        DB::table('dishes')->insert([
            'name' => "Meal",
            'available' => 5,
            'price' => 7000,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
        DB::table('dishes')->insert([
            'name' => "Special Meal",
            'available' => 15,
            'price' => 10000,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
        DB::table('dishes')->insert([
            'name' => "Tea",
            'available' => 100,
            'price' => 1000,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        // Settings seeders
        DB::table('settings')->insert([
            'name' => "stock_alert_email",
            'value' => 'contact@subdine.com',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
        DB::table('settings')->insert([
            'name' => "stock_alert_quantity",
            'value' => '2',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
    }
}
