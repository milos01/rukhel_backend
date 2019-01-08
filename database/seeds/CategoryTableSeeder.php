<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => "math",
            'display_name' => "Mathematics",
            'color' => "#cccccc",
        ]);

        DB::table('categories')->insert([
            'name' => "programming",
            'display_name' => "Programming",
            'color' => "#ffffff",
        ]);

        DB::table('categories')->insert([
            'name' => "physics",
            'display_name' => "Physics",
            'color' => "#000000",
        ]);
    }
}
