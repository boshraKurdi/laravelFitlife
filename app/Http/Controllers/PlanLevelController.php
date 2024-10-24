<?php

namespace App\Http\Controllers;

use App\Models\PlanLevel;
use App\Http\Requests\StorePlanLevelRequest;
use App\Http\Requests\UpdatePlanLevelRequest;
use Illuminate\Http\Request;

class PlanLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscleGroups = ['arm', 'pectoral', 'belly', 'thigh'];
        $results = array();

        foreach ($muscleGroups as $muscle) {
            $r = PlanLevel::whereHas('plan', function ($q) use ($muscle) {
                $q->where('muscle', $muscle);
            })
                ->with(['plan', 'level', 'plan.media'])->get();
            array_push($results, $r);
        }

        return response()->json($results);
    }

    public function exercise($planLevel)
    {
        $exe =  PlanLevel::where('id', $planLevel)->with('exercise')->get();
        return response()->json($exe);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanLevelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanLevel $planLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanLevelRequest $request, PlanLevel $planLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanLevel $planLevel)
    {
        //
    }
}
