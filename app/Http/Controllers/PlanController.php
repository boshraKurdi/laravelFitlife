<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getPlanForGoals($ids)
    {
        $plans = Plan::query()->with('levels')->whereHas('goalPlanLevel', function ($q)  use ($ids) {
            $q->where('goal_id', $ids);
        })
            // ->with(['levels', 'goalPlanLevel' => function ($q) use ($ids) {
            //     $q->where('goal_id', $ids);
            // }])
            ->get();

        return response()->json($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        $plan = Plan::query()->create([
            'title' => $request->title,
            'description' => $request->description,
            'muscle' => $request->muscle,
            'duration' => "1 month"
        ]);
        if ($request->media) {
            $plan->addMediaFromRequest('media')->toMediaCollection('plans');
        }
        return response()->json($plan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $update = $plan->update([
            'title' => $request->title
        ]);
        if ($request->media) {
            $plan->addMediaFromRequest('media')->toMediaCollection('plans');
        }
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }
}
