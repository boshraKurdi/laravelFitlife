<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Ingredient;
use App\Models\PlanMeal;
use App\Services\GetDate;

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
            'category_id' => $request->category_id,
            'breakfast' => $request->period == 'breakfast' ? 1 : 0,
            'dinner' => $request->period == 'dinner' ? 1 : 0,
            'lunch' => $request->period == 'lunch' ? 1 : 0,
            'snacks' => $request->period == 'snacks' ? 1 : 0,

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
    public function showPlanMeal(Meal $meal)
    {
        $show = $meal->load(['media', 'category', 'ingredients', 'ingredients.media']);
        $other = Meal::whereHas('category', function ($q) use ($meal) {
            $q->where('id', $meal->category->id);
        })->with('media')->get();
        $show->other = $other;
        return response()->json($show);
    }
    public function show(Meal $meal)
    {
        $show = $meal->load(['media', 'category', 'ingredients', 'ingredients.media']);
        $other = Meal::whereHas('category', function ($q) use ($meal) {
            $q->where('id', $meal->category->id);
        })->with('media')->get();
        $dayd = GetDate::GetDate(2);
        $day = $dayd['day'];
        $week = $dayd['week'];
        $time = PlanMeal::where('meal_id',  $show->id)->where('day', $day)->where('week', $week)->get();
        $show->other = $other;
        $show->pivot = ['breakfast' => $show->breakfast, 'lunch' => $show->lunch];
        $show->time = $time;
        return response()->json(['data' => $show]);
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
            'category_id' => $request->category_id,
            'breakfast' => $request->period == 'breakfast' ? 1 : 0,
            'dinner' => $request->period == 'dinner' ? 1 : 0,
            'lunch' => $request->period == 'lunch' ? 1 : 0,
            'snacks' => $request->period == 'snacks' ? 1 : 0,
        ]);
        if ($request->hasFile("media")) {
            $meal->addMediaFromRequest("media")->toMediaCollection('meals');
        }
        if ($request->has('ingredients')) {
            Ingredient::where("meal_id", $meal->id)->delete();
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
