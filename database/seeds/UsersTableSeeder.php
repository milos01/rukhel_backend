<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use \App\Model\Enums\UserType;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,5) as $index) {
            DB::table('users')->insert([
                'full_name' => $faker->name,
                'email' => $faker->email,
                'username' => $faker->userName,
                'role' => UserType::MODERATOR(),
                'password' => bcrypt('secret'),
                'provider' => str_random(8),
                'provider_id' => str_random(8),
                'activated' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('users')->insert([
                'full_name' => $faker->name,
                'email' => $faker->email,
                'username' => $faker->userName,
                'role' => UserType::USER(),
                'password' => bcrypt('secret'),
                'provider' => str_random(8),
                'provider_id' => str_random(8),
                'activated' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        foreach (range(1,2) as $index) {
            DB::table('users')->insert([
                'full_name' => $faker->name,
                'email' => $faker->email,
                'username' => $faker->userName,
                'role' => UserType::ADMIN(),
                'password' => bcrypt('secret'),
                'provider' => str_random(8),
                'provider_id' => str_random(8),
                'activated' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
