<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Step;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        $index = Exercise::query()->with('plan')->get();
        if ($id) {
            $index = Exercise::query()->where('id', $id)->with('plan')->get();
        }
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request)
    {
        $exercise = Exercise::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'duration' => $request->duration,
            'counter' => $request->counter,
            'type' => $request->type,
            'calories' => $request->calories
        ]);
        if ($request->media) {
            $exercise->addMediaFromRequest('media')->toMediaCollection('exercises');
        }
        if ($request->video) {
            $exercise->addMediaFromRequest('video')->toMediaCollection('exercises');
        }
        if ($request->svg) {
            $exercise->addMediaFromRequest('svg')->toMediaCollection('exercises');
        }
        if ($request->has('steps')) {
            foreach ($request->steps as $index => $stepData) {


                $step = Step::create([
                    'exercise_id' => $exercise->id,
                    'content' => $stepData['content'],
                    'content_ar' => $stepData['content_ar'],
                ]);
                if ($request->hasFile("media_steps.$index")) {
                    $step->addMediaFromRequest("media_steps.$index")->toMediaCollection('steps');
                }
            }
        }

        return response()->json($exercise);
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
            'type' => $request->type,
            'calories' => $request->calories
        ]);
        if ($request->media) {
            $exercise->addMediaFromRequest('media')->toMediaCollection('exercises');
        }
        if ($request->has('steps')) {
            foreach ($request->steps as $index => $stepData) {
                // إنشاء أو تحديث الخطوة
                $step = Step::updateOrCreate(
                    [
                        'exercise_id' => $exercise->id, // البحث عن السجل حسب التمرين
                        'id' => $stepData['id'] ?? null, // تحديث إذا كان هناك ID، وإلا يتم الإنشاء
                    ],
                    [
                        'content' => $stepData['content'],
                        'content_ar' => $stepData['content_ar'],
                    ]
                );

                // التحقق مما إذا تم رفع صورة جديدة
                if ($request->hasFile("media_steps.$index")) {
                    // حذف الصورة القديمة (إذا كانت موجودة)
                    $step->clearMediaCollection('steps');

                    // إضافة الصورة الجديدة
                    $step->addMediaFromRequest("media_steps.$index")->toMediaCollection('steps');
                }
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
