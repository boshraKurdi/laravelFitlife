<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Meal::query()->with(['media', 'category'])->get();
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request)
    {
        $store = Meal::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'components' => $request->components,
            'components_ar' => $request->components_ar,
            'prepare' => $request->prepare,
            'calories' => $request->calories,
            'prepare_ar' => $request->prepare_ar,
            'category_id' => $request->category_id
        ]);
        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        $show = $meal->load(['media', 'category']);
        return response()->json($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $meal->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'components' => $request->components,
            'components_ar' => $request->components_ar,
            'prepare' => $request->prepare,
            'calories' => $request->calories,
            'prepare_ar' => $request->prepare_ar,
            'category_id' => $request->category_id
        ]);
        return response()->json(['data' => 'update meal successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();
        return response()->json('meal been deleted successfully');
    }
}
