<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $faker = Faker::create();

            $startDate = '2023-01-01';
            $endDate = '2023-06-18';
            $startDateObj = Carbon::parse($startDate);
            $endDateObj = Carbon::parse($endDate);
    
            for ($i = 0; $i < 10000; $i++) {
                User::create([
                    'name' => $faker->firstName,
                    'city' => $faker->city,
                    'created_at' => $faker->dateTimeBetween($startDate, $endDate),
                ]);
            }
        }
    }
}