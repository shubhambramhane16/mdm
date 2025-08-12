<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $states = [
            ['name' => 'Maharashtra', 'code' => 'MH', 'country_id' => 1],
            ['name' => 'Karnataka', 'code' => 'KA', 'country_id' => 1],
            ['name' => 'California', 'code' => 'CA', 'country_id' => 2],
            ['name' => 'Texas', 'code' => 'TX', 'country_id' => 2],
            ['name' => 'Ontario', 'code' => 'ON', 'country_id' => 3],
            ['name' => 'Quebec', 'code' => 'QC', 'country_id' => 3],
        ];

        foreach ($states as $state) {
            State::updateOrInsert(
                ['name' => $state['name']],
                [
                    'code' => $state['code'],
                    'country_id' => $state['country_id'],
                    'status' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

    }
}
