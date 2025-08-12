<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the module data from the sidebar.json file
        $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
        $sidebarData = json_decode(file_get_contents($sidebarPath), true);

        // Check if the sidebar data has the expected structure
        if (isset($sidebarData['data']) && is_array($sidebarData['data'])) {
            foreach ($sidebarData['data'] as $module) {
            \App\Models\Module::updateOrCreate(
                ['slug' => $module['slug']],
                [
                'name' => $module['label'],
                'icon' => $module['icon'] ?? null,
                'parent_id' => $module['parent_id'] ?? 0,
                'description' => $module['description'] ?? null,
                'class_name' => str_replace(' ', '', ucwords(str_replace('_', ' ', $module['label']))),
                'sort_order' => $module['sortOrder'] ?? 0,
                'is_published' => 1, // Assuming all modules are published
                'is_left_menu' => $module['is_left_menu'] ?? false,
                'is_accordion' => $module['is_accordion'] ?? false,
                'status' => $module['status'] ?? true,
                ]
            );
            }
        }
    }
}
