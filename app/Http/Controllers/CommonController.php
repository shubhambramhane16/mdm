<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CommonController extends Controller
{
    public function subcategoryList(Request $request)
    {
        $categoryId = $request->input('category_id');
        if (!$categoryId) {
            return response()->json(['error' => 'Category ID is required'], 400);
        }
        $subcategories = Category::where('parent_id', $categoryId)->get();
        return response()->json($subcategories->isEmpty() ? [] : $subcategories);
    }

    // unitList
    public function unitList(Request $request)
    {
        $clientId = $request->input('client_id');
        if (!$clientId) {
            return response()->json(['error' => 'Client ID is required'], 400);
        }
        $units = \App\Models\ClientUnit::where('client_id', $clientId)->get();
        return response()->json($units->isEmpty() ? [] : $units);
    }
}
