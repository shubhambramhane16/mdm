<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use DB;
use Validator;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index()
    {
        try {

        $page_title = 'Module Management';
        $page_description = '';
        $breadcrumbs = [
            [
            'title' => 'Module Management',
            'url' => '',
            ]
        ];
        $status = request('status');
        if ($status == '0') {
            $status = '2';
        }
        $details = Module::when($status, function ($users) use ($status) {
            if ($status != '-1') {
            $status = conditionalStatus($status);
            $users->where('status', '=', $status);
            }
        })->orderBy('sort_order', 'asc')->get();
        // dd($details);
        return view('admin.pages.module.list', compact('page_title', 'page_description', 'breadcrumbs',  'details'));
        } catch (\Exception $e) {
        dd($e);
        return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function add(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                // dd($request->all());
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    // 'slug' => 'required|string|max:255|unique:modules,slug',
                    'description' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                DB::beginTransaction();
                $array = [
                    'name' => $request->name,
                    // if name is Expense Category then class_name is ExpenseCategory
                    'class_name' => str_replace(' ', '', ucwords($request->name)),
                    'slug' => Str::slug($request->name),
                    'icon'=> $request->icon,
                    'description' => $request->description,
                    'parent_id' => $request->parent_id ?? 0,
                    'status' => 1, // Default status
                ];

                $existingModule = Module::where('slug', $array['slug'])->first();
                if ($existingModule) {
                    return redirect()->back()->withErrors(['Module with this name already exists.']);
                }
                $module = Module::updateOrCreate(['slug' => $array['slug']],$array);
                DB::commit();
                if (!$module) {
                    return redirect()->back()->withErrors(['Failed to add module. Please try again.']);
                }
                return redirect('admin/module/list')->with('success', 'Module added successfully.');
            }

            $pageSettings = $this->pageSetting('add');
            $page_title =  $pageSettings['page_title'];
            $page_description = $pageSettings['page_description'];
            $breadcrumbs = $pageSettings['breadcrumbs'];
            return view('admin.pages.module.add', compact('page_title', 'page_description', 'breadcrumbs'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    // sort-order
    public function sortOrder(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $sortOrders = $request->input('sort_order', []);
                if (is_array($sortOrders) && count($sortOrders) > 0) {
                    DB::beginTransaction();
                    foreach ($sortOrders as $item) {
                        if (isset($item['id']) && isset($item['position'])) {
                            Module::where('id', $item['id'])->update(['sort_order' => $item['position']]);
                        }
                    }
                    // Update sidebar.json sortOrder
                    $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
                    $sidebarData = json_decode(file_get_contents($sidebarPath), true);

                    foreach ($sortOrders as $item) {
                        $module = Module::find($item['id']);
                        if ($module) {
                            foreach ($sidebarData['data'] as &$sidebarItem) {
                                if ($sidebarItem['id'] === $module->slug) {
                                    $sidebarItem['sortOrder'] = $item['position'];
                                    break;
                                }
                            }
                        }
                    }
                    file_put_contents($sidebarPath, json_encode($sidebarData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                    DB::commit();
                    return redirect()->back()->with('success', 'Sort order updated successfully.');
                } else {
                    return redirect()->back()->withErrors(['Invalid sort order data.']);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    // 'slug' => 'required|string|max:255|unique:modules,slug,' . $id,
                    'description' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                DB::beginTransaction();
                $array = [
                    'name' => $request->name,
                    // if name is Expense Category then class_name is ExpenseCategory
                    'class_name' => str_replace(' ', '', ucwords($request->name)),
                    'slug' => Str::slug($request->name),
                    'description' => $request->description,
                    'parent_id' => $request->parent_id ?? 0,
                    'icon' => $request->icon
                ];

                $existingModule = Module::where('id', '!=', $id)->where('slug', $array['slug'])->first();
                if ($existingModule) {
                    return redirect()->back()->withErrors(['Module with this name already exists.']);
                }
                $module = Module::updateOrCreate(['id' => $id],$array);
                DB::commit();
                if (!$module) {
                    return redirect()->back()->withErrors(['Failed to update module. Please try again.']);
                }
                return redirect('admin/module/list')->with('success', 'Module updated successfully.');
            }

            $details = Module::where('id',$id)->first();
            if ($details) {

                $pageSettings = $this->pageSetting('edit', ['title' => $details->name]);
                $page_title =  $pageSettings['page_title'];
                $page_description = $pageSettings['page_description'];
                $breadcrumbs = $pageSettings['breadcrumbs'];
                return view('admin.pages.module.edit', compact('page_title', 'page_description', 'breadcrumbs', 'details'));
            } else {
                return redirect()->back()->withErrors(['Role details not exist.']);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function publish($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                // dd($id);
                $module = Module::find($id);
                // dd($module);
                if (!$module) {
                    return redirect()->json(['success' => false, 'message' => 'Module not found.']);
                }
                // $module->name = str_replace(' ', '', ucwords(str_replace('_', ' ', $module->name)));

                createRoutes($module);

                createController($module);
                createModelAndMigration($module);
                createViews($module);

                $module->is_published = 1; // Set status to published
                $module->save();
                DB::commit();

                // Update sidebar.json
                $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
                $sidebarData = json_decode(file_get_contents($sidebarPath), true);

                $newModule = [
                    'label' => $module->name,
                    'icon' => $module->icon,
                    'route' => $module->slug . '/list',
                    'sortOrder' => 10, // Default sort order, can be adjusted
                    'id' => $module->slug,
                    'slug' => $module->slug,
                    'is_left_menu' => $module->is_left_menu ? true : false,
                    'is_accordion' => $module->is_accordion ? true : false,
                    'children' => $module->is_accordion ? [
                        [
                            'label' => 'All Modules',
                            'route' => $module->slug . '/list',
                            'slug' => $module->slug
                        ]
                    ] : [],
                ];

                // Check if the module already exists in sidebar data
                $moduleExists = collect($sidebarData['data'])->first(function ($item) use ($module) {
                    return $item['id'] === $module->slug;
                });

                if ($moduleExists) {
                    // Update existing module
                    $sidebarData['data'] = collect($sidebarData['data'])->map(function ($item) use ($module, $newModule) {
                        return $item['id'] === $module->slug ? $newModule : $item;
                    })->toArray();
                } else {
                    // Add new module
                    $sidebarData['data'][] = $newModule;
                }

                // Save updated sidebar data
                file_put_contents($sidebarPath, json_encode($sidebarData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


                return response()->json(['success' => true, 'message' => 'Module published successfully.']);
            } else {
                return redirect()->json(['success' => false, 'message' => 'Module details not found.']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // left-menu
    public function leftMenu($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();

                // Fetch the module by ID
                $module = Module::find($id);
                if (!$module) {
                    DB::rollback();
                    return response()->json(['success' => false, 'message' => 'Module not found.']);
                }

                $moduleSlug = $module->slug;

                // Load sidebar.json
                $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
                $sidebarData = json_decode(file_get_contents($sidebarPath), true);

                $updated = false;
                if (isset($sidebarData['data']) && is_array($sidebarData['data'])) {
                    foreach ($sidebarData['data'] as &$item) {
                        if ($item['id'] === $moduleSlug) {
                            $item['is_left_menu'] = isset($item['is_left_menu']) ? !$item['is_left_menu'] : true;
                            $module->is_left_menu = $item['is_left_menu'];
                            $updated = true;
                            break;
                        }
                    }
                }

                // If not found in sidebar, just update in database
                if (!$updated) {
                    $module->is_left_menu = !$module->is_left_menu;
                }

                $module->save();

                // Save updated sidebar data only if changed
                if ($updated) {
                    file_put_contents($sidebarPath, json_encode($sidebarData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Module left menu status updated successfully.']);

            } else {
                return response()->json(['success' => false, 'message' => 'Module details not found.']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // accordion
    public function accordion($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();

                // Fetch the module by ID
                $module = Module::find($id);
                if (!$module) {
                    DB::rollback();
                    return response()->json(['success' => false, 'message' => 'Module not found.']);
                }

                // Load sidebar.json
                $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
                $sidebarData = json_decode(file_get_contents($sidebarPath), true);

                $updated = false;
                if (isset($sidebarData['data']) && is_array($sidebarData['data'])) {
                    foreach ($sidebarData['data'] as &$item) {
                        if ($item['id'] === $module->slug) {
                            // Toggle boolean instead of 1/0
                            $item['is_accordion'] = isset($item['is_accordion']) ? !$item['is_accordion'] : true;
                            $module->is_accordion = $item['is_accordion'];
                            if ($item['is_accordion']) {
                                $item['children'] = [
                                    [
                                        'label' => 'All Modules',
                                        'route' => $module->slug . '/list',
                                        'slug' => $module->slug
                                    ]
                                ];
                            } else {
                                if (isset($item['children'])) {
                                    unset($item['children']);
                                }
                            }
                            $updated = true;
                            break;
                        }
                    }
                }

                // If not found in sidebar, just update in database
                if (!$updated) {
                    $module->is_accordion = !$module->is_accordion;
                }

                $module->save();

                // Save updated sidebar data only if changed
                if ($updated) {
                    file_put_contents($sidebarPath, json_encode($sidebarData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Module accordion status updated successfully.']);

            } else {
                return response()->json(['success' => false, 'message' => 'Module details not found.']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function delete($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                $cat = Module::find($id);
                // dd($cat);

                // Prevent deletion if module is published
                // if ($cat && $cat->is_published) {
                //     DB::rollback();
                //     return redirect()->back()->with('error', 'Published modules cannot be deleted.');
                // }

                if ($cat && $cat->delete()) {
                    DB::commit();
                    // Remove the module from sidebar.json
                    $sidebarPath = resource_path('views/admin/pages/sidebar/sidebar.json');
                    $sidebarData = json_decode(file_get_contents($sidebarPath), true);
                    $sidebarData['data'] = array_values(array_filter($sidebarData['data'], function ($item) use ($cat) {
                        return $item['id'] !== $cat->slug;
                    }));
                    file_put_contents($sidebarPath, json_encode($sidebarData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                    // Remove the module's directory
                    $modulePath = app_path('Modules/' . $cat->slug);
                    if (is_dir($modulePath)) {
                        $files = glob($modulePath . '/*');
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            } elseif (is_dir($file)) {
                                rmdir($file);
                            }
                        }
                        rmdir($modulePath);
                    }

                    // Remove the module's controller
                    $controllerPath = app_path('Http/Controllers/Admin/' . $cat->name . 'Controller.php');
                    if (file_exists($controllerPath)) {
                        unlink($controllerPath);
                    }

                    // Remove the module's model
                    $modelPath = app_path('Models/' . $cat->name . '.php');
                    if (file_exists($modelPath)) {
                        unlink($modelPath);
                    }

                    // Remove the module's migration
                    $migrationFiles = glob(database_path('migrations/*_' . Str::slug($cat->name) . '.php'));
                    foreach ($migrationFiles as $migrationFile) {
                        if (file_exists($migrationFile)) {
                            unlink($migrationFile);
                        }
                    }

                    // Remove the module's views
                    $viewsPath = resource_path('views/admin/pages/' . Str::slug($cat->name));
                    if (is_dir($viewsPath)) {
                        $files = glob($viewsPath . '/*');
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            } elseif (is_dir($file)) {
                                rmdir($file);
                            }
                        }
                        rmdir($viewsPath);
                    }

                    // Remove the module's routes from the modules folder
                    $routesPath = base_path('routes/modules/' . Str::slug($cat->name) . '.php');
                    if (file_exists($routesPath)) {
                        unlink($routesPath);
                    }
                    // Remove the module's sidebar entry
                    return redirect()->back()->with('success', 'Module deleted successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to delete try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Module details not found.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function updateStatus($id, $status)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                $status = ($status == 1) ? $status = 0 : $status = 1;
                $updateArr = [
                    'status' => $status,
                ];
                $response = Module::UpdateOrCreate(['id' => $id], $updateArr);
                DB::commit();
                return redirect()->back()->with('success', 'Module status updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Module details not found.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    public function pageSetting($action, $dataArray = [])
    {
        if ($action == 'edit') {
            $data['page_title'] = 'Module List';
            $data['page_description'] = 'Edit Module';
            $data['breadcrumbs'] = [
                [
                    'title' => 'Modules',
                    'url' => url('admin/module/list'),
                ]
            ];
            if (isset($dataArray['title']) && !empty($dataArray['title'])) {
                $data['breadcrumbs'][] =
                    [
                        'title' => $dataArray['title'],
                        'url' => '',

                    ];
            }
            return $data;
        }

        if ($action == 'add') {
            $data['page_title'] = 'Modules';
            $data['page_description'] = 'Add New Module';
            $data['breadcrumbs'] = [
                [
                    'title' => 'Module',
                    'url' => url('admin/module/list'),
                ],
                [
                    'title' => 'Add a New Module',
                    'url' => '',
                ],
            ];
            return $data;
        }

        if ($action == 'template') {
            $data['page_title'] = 'Module Template';
            $data['page_description'] = 'Module Template';
            $data['breadcrumbs'] = [
                [
                    'title' => 'Module Template',
                    'url' => '',
                ],
            ];
            return $data;
        }

    }
}
