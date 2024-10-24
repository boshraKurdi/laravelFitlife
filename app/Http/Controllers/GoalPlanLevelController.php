<?php

namespace App\Http\Controllers;

use App\Models\GoalPlanLevel;
use App\Http\Requests\StoreGoalPlanLevelRequest;
use App\Http\Requests\UpdateGoalPlanLevelRequest;

class GoalPlanLevelController extends Controller
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
        $idsArray = explode(',', $ids);
        $targets = GoalPlanLevel::query()->whereIn('goal_id', $idsArray)->with(['planLevels.plan', 'planLevels.level', 'planLevels.plan.media', 'goals', 'users'])->get();
        return response()->json($targets);
    }


    public function getPlanForGoalsWithMuscle($ids)
    {
        $idsArray = explode(',', $ids);
        $muscleGroups = ['arm', 'pectoral', 'belly', 'thigh'];
        $targets = array();

        foreach ($muscleGroups as $muscle) {
            $r = GoalPlanLevel::query()->whereIn('goal_id', $idsArray)->whereHas('planLevels.plan', function ($q) use ($muscle) {
                $q->where('muscle', $muscle);
            })
                ->with(['planLevels.plan', 'planLevels.level', 'planLevels.plan.media', 'goals'])->get();
            array_push($targets, $r);
        }
        return response()->json($targets);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalPlanLevelRequest $request)
    {
        return response()->json('s');
    }

    /**
     * Display the specified resource.
     */
    public function show(GoalPlanLevel $goalPlanLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalPlanLevelRequest $request, GoalPlanLevel $goalPlanLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoalPlanLevel $goalPlanLevel)
    {
        //
    }
}
