<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view("index", ["categories" => $categories]);
    }

    // public function show($id)
    // {
    //     $category = Category::find($id);

    //     if (!$category) {
    //         return response()->json(['message' => 'Category not found'], 404);
    //     }

    //     return response()->json($category);
    // }
}
