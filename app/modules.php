<?php

function modulesList()
{
    return [
        [
            'id' => 1,
            'slug' => 'dashboard',
            'module_name' => 'Dashboard',
        ],
       [
            'id' => 2,
            'slug' => 'user',
            'module_name' => 'Users',
        ],
        [
            'id' => 3,
            'slug' => 'role',
            'module_name' => 'Roles',
        ],
        [
            'id' => 4,
            'slug' => 'settings',
            'module_name' => 'Settings',
        ],
        [
            'id' => 5,
            'slug' => 'module',
            'module_name' => 'Module Management',
        ],
        [
            'id' => 6,
            'slug' => 'dynamic-form',
            'module_name' => 'Dynamic Form Management',
        ],
        [
            'id' => 7,
            'slug' => 'master',
            'module_name' => 'Master',
        ],

    ];
}

function modulesListNew()
{
    $modules = json_decode(file_get_contents(resource_path('views/admin/pages/sidebar/sidebar.json')), true);
    $modules = collect($modules['data'])->map(function ($module) {
        $children = [];
        if (isset($module['children'])) {
            $children = collect($module['children'])->map(function ($child) use ($module) {
                return [
                    'module_name' => $child['label'],
                    'slug' => $child['slug'],
                    'parent_slug' => $module['slug'],
                    'icon' => $child['icon'] ?? null,
                    'route' => $child['route'] ?? null,
                    'sortOrder' => $child['sortOrder'] ?? null,
                ];
            })->toArray();
        }
        return [
            'id' => $module['id'],
            'module_name' => $module['label'],
            'slug' => $module['slug'],
            'icon' => $module['icon'] ?? null,
            'route' => $module['route'] ?? null,
            'sortOrder' => $module['sortOrder'] ?? null,
            'children' => $children,
            'is_left_menu' => $module['is_left_menu'] ?? false,
        ];
    })->toArray();
    return $modules;
}
