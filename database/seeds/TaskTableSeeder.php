<?php

use Illuminate\Database\Seeder;
use App\Model\Enums\TaskType;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1,5) as $index) {
            $faker = Faker::create();
            $test = collect(['php', 'ruby', 'java', 'javascript', 'bash'])->random(2)->values()->all();
//            dd($test);

            \Illuminate\Support\Facades\DB::table('tasks')->insert([
                'subject' => $faker->realText(10),
                'slug' => $faker->realText(10),
                'user_creator_id' => 1,
                'user_solver_id' => 2,
                'category_id' => 1,
                'description' => $faker->realText(30),
                'solution_description' => $faker->realText(30),
                'biding_expires_at' => Carbon::now(),
                'status' => 'unknown',
                'categories' => '{"k1": "value", "k2": 10}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
