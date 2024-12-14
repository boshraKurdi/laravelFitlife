<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Target;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = Goal::query()->with('media')->get();
        return response()->json(['data' => $goals]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        $goal = Goal::query()->create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'calories_min' => $request->calories_min,
            'calories_max' => $request->calories_max,
            'duration' => $request->duration
        ]);
        if ($request->media) {
            $goal->addMediaFromRequest('media')->toMediaCollection('goals');
        }
        if ($request->PlanLevel) {
            $goal->PlanLevel()->attach($request->PlanLevel);
        }
        return response()->json($goal);
    }

    /**
     * Display the specified resource.
     */
    public function show(Goal $goal,  $id)
    {
        $show = $goal->load('media');
        $g = $goal->id;
        $d = Target::where('user_id', $id)->whereHas('goalPlanLevel', function ($q) use ($g) {
            $q->where('goal_id', $g);
        })->count();
        $e = Target::where('user_id', $id)->count();
        $show->count = $d;
        $show->countAll = $e;
        return response()->json($show);
    }

    public function showGoal(Goal $goal, $id)
    {
        return response()->json(['data' => Goal::with(['media',  'PlanLevel', 'PlanLevel.plan.media', 'PlanLevel.plan', 'PlanLevel.level'])->where('id', $id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
            'title_ar' => $request->title_ar,
            'description_ar' => $request->description_ar,
            'calories_min' => $request->calories_min,
            'calories_max' => $request->calories_max,
            'duration' => $request->duration
        ]);

        if ($request->PlanLevel) {
            $goal->PlanLevel()->sync($request->PlanLevel);
        }
        if ($request->media) {
            $goal->addMediaFromRequest('media')->toMediaCollection('goals');
        }
        return response()->json(['data' => 'update successfully!']);
    }

    public function getUserGoals()
    {
        $goals = Goal::query()
            ->whereHas('targets', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['targets' => function ($query) {
                $query->where('user_id', auth()->id());
            }, 'media'])
            ->get();
        return response()->json(['data' => $goals]);
    }

    public function getPlanForGoal(Goal $goal)
    {

        $plans = Goal::with(['PlanLevel', 'PlanLevel.plan'])->where('id', 2)->with('PlanLevel')->get();
        return response()->json($plans);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();
        return response()->json('Goal been deleted successfully');
    }
}
