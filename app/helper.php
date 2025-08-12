<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;


function runTimeChecked($myId, $matchId)
{
    if ($myId == $matchId)
        return 'checked';
}

function getSystemRoles($role = null)
{
    $data = 'App\Models\Role'::when($role, function ($data) use ($role) {
        if ($role) {
            $data->where('id', '=',  $role);
        }
    })->get();
    return $data;
}

function runTimeSelection($myId, $matchId)
{
    if ($myId == $matchId)
        return 'selected';
}


function SidebarModules()
{
    $data = 'App\Models\Module'::
    where('status', 1)
    ->where('is_show_in_menu',1)
    ->orderBy('sort_order', 'asc')
    ->get();

    return $data;
}

function conditionalStatus($status)
{
    if ($status == '1') {
        $status = 1;
    }
    if ($status == '2') {
        $status = 0;
    }
    return $status;
}



function getRole()
{
    $role_id = Auth::user()->role_id;
    $data = 'App\Models\Role'::where('id', $role_id)->first();
    $role = $data->role;
    return $role;
}


function getRoleById($id)
{
    $data = 'App\Models\Role'::where('id', $id)->first();
    // dd($data);
    return $data;
}


function getCurrency($amount)
{
    $data = "₹ " . number_format($amount, 2);
    return $data;
}


function formatIndianCurrency($number) {
        $number = (int) $number;
        $num = preg_replace('/\D/', '', $number);
        $len = strlen($num);
        if ($len > 3) {
            $last3 = substr($num, -3);
            $rest = substr($num, 0, $len - 3);
            $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
            return $rest . "," . $last3;
        } else {
            return $num;
        }
}

function createRoutes($module)
{


    // make a file in routes/modules folder
    $filePath = base_path('routes/modules/' . $module->class_name . '.php');
    if (!file_exists($filePath)) {
        $content = "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n";
        $content .= "Route::group(['prefix' => '$module->slug'], function () {\n";
        // create a route with controller
        $content .= "    Route::get('list', [App\Http\Controllers\admin\\" . $module->class_name. "Controller::class, 'index']);\n";
        $content .= "    Route::any('add', [App\Http\Controllers\admin\\" . $module->class_name. "Controller::class, 'add']);\n";
        $content .= "    Route::any('edit/{id}', [App\Http\Controllers\admin\\" . $module->class_name. "Controller::class, 'edit']);\n";
        $content .= "    Route::any('delete/{id}', [App\Http\Controllers\admin\\" . $module->class_name. "Controller::class, 'delete']);\n";
        $content .= "    Route::any('update-status/{id}/{status}', [App\Http\Controllers\admin\\" . $module->class_name. "Controller::class, 'updateStatus']);\n";

        $content .= "});\n";

        file_put_contents($filePath, $content);
    }
}

