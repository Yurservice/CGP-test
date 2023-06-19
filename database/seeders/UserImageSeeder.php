<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserImage;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserImageSeeder extends Seeder
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

            for ($i = 0; $i < 100000; $i++) {
                $filename = $faker->uuid();
                $extension = $faker->randomElement(['jpg', 'png']);
                $fullFilename = $filename . '.' . $extension;

                UserImage::create([
                    'user_id' => mt_rand(1,10000),
                    'image' => $fullFilename,
                    'created_at' => $faker->dateTimeBetween($startDate, $endDate),
                ]);
            }
        }
    }
}
