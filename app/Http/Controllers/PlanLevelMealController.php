<?php

namespace App\Http\Controllers;

use App\Models\PlanLevelMeal;
use App\Http\Requests\StorePlanLevelMealRequest;
use App\Http\Requests\UpdatePlanLevelMealRequest;

class PlanLevelMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanLevelMealRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanLevelMeal $planLevelMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanLevelMealRequest $request, PlanLevelMeal $planLevelMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanLevelMeal $planLevelMeal)
    {
        //
    }
}
