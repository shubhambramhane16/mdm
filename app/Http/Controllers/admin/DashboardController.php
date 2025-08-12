<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Image;
use File;

class DashboardController extends Controller
{
    public function dashboard()
    {

        try {
            $page_title = 'Dashboard';
            $page_description = '';
            $breadcrumbs = [
                // [
                //     'title' => 'Dashboard',
                //     'url' => '',
                // ],
            ];
            $status = request('status');
            if ($status == '0') {
                $status = '2';
            }


            // dd($ordersMonthWiseData);
            return view('admin.pages.dashboard.list', compact('page_title', 'page_description', 'breadcrumbs', ));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
