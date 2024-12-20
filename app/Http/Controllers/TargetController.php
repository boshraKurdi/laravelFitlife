<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Http\Requests\StoreTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\User;
use Carbon\Carbon;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTargetRequest $request)
    {
        $count = Target::where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->count();
        $rate = intval(($count / 30) * 100);
        $today = Carbon::today();
        $UpdatedToday = Target::whereDate('updated_at', $today)->where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->get();
        if (count($UpdatedToday)) {
            $target = Target::where('id', $UpdatedToday[0]->id)->update([
                'calories' => $request->calories + $UpdatedToday[0]->calories,
                'rate' => $rate,
            ]);
        } else {
            $target = Target::create([
                'user_id' => auth()->id(),
                'goal_plan_level_id' => $request->goal_plan_level_id,
                'calories' => $request->calories,
                'rate' => $rate,
            ]);
        }
        return response()->json($target);
    }

    /**
     * Display the specified resource.
     */
    public function show(Target $target)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTargetRequest $request, Target $target)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        //
    }
}