function createController($module)
{
    // create controller for the module in app/Http/Controllers/admin folder
    $controllerPath = app_path('Http/Controllers/admin/' . $module->class_name . 'Controller.php');

    if (!file_exists($controllerPath)) {
        $content = "<?php\n\nnamespace App\Http\Controllers\Admin;\n\n";
        $content .= "use App\Http\Controllers\Controller;\n";
        $content .= "use Illuminate\Http\Request;\n";
        $content .= "use App\Models\\" . $module->class_name . ";\n";
        $content .= "use DB;\n";
        $content .= "use Validator;\n\n";

        // class declaration
        $content .= "class " . $module->class_name . "Controller extends Controller\n{\n";

        // index function
        $content .= "    public function index()\n    {\n";
        $content .= "        try {\n";
        $content .= "            \$view_page_base_path = env('VIEW_ADMIN_BASE_PATH');\n";
        $content .= "            \$jsonPath = \$view_page_base_path . \"{$module->slug}/view_list.json\";\n";
        $content .= "            \$json = json_decode(file_get_contents(resource_path(\$jsonPath)), true);\n\n";
        $content .= "            if (!\$json) {\n";
        $content .= "                return redirect()->back()->withErrors(['JSON file not found or invalid.']);\n";
        $content .= "            }\n\n";
        $content .= "            \$pageSettings = \$this->pageSetting('index');\n";
        $content .= "            \$page_title = \$pageSettings['page_title'];\n";
        $content .= "            \$page_description = \$pageSettings['page_description'];\n";
        $content .= "            \$breadcrumbs = \$pageSettings['breadcrumbs'];\n";
        $content .= "            \$status = request('status');\n";
        $content .= "            if (\$status == '0') {\n";
        $content .= "                \$status = '2';\n";
        $content .= "            }\n";
        $content .= "            \$details = {$module->class_name}::when(\$status, function (\$query) use (\$status) {\n";
        $content .= "                if (\$status != '-1') {\n";
        $content .= "                    \$status = conditionalStatus(\$status);\n";
        $content .= "                    \$query->where('status', '=', \$status);\n";
        $content .= "                }\n";
        $content .= "            })->orderBy('id', 'asc')->paginate(10);\n";
        $content .= "            return view('admin.layout.data-view-master.list', compact('json','page_title', 'page_description', 'breadcrumbs',  'details'));\n";
        $content .= "        } catch (\\Exception \$e) {\n";
        $content .= "            dd(\$e);\n";
        $content .= "            return redirect()->back()->with('error', \$e->getMessage());\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // add function
        $content .= "    public function add(Request \$request)\n    {\n";
        $content .= "        try {\n";
        $content .= "            if (\$request->isMethod('post')) {\n";
        $content .= "                // Handle form submission logic here\n";
        $content .= "            }\n\n";
        $content .= "            \$view_page_base_path = env('VIEW_ADMIN_BASE_PATH');\n";
        $content .= "            \$jsonPath = \$view_page_base_path . \"{$module->slug}/add_form.json\";\n";
        $content .= "            \$json = json_decode(file_get_contents(resource_path(\$jsonPath)), true);\n\n";
        $content .= "            if (!\$json) {\n";
        $content .= "                return redirect()->back()->withErrors(['JSON file not found or invalid.']);\n";
        $content .= "            }\n";
        $content .= "            \$pageSettings = \$this->pageSetting('add');\n";
        $content .= "            \$page_title =  \$pageSettings['page_title'];\n";
        $content .= "            \$page_description = \$pageSettings['page_description'];\n";
        $content .= "            \$breadcrumbs = \$pageSettings['breadcrumbs'];\n";
        $content .= "            return view('admin.layout.form-master.add', compact('json','page_title', 'page_description', 'breadcrumbs'));\n";
        $content .= "        } catch (\\Exception \$e) {\n";
        $content .= "            DB::rollback();\n";
        $content .= "            return redirect()->back()->withErrors(\$e->getMessage());\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // edit function
        $content .= "    public function edit(Request \$request, \$id)\n    {\n";
        $content .= "        try {\n\n";
        $content .= "            if (\$request->isMethod('post')) {\n";
        $content .= "                // Handle form submission logic here\n";
        $content .= "            }\n\n";
        $content .= "            \$details = {$module->class_name}::where('id', \$id)->first();\n";
        $content .= "            if (\$details) {\n";
        $content .= "                \$view_page_base_path = env('VIEW_ADMIN_BASE_PATH');\n";
        $content .= "                \$jsonPath = \$view_page_base_path . \"{$module->slug}/edit_form.json\";\n";
        $content .= "                \$json = json_decode(file_get_contents(resource_path(\$jsonPath)), true);\n\n";
        $content .= "                if (!\$json) {\n";
        $content .= "                    return redirect()->back()->withErrors(['JSON file not found or invalid.']);\n";
        $content .= "                }\n\n";
        $content .= "                \$pageSettings = \$this->pageSetting('edit');\n";
        $content .= "                \$page_title =  \$pageSettings['page_title'];\n";
        $content .= "                \$page_description = \$pageSettings['page_description'];\n";
        $content .= "                \$breadcrumbs = \$pageSettings['breadcrumbs'];\n";
        $content .= "                return view('admin.layout.form-master.edit', compact('page_title', 'page_description', 'breadcrumbs', 'details', 'json'));\n";
        $content .= "            } else {\n";
        $content .= "                return redirect()->back()->withErrors(['{$module->class_name} details not exist.']);\n";
        $content .= "            }\n";
        $content .= "        } catch (\\Exception \$e) {\n";
        $content .= "            dd(\$e);\n";
        $content .= "            DB::rollback();\n";
        $content .= "            return redirect()->back()->withErrors(\$e->getMessage());\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // delete function
        $content .= "    public function delete(\$id)\n    {\n";
        $content .= "        try {\n";
        $content .= "            if (\$id) {\n";
        $content .= "                DB::beginTransaction();\n";
        $content .= "                \$item = {$module->class_name}::find(\$id);\n";
        $content .= "                if (\$item && \$item->delete()) {\n";
        $content .= "                    DB::commit();\n";
        $content .= "                    return redirect()->back()->with('success', '{$module->class_name} deleted successfully.');\n";
        $content .= "                } else {\n";
        $content .= "                    return redirect()->back()->with('error', 'Failed to delete. Please try again.');\n";
        $content .= "                }\n";
        $content .= "            } else {\n";
        $content .= "                return redirect()->back()->with('error', '{$module->class_name} details not found.');\n";
        $content .= "            }\n";
        $content .= "        } catch (\\Exception \$e) {\n";
        $content .= "            DB::rollback();\n";
        $content .= "            return redirect()->back()->with('error', \$e->getMessage());\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // updateStatus function
        $content .= "    public function updateStatus(\$id, \$status)\n    {\n";
        $content .= "        try {\n";
        $content .= "            if (\$id) {\n";
        $content .= "                DB::beginTransaction();\n";
        $content .= "                \$newStatus = (\$status == 1) ? 0 : 1;\n";
        $content .= "                \$updateArr = [\n";
        $content .= "                    'status' => \$newStatus,\n";
        $content .= "                ];\n";
        $content .= "                \$item = {$module->class_name}::find(\$id);\n";
        $content .= "                if (\$item) {\n";
        $content .= "                    \$item->update(\$updateArr);\n";
        $content .= "                    DB::commit();\n";
        $content .= "                    return redirect()->back()->with('success', '{$module->class_name} status updated successfully.');\n";
        $content .= "                } else {\n";
        $content .= "                    return redirect()->back()->with('error', '{$module->class_name} details not found.');\n";
        $content .= "                }\n";
        $content .= "            } else {\n";
        $content .= "                return redirect()->back()->with('error', '{$module->class_name} details not found.');\n";
        $content .= "            }\n";
        $content .= "        } catch (\\Exception \$e) {\n";
        $content .= "            DB::rollback();\n";
        $content .= "            return redirect()->back()->with('error', \$e->getMessage());\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // pageSetting function
        $content .= "    public function pageSetting(\$action, \$dataArray = [])\n    {\n";
        $content .= "        \$data = [];\n\n";
        $content .= "        if (\$action == 'edit') {\n";
        $content .= "            \$data['page_title'] = '{$module->class_name} Management';\n";
        $content .= "            \$data['page_description'] = 'Edit {$module->class_name}';\n";
        $content .= "            \$data['breadcrumbs'] = [\n";
        $content .= "                [\n";
        $content .= "                    'title' => '{$module->class_name} Management',\n";
        $content .= "                    'url' => url('admin/{$module->slug}/list'),\n";
        $content .= "                ]\n";
        $content .= "            ];\n";
        $content .= "            if (isset(\$dataArray['title']) && !empty(\$dataArray['title'])) {\n";
        $content .= "                \$data['breadcrumbs'][] = [\n";
        $content .= "                    'title' => \$dataArray['title'],\n";
        $content .= "                    'url' => '',\n";
        $content .= "                ];\n";
        $content .= "            }\n";
        $content .= "            return \$data;\n";
        $content .= "        }\n\n";
        $content .= "        if (\$action == 'add') {\n";
        $content .= "            \$data['page_title'] = '{$module->class_name} Management';\n";
        $content .= "            \$data['page_description'] = 'Add New {$module->class_name}';\n";
        $content .= "            \$data['breadcrumbs'] = [\n";
        $content .= "                [\n";
        $content .= "                    'title' => '{$module->class_name} Management',\n";
        $content .= "                    'url' => url('admin/{$module->slug}/list'),\n";
        $content .= "                ],\n";
        $content .= "                [\n";
        $content .= "                    'title' => 'Add a New {$module->class_name}',\n";
        $content .= "                    'url' => '',\n";
        $content .= "                ],\n";
        $content .= "            ];\n";
        $content .= "            return \$data;\n";
        $content .= "        }\n\n";
        $content .= "        if (\$action == 'index') {\n";
        $content .= "            \$data['page_title'] = '{$module->class_name} Management';\n";
        $content .= "            \$data['page_description'] = '{$module->class_name} Management';\n";
        $content .= "            \$data['breadcrumbs'] = [\n";
        $content .= "                [\n";
        $content .= "                    'title' => '{$module->class_name} Management',\n";
        $content .= "                    'url' => '',\n";
        $content .= "                ],\n";
        $content .= "            ];\n";
        $content .= "            return \$data;\n";
        $content .= "        }\n\n";
        $content .= "        return \$data;\n";
        $content .= "    }\n";

        // closing class
        $content .= "}\n";

        file_put_contents($controllerPath, $content);
    }
}


