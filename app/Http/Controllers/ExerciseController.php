<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Exercise::query()->with('plan')->get();
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request)
    {
        $store = Exercise::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'duration' => $request->duration,
            'counter' => $request->counter,
            'calories' => $request->calories
        ]);
        if ($request->media) {
            $store->addMediaFromRequest('media')->toMediaCollection('exercises');
        }
        if ($request->steps) {
            foreach ($request->steps as $step) {
                $store->steps()->create([
                    'content' => $step['content'],
                    'content_ar' => $step['content_ar']
                ]);
            }
        }

        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        $exe = $exercise->load(['media', 'steps', 'steps.media']);
        return response()->json($exe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise)
    {
        $exercise->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'duration' => $request->duration,
            'counter' => $request->counter,
            'calories' => $request->calories
        ]);
        if ($request->media) {
            $exercise->addMediaFromRequest('media')->toMediaCollection('exercises');
        }
        if ($request->steps) {
            $exercise->steps()->delete();
            foreach ($request->steps as $step) {
                $exercise->steps()->create([
                    'content' => $step['content'],
                    'content_ar' => $step['content_ar']
                ]);
            }
        }
        return response()->json(['data' => 'update exercise successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return response()->json('exercise been deleted successfully');
    }
}
