<?php

namespace App\Http\Controllers;

use App\Models\PlanMeal;
use App\Http\Requests\StorePlanMealRequest;
use App\Http\Requests\UpdatePlanMealRequest;

class PlanMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanMealRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanMeal $planMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanMealRequest $request, PlanMeal $planMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanMeal $planMeal)
    {
        //
    }
}
