<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\BankDetails;
use DB;
use Validator;
use Image;
use File;

class SettingsController extends Controller
{
    public function index(Request $request)
    {

        try {
            if ($request->isMethod('post')) {
                // dd($request->all());
                $validator = Validator::make($request->all(), [
                    'registered_office_address' => 'required',
                    'office_address' => 'required',
                    'phone_number' => 'required',
                    'email_id' => 'required',
                    'whatsapp' => 'required',
                    'customer_care' => 'required',
                    // 'gst_number' => 'required',
                ], [
                    'registered_office_address.required' => 'Registered office address is required.',
                    'office_address.required' => 'Office address is required.',
                    'phone_number.required' => 'Phone number is required.',
                    'email_id.required' => 'Email id is required.',
                    'whatsapp.required' => 'Whatsapp is required.',
                    'customer_care.required' => 'Customer care is required.',
                    'gst_number.required' => 'Gst number is required.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                DB::beginTransaction();


                $array = [
                    'company_name' => $request->company_name,
                    'website_url' => $request->website_url,
                    'registered_office_address' => $request->registered_office_address,
                    'registered_office_address2' => $request->registered_office_address2,
                    'office_address2' => $request->office_address2,
                    'office_address' => $request->office_address,
                    'phone_number' => $request->phone_number,
                    'email_id' => $request->email_id,
                    'whatsapp' => $request->whatsapp,
                    'customer_care' => $request->customer_care,
                    'gst_number' => $request->gst_number,
                    'pan_number' => $request->pan_number,
                    'timezone' => $request->timezone,
                    'currency' => $request->currency,
                    'prior_hours_preferred_time' => $request->prior_hours_preferred_time,
                    'updated_by' => auth()->user()->id,


                ];
                if (request('setting_id')) {
                    $setting_id = request('setting_id');
                } else {
                    $setting_id = null;
                    $array['created_by'] =auth()->user()->id;
                }
                $response = Setting::UpdateOrCreate(['id' => $setting_id], $array);
                DB::commit();
                return redirect('admin/settings')->with('success', 'Settings details updated successfully.');
            }

            $page_title = 'Admin Settings';
            $page_description = '';
            $breadcrumbs = [
                [
                    'title' => 'Settings',
                    'url' => '',
                ]
            ];


            $status = request('status');
            if ($status == '0') {
                $status = '2';
            }
            $details  = Setting::orderBy('id', 'desc')->first();
            $bank_details = BankDetails::first();
            return view('admin.pages.settings.settings', compact('page_title', 'page_description', 'breadcrumbs', 'details', 'bank_details'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    // admin/settings/bank
    public function bank(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                // dd($request->all());

                $validator = Validator::make($request->all(), [
                    'bank_name' => 'required',
                    'account_number' => 'required',
                    'account_holder_name' => 'required',
                    'bank_address' => 'required',
                    'ifsc_code' => 'required',
                    'branch_name' => 'required',
                ], [
                    'bank_name.required' => 'Bank name is required.',
                    'account_number.required' => 'Account number is required.',
                    'ifsc_code.required' => 'IFSC code is required.',
                    'branch_name.required' => 'Branch name is required.',
                    'account_holder_name.required' => 'Account holder name is required.',
                    'bank_address.required' => 'Bank address is required.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                DB::beginTransaction();

                $array = [
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'branch_name' => $request->branch_name,
                    'account_holder_name' => $request->account_holder_name,
                    'bank_address' => $request->bank_address,
                    'updated_by' => auth()->user()->id,
                ];
                if (request('bank_id')) {
                    $id = request('bank_id');
                } else {
                    $id = null;
                    $array['created_by'] = auth()->user()->id;
                }
                BankDetails::UpdateOrCreate(['id' => $id], $array);
                DB::commit();
                return redirect('admin/settings')->with('success', 'Bank details updated successfully.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function master()
    {
        try {
        $page_title = 'Settings Master';
        $page_description = '';
        $breadcrumbs = [
            [
            'title' => 'Settings Master',
            'url' => '',
            ]
        ];
        return view('admin.pages.settings.master', compact('page_title', 'page_description', 'breadcrumbs'));
        } catch (\Exception $e) {
        dd($e);
        return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
