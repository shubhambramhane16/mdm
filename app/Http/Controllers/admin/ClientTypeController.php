<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientType;
use DB;
use Validator;

class ClientTypeController extends Controller
{
    public function index()
    {
        try {
            $view_page_base_path = env('VIEW_ADMIN_BASE_PATH');
            $jsonPath = $view_page_base_path . "client-type/view_list.json";
            $json = json_decode(file_get_contents(resource_path($jsonPath)), true);

            if (!$json) {
                return redirect()->back()->withErrors(['JSON file not found or invalid.']);
            }

            $pageSettings = $this->pageSetting('index');
            $page_title = $pageSettings['page_title'];
            $page_description = $pageSettings['page_description'];
            $breadcrumbs = $pageSettings['breadcrumbs'];
            $status = request('status');
            if ($status == '0') {
                $status = '2';
            }
            $details = ClientType::when($status, function ($query) use ($status) {
                if ($status != '-1') {
                    $status = conditionalStatus($status);
                    $query->where('status', '=', $status);
                }
            })->orderBy('id', 'asc')->paginate(10);
            return view('admin.layout.data-view-master.list', compact('json','page_title', 'page_description', 'breadcrumbs',  'details'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function add(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                // Handle form submission logic here
                // dd($request->all());

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:255',
                ],
                [
                    'name.required' => 'Client Type is required.',
                    'code.required' => 'Code is required.',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                DB::beginTransaction();

                $array = [
                    'name' => strtoupper($request->name),
                    'code' => strtoupper($request->code),
                    'status' => $request->status ? 1 : 0,
                    'created_by' => auth()->user()->id,
                ];
                // check if client type already exists
                $existingClientType = ClientType::where('name', strtoupper($request->name))
                    ->orWhere('code', strtoupper($request->code))
                    ->first();

                if ($existingClientType) {
                    return redirect()->back()->withErrors(['Client Type or Code already exists.'])->withInput();
                }

                $clientType = new ClientType($array);
                $clientType->save();
                DB::commit();
                return redirect('admin/client-type/list')->with('success', 'ClientType added successfully.');
            }

            $view_page_base_path = env('VIEW_ADMIN_BASE_PATH');
            $jsonPath = $view_page_base_path . "client-type/add_form.json";
            $json = json_decode(file_get_contents(resource_path($jsonPath)), true);

            if (!$json) {
                return redirect()->back()->withErrors(['JSON file not found or invalid.']);
            }
            $pageSettings = $this->pageSetting('add');
            $page_title =  $pageSettings['page_title'];
            $page_description = $pageSettings['page_description'];
            $breadcrumbs = $pageSettings['breadcrumbs'];
            return view('admin.layout.form-master.add', compact('json','page_title', 'page_description', 'breadcrumbs'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {

            if ($request->isMethod('post')) {
                // Handle form submission logic here
            }

            $details = ClientType::where('id', $id)->first();
            if ($details) {
                $view_page_base_path = env('VIEW_ADMIN_BASE_PATH');
                $jsonPath = $view_page_base_path . "client-type/edit_form.json";
                $json = json_decode(file_get_contents(resource_path($jsonPath)), true);

                if (!$json) {
                    return redirect()->back()->withErrors(['JSON file not found or invalid.']);
                }

                $pageSettings = $this->pageSetting('edit');
                $page_title =  $pageSettings['page_title'];
                $page_description = $pageSettings['page_description'];
                $breadcrumbs = $pageSettings['breadcrumbs'];
                return view('admin.layout.form-master.edit', compact('page_title', 'page_description', 'breadcrumbs', 'details', 'json'));
            } else {
                return redirect()->back()->withErrors(['ClientType details not exist.']);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                $item = ClientType::find($id);
                if ($item && $item->delete()) {
                    DB::commit();
                    return redirect()->back()->with('success', 'ClientType deleted successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to delete. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'ClientType details not found.');
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
                $newStatus = ($status == 1) ? 0 : 1;
                $updateArr = [
                    'status' => $newStatus,
                ];
                $item = ClientType::find($id);
                if ($item) {
                    $item->update($updateArr);
                    DB::commit();
                    return redirect()->back()->with('success', 'ClientType status updated successfully.');
                } else {
                    return redirect()->back()->with('error', 'ClientType details not found.');
                }
            } else {
                return redirect()->back()->with('error', 'ClientType details not found.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pageSetting($action, $dataArray = [])
    {
        $data = [];

        if ($action == 'edit') {
            $data['page_title'] = 'ClientType Management';
            $data['page_description'] = 'Edit ClientType';
            $data['breadcrumbs'] = [
                [
                    'title' => 'ClientType Management',
                    'url' => url('admin/client-type/list'),
                ]
            ];
            if (isset($dataArray['title']) && !empty($dataArray['title'])) {
                $data['breadcrumbs'][] = [
                    'title' => $dataArray['title'],
                    'url' => '',
                ];
            }
            return $data;
        }

        if ($action == 'add') {
            $data['page_title'] = 'ClientType Management';
            $data['page_description'] = 'Add New ClientType';
            $data['breadcrumbs'] = [
                [
                    'title' => 'ClientType Management',
                    'url' => url('admin/client-type/list'),
                ],
                [
                    'title' => 'Add a New ClientType',
                    'url' => '',
                ],
            ];
            return $data;
        }

        if ($action == 'index') {
            $data['page_title'] = 'ClientType Management';
            $data['page_description'] = 'ClientType Management';
            $data['breadcrumbs'] = [
                [
                    'title' => 'ClientType Management',
                    'url' => '',
                ],
            ];
            return $data;
        }

        return $data;
    }
}
