<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        $existingModules = modulesList();
        $modules = modulesListNew();

        // Merge existing modules with new modules
        $modules = array_merge($existingModules, $modules);
        $modules = array_values(array_unique($modules, SORT_REGULAR));
        $modules = array_filter($modules, function ($module) {
            return isset($module['slug']) && !empty($module['slug']);
        });

        if (count($modules) > 0) {
            foreach ($modules as $key => $module) {
                if (!isset($module['id'])) {
                    $modules[$key]['id'] = $key + 1;
                }
            }
        }

        // Create roles with permissions
        $modules = array_column($modules, 'slug');
        $modules = array_fill_keys($modules, 5);

        // Create superAdmin role
        Role::create(['role' => 'superAdmin', 'permission' => json_encode($modules)]);

        // Create a test role
        Role::create(['role' => 'test']);

        User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@crm.com',
            'password' => bcrypt('P+8sbh3WOiRmfFTDXrSW4x*A'),
            'mobile' => '8857916707',
            'role_id' => 1,
        ]);

        Setting::updateOrCreate(
            ['id' => 1],
            [
            'company_name' => 'VASTRAM VEDA PRIVATE LIMITED',
            'website_url' => 'https://vastramveda.com',
            'registered_office_address' => 'G-234 Second floor Sector 63, Noida',
            'registered_office_address2' => '',
            'email_id' => 'vastramveda@gmail.com',
            'office_address' => 'G-234 Second floor Sector 63, Noida',
            'office_address2' => '',
            'phone_number' => '9873556217',
            'whatsapp' => '9873556217',
            'customer_care' => '9873556217',
            'gst_number' => null,
            'pan_number' => null,
            'timezone' => 'Asia/Kolkata',
            'currency' => 'INR',
            ]
        );

        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            ModuleSeeder::class,
        ]);
    }
}
