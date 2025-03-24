<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        $index = Category::get();
        if ($id) {
            $index = Category::where('id', $id)->get();
        }
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $store = Category::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
        ]);
        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(['data' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
        ]);
        return response()->json(['data' => 'update category successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['data' => 'delete category successfully!']);
    }
}