function createModelAndMigration($module)
{
    // Normalize module name

    // Run artisan command to create model and migration together
    \Artisan::call('make:model', [
        'name' => $module->class_name,
        '--migration' => true
    ]);
}

function createViews($module)
{
    // create views for the module in resources/views/admin folder

    $viewPath = resource_path('views/admin/pages/' . $module->slug);

    if (!file_exists($viewPath)) {
        mkdir($viewPath, 0755, true);

        // view_list.json
        $viewListData = [
            "module" => $module->name,
            "slug" => $module->slug,
            "data" => [
            [
                "fieldKey" => "name",
                "label" => "Name",
                "relation" => "",
                "config" => [
                "name" => "name",
                "value" => "id"
                ]
            ]
            ]
        ];
        file_put_contents($viewPath . '/view_list.json', json_encode($viewListData, JSON_PRETTY_PRINT));

        // add_form.json
        $addFormData = [
            "module" => $module->name,
            "slug" => $module->slug,
            "type" => "form",
            "formNameKey" => "new-registration",
            "data" => [
                [
                    "formName" => "New User Registration",
                    "formId" => 1,
                    "templateId" => 1,
                    "submitButtonText" => "Update",
                    "formDescription" => "Fill the registration form carefully.",
                    "formCssClass" => "form-container",
                    "gridColumns" => 12,
                    "fields" => [
                        [
                            "fieldKey" => "parent_id",
                            "label" => "Parent Module",
                            "type" => "dropdown",
                            "column" => 12,
                            "dataSourceType" => "master",
                            "dataSource" => "module",
                            "relation" => "parent_id",
                            "isMultiple" => false,
                            "autocomplete" => true,
                            "config" => [
                                "name" => "name",
                                "value" => "id"
                            ],
                            "validation" => [
                                "isRequired" => false
                            ]
                        ]
                    ]
                ]
            ]
        ];
        file_put_contents($viewPath . '/add_form.json', json_encode($addFormData, JSON_PRETTY_PRINT));

        // edit_form.json
        $editFormData = [
            "module" => $module->name,
            "slug" => $module->slug,
            "type" => "form",
            "formNameKey" => "new-registration",
            "data" => [
                [
                    "formName" => "New User Registration",
                    "formId" => 1,
                    "templateId" => 1,
                    "submitButtonText" => "Update",
                    "formDescription" => "Fill the registration form carefully.",
                    "formCssClass" => "form-container",
                    "gridColumns" => 12,
                    "fields" => [
                        [
                            "fieldKey" => "parent_id",
                            "label" => "Parent Module",
                            "type" => "dropdown",
                            "column" => 12,
                            "dataSourceType" => "master",
                            "dataSource" => "module",
                            "relation" => "parent_id",
                            "isMultiple" => false,
                            "autocomplete" => true,
                            "config" => [
                                "name" => "name",
                                "value" => "id"
                            ],
                            "validation" => [
                                "isRequired" => false
                            ]
                        ]
                    ]
                ]
            ]
        ];
        file_put_contents($viewPath . '/edit_form.json', json_encode($editFormData, JSON_PRETTY_PRINT));
    }
}
