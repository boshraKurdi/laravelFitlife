<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\GoalPlan;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Plan::with(['media'])->get();
        return response()->json(['data' => $index]);
    }

    public function getPlanForGoals($ids)
    {
        $plans = Plan::query()->whereHas('goalPlan', function ($q)  use ($ids) {
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
            'duration' => $request->duration
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
        $show = $plan->load(['media']);
        return response()->json($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $update = $plan->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'muscle' => $request->muscle,
            'muscle_ar' => $request->muscle_ar,
            'duration' => $request->duration
        ]);
        if ($request->media) {
            $plan->addMediaFromRequest('media')->toMediaCollection('plans');
        }
        return response()->json(['data' => 'update successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }

    public function exercise(Request $request, $plan, $day, $week)
    {
        $arr = [];


        $x = [0];
        $y = [0];
        $date = [];
        $message = '';
        $exe  = [];
        $firstWeek = [];
        $secondWeek = [];
        $CountGetdate = Target::where('user_id', auth()->id())->whereHas('goalPlan', function ($q) use ($plan) {
            $q->where('plan_id', $plan);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $date = $Getdate->users->date;
                $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
                $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
                $today = Carbon::today();
                //get count target plan and user
                $count = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->count();
                $totalRate = intval(($count / 42) * 100);
                $countWeekOne = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->where(function ($query) use ($firstWeek) {
                        foreach ($firstWeek as $d) {
                            $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
                $countWeekTwo = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->where(function ($query) use ($secondWeek) {
                        foreach ($secondWeek as $d) {
                            $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
                $countDay = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->whereDate('updated_at', $today)
                    ->count();

                $totalRateDay = intval(($countDay / 3) * 100);
                if ($request->type) {
                    if ($request->type === 'weekly') {
                        if ($request->number_week == 1) {
                            $arr = $firstWeek;
                        } else if ($request->number_week == 2) {
                            $arr = $secondWeek;
                        }
                        foreach ($arr as $d) {
                            $countDayTotal = Target::where('user_id', auth()->id())
                                ->whereHas('goalPlan', function ($q) use ($plan) {
                                    $q->where('plan_id', $plan);
                                })->where('check', '!=', 0)->whereDate('updated_at', $d->date) > count();
                            $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                            array_push($x,  $d->date);
                            array_push($y, $totalRateDayTotal);
                        }
                    } else {
                        array_push($x, 1);
                        array_push($y, $totalRateWeekOne);
                        array_push($x, 2);
                        array_push($y, $totalRateWeekTwo);
                    }
                }
                //get exe
                $exe =  Plan::where('id', $plan)->with(['exercise' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week);
                }, 'exercise.media', 'targets' => function ($q) use ($today) {
                    $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                }, 'targets.users', 'targets.users.date'])->first();
                $exe->totalRate =   $totalRate;
                $exe->totalRateDay =   $totalRateDay;
                $exe->totalRateWeekOne =   $totalRateWeekOne;
                $exe->totalRateWeekTwo =   $totalRateWeekTwo;
                $exe->x = $x;
                $exe->y = $y;
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $exe, 'message' => $message]);
    }

    public function getSleep()
    {
        $today = Carbon::today();
        $sleep =  Plan::where('id', 14)->with(['targets' => function ($q) use ($today) {
            $q->where('user_id', auth()->id())->where('sleep', '!=', null)->whereDate('targets.created_at', $today);
        }])->first();

        return response()->json($sleep);
    }

    public function getExerciseForPlan($plan)
    {
        $exe =  Plan::where('id', $plan)->with(['exercise', 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }


    public function meal($day, $week, Request $request)
    {
        $exe = [];
        $today = Carbon::now();
        $message = '';
        $planId = 0;
        $check = Target::where('user_id', auth()->id())->count();
        if ($check) {
            $target = GoalPlan::whereHas('users', function ($q) {
                $q->where('user_id', auth()->id());
            })->whereHas('targets', function ($q) {
                $q->where('active', true);
            })->get();
            if (count($target)) {
                $plan_id = Plan::whereHas('goals', function ($q) use ($target) {
                    $q->where('goal_id', $target[0]->goal_id);
                })
                    ->where('type', 'food')
                    ->get();
                $planId = $plan_id[0]->id;
                if ($request->breakfast) {
                    $exe =  Plan::where('id', $plan_id[0]->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('breakfast', 1);
                    }, 'meal.media'])->get();
                } else if ($request->lunch) {
                    $exe =  Plan::where('id', $plan_id[0]->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('lunch', 1);
                    }, 'meal.media'])->get();
                } else {
                    $exe =  Plan::where('id', $plan_id[0]->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('dinner', 1);
                    }, 'meal.media'])->get();
                }
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json([
            'data' => $exe,
            'id' => $plan_id,
            'message' => $message
        ]);
    }


    public function getUserPlans()
    {
        $message = '';
        $newIndex = [];
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateWithActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateWithActive) {
                $plans = Plan::query()
                    ->whereHas('targets', function ($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->where('type', '!=', 'food')

                    ->with(['targets' => function ($query) {
                        $query->where('user_id', auth()->id());
                    }, 'media', 'targets.goalPlan'])
                    ->get();
                $newIndex = $plans->map(function ($i) {
                    $count = Target::where('user_id', auth()->id())
                        ->whereHas('goalPlan', function ($q) use ($i) {
                            $q->where('plan_id', $i->id);
                        })->where('check', '!=', 0)
                        ->count();
                    $totalRate = intval(($count / 42) * 100);
                    $i->totalRate = $totalRate;
                    return $i;
                });
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $newIndex, 'message' => $message]);
    }


    public function showPlan(Request $request, $id)
    {
        $arr = [];
        $arrDay = [];
        $date = [];
        $message = '';
        $today = Carbon::today();
        $show = [];
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $date = $Getdate->users->date;
                $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
                $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
                $count = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->count();
                $totalRate = intval(($count / 42) * 100);
                $countWeekOne = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->where(function ($query) use ($firstWeek) {
                        foreach ($firstWeek as $d) {
                            $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
                $countWeekTwo = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->where(function ($query) use ($secondWeek) {
                        foreach ($secondWeek as $d) {
                            $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
                $countDay = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->WhereDate('updated_at', $today)->count();

                $totalRateDay = intval(($countDay / 3) * 100);
                if ($request->type) {
                    array_push($arrDay, ['x' => 0, 'y' => 0]);
                    if ($request->type === 'weekly') {
                        if ($request->number_week == 1) {
                            $arr = $firstWeek;
                        } else if ($request->number_week == 2) {
                            $arr = $secondWeek;
                        }
                        foreach ($arr as $d) {
                            $countDayTotal = Target::where('user_id', auth()->id())
                                ->whereHas('goalPlan', function ($q) use ($id) {
                                    $q->where('plan_id', $id);
                                })->where('check', '!=', 0)->whereDate('updated_at', $d->date)->count();
                            $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                            array_push($arrDay, ['x' => $d->date, 'y' => $totalRateDayTotal]);
                        }
                    } else {
                        array_push($arrDay, ["x" => 'first week', 'y' => $totalRateWeekOne]);
                        array_push($arrDay, ['x' => "scound week", 'y' => $totalRateWeekTwo]);
                    }
                }
                $show = Plan::where('id', $id)
                    ->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->whereDate('targets.created_at', $today)->where('check', '!=', 0);
                    }, 'targets.users', 'targets.users.date', 'media'])
                    ->first();
                $show->date =   $date;
                $show->totalRate =   $totalRate;
                $show->totalRateDay =   $totalRateDay;
                $show->totalRateWeekOne =   $totalRateWeekOne;
                $show->totalRateWeekTwo =   $totalRateWeekTwo;
                $show->arrDay = $arrDay;
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }
        return response()->json(['data' => $show, 'message' => $message]);
    }

    // public function showPlan(Plan $Plan)
    // {
    //     $i = $Plan->load(['plan', 'plan.media',  'level', 'exercise', 'exercise.media']);
    //     return response()->json($i);
    // }
}
