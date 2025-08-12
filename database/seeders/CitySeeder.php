<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Define an array of cities to seed
        $cities = [
            ['name' => 'Mumbai', 'state_id' => 1],
            ['name' => 'Pune', 'state_id' => 1],
            ['name' => 'Bengaluru', 'state_id' => 2],
            ['name' => 'San Francisco', 'state_id' => 3],
            ['name' => 'Los Angeles', 'state_id' => 4],
            ['name' => 'Toronto', 'state_id' => 5],
            ['name' => 'Montreal', 'state_id' => 6],
        ];

        foreach ($cities as $city) {
            City::updateOrInsert(
                ['name' => $city['name']],
                [
                    'state_id' => $city['state_id'],
                    'status' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

    }
}
