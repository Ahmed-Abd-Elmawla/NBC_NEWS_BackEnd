<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "category_name" => 'required|unique:categories|max:25',
        ]);

        $category = Category::create([
            'category_name' => $request ->category_name,
        ]);

        return response()->json(['message' => 'Category created successfully', 'Category' => $category]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // dd($request);
        $request->validate([
            'category_name' => 'required|unique:categories,category_name,' . $category->id . '|max:25',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'Category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'category deleted successfully']);
    }
}
