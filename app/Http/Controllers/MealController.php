<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Ingredient;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {

        $index = Meal::query()->with(['media', 'category'])->get();

        if ($id) {
            $index = Meal::query()->where('id', $id)->with(['media', 'category'])->get();
        }
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request)
    {
        $meal = Meal::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'components' => $request->components,
            'components_ar' => $request->components_ar,
            'prepare' => $request->prepare,
            'carbohydrates' => $request->carbohydrates,
            'proteins' => $request->proteins,
            'fats' => $request->fats,
            'calories' => $request->calories,
            'prepare_ar' => $request->prepare_ar,
            'category_id' => $request->category_id
        ]);
        if ($request->hasFile("media")) {
            $meal->addMediaFromRequest("media")->toMediaCollection('meals');
        }

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $index => $stepData) {


                $step = Ingredient::create([
                    'meal_id' => $meal->id,
                    'name' => $stepData['name'],
                    'name_ar' => $stepData['name_ar'],
                    'num' => $stepData['num'],
                ]);
                if ($request->hasFile("media_ingredients") && isset($request->file('media_ingredients')[$index])) {
                    $step->addMedia($request->file('media_ingredients')[$index])->toMediaCollection('ingredients');
                }
            }
        }

        return response()->json($meal);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        $show = $meal->load(['media', 'category', 'ingredients', 'ingredients.media']);
        $other = Meal::whereHas('category', function ($q) use ($meal) {
            $q->where('id', $meal->category->id);
        })->with('media')->get();
        $show->other = $other;
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
            'carbohydrates' => $request->carbohydrates,
            'proteins' => $request->proteins,
            'fats' => $request->fats,
            'calories' => $request->calories,
            'prepare_ar' => $request->prepare_ar,
            'category_id' => $request->category_id
        ]);
        if ($request->hasFile("media")) {
            $meal->addMediaFromRequest("media")->toMediaCollection('meals');
        }

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $index => $stepData) {
                $i = Ingredient::updateOrCreate(
                    ['meal_id' => $meal->id, 'name' => $stepData['name']],
                    [
                        'name_ar' => $stepData['name_ar'],
                        'num' => $stepData['num'],
                    ]
                );

                if ($request->hasFile("media_ingredients.$index")) {
                    $i->clearMediaCollection('ingredients');
                    $i->addMediaFromRequest("media_ingredients.$index")->toMediaCollection('ingredients');
                }
            }
        }

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
