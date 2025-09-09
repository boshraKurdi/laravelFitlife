<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMealRequest;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Exercise;
use App\Models\GoalPlan;
use App\Models\PlanExercise;
use App\Models\PlanMeal;
use Illuminate\Support\Facades\Auth;
use App\Models\Target;
use App\Observers\GoalPlanObserver;
use App\Services\GetDate;
use App\Services\IsHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        $index = Plan::with(['media', 'exercise'])->get();

        if ($id) {
            $index = Plan::where('id', $id)->with(['media'])->get();
        }
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
        $plan = Plan::create([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'muscle' => $request->muscle ? $request->muscle : "a",
            'muscle_ar' => $request->muscle_ar ? $request->muscle_ar : "a",
            'duration' => $request->duration,
            'water' => $request->water,
            'sleep' => $request->sleep,
            'type' => $request->type,
            'type_ar' => $request->type_ar
        ]);
        $allDays = json_decode($request->a, true);
        if ($request->type === 'food') {
            foreach ($allDays as $index => $meals) {
                foreach ($meals as $id) {
                    PlanMeal::create([
                        'meal_id' => $id,
                        'plan_id' => $plan->id,
                        'day' => ($index % 7) + 1,
                        'week' => intval($index / 7) + 1,
                    ]);
                }
            }
        } else if ($request->type !== 'water' && $request->type !== 'food' && $request->type !== 'sleep') {


            foreach ($allDays as $index => $exercise_ids) {
                foreach ($exercise_ids as $id) {
                    PlanExercise::create([
                        'exercise_id' => $id['key'],
                        'plan_id' => $plan->id,
                        'day' => ($index % 7) + 1,
                        'week' => intval($index / 7) + 1,
                    ]);
                }
            }
        }
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
        $show = $plan->load(['media', 'exercise', 'exercise.media', 'meal']);
        return response()->json($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'muscle' => $request->muscle,
            'muscle_ar' => $request->muscle_ar,
            'duration' => $request->duration,
            'water' => $request->water,
            'sleep' => $request->sleep,
            'type' => $request->type,
            'type_ar' => $request->type_ar
        ]);
        $allDays = json_decode($request->a, true);
        if ($request->type === 'food') {
            PlanMeal::where('plan_id', $plan->id)->delete();
            foreach ($allDays as $index => $meal_ids) {
                foreach ($meal_ids as $id) {
                    PlanMeal::create([
                        'meal_id' => $id,
                        'plan_id' => $plan->id,
                        'day' => ($index % 7) + 1,
                        'week' => intval($index / 7) + 1,
                    ]);
                }
            }
        } else if ($request->type !== 'water' && $request->type !== 'food' && $request->type !== 'sleep') {
            PlanExercise::where('plan_id', $plan->id)->delete();
            foreach ($allDays as $index => $exercise_ids) {
                foreach ($exercise_ids as $id) {
                    PlanExercise::create([
                        'exercise_id' => $id,
                        'plan_id' => $plan->id,
                        'day' => ($index % 7) + 1,
                        'week' => intval($index / 7) + 1,
                    ]);
                }
            }
        }
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
        $plan->delete();
        return response()->json('delete');
    }

    public function exercise(Request $request, $plan)
    {
        $arr = [];
        $x = [0];
        $y = [0];
        $date = [];
        $message = '';
        $exe  = [];
        $type = "error";
        $firstWeek = [];
        $secondWeek = [];
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->whereHas('goalPlan', function ($q) use ($plan) {
            $q->where('plan_id', $plan);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                $week = $dayd['week'];
                if ($day > 0) {
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
                    $is = IsHoliday::IsHoliday();

                    if (!$is) {
                        $exe =  Plan::where('id', $plan)->with(['exercise' => function ($q) use ($day, $week) {
                            $q->where('day', $day)->where('week', $week);
                        }, 'exercise.media', 'targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'targets.users.date'])->first();
                    } else {
                        $exe =  Plan::where('id', $plan)->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'targets.users.date'])->first();
                        $e = Exercise::where('type', 'holiday')->with('media')->get();
                        $exe->exercise = $e;
                    }

                    $exe->totalRate =   $totalRate;
                    $exe->totalRateDay =   $totalRateDay;
                    $exe->totalRateWeekOne =   $totalRateWeekOne;
                    $exe->totalRateWeekTwo =   $totalRateWeekTwo;
                    $exe->x = $x;
                    $exe->y = $y;
                    $type = "success";
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }
            } else {
                $message = app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "This plan is not available to you or is not intended for exercise.😊" : "هذه الخطة غير متاحة لك وليست مخصصة لممارسة الرياضة.😊";
        }

        return response()->json(['data' => $exe, 'type' => $type,  'message' => $message]);
    }

    public function getSleep()
    {
        $today = Carbon::today();
        $message = '';
        $sleep = '';
        $arrDay = [];

        $type = 'error';
        $check = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($check) {
            $checkAtice = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($checkAtice) {
                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                if ($day > 0) {
                    $sleep =  Plan::where('id', 14)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('sleep', '!=', null)->whereDate('targets.created_at', $today);
                    }])->first();

                    $SleepForDay = Target::selectRaw('DATE(created_at) as x, SUM(sleep) as y')
                        ->whereHas('goalPlan.plan', function ($q) {
                            $q->where('type', 'sleep');
                        })
                        ->where('user_id', auth()->id())
                        ->groupBy('x')
                        ->get();

                    foreach ($SleepForDay as $data) {
                        array_push($arrDay, ['x' => $data->x, 'y' => intval($data->y)]);
                    }
                    $sleep->arrDay = $arrDay;
                    $type = 'success';
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }
            } else {
                $message = app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ?  "If you want to see more details please register with this goal and don't forget to check your email address 😉😉" : "إذا كنت تريد رؤية المزيد من التفاصيل يرجى التسجيل بهذا الهدف ولا تنسى التحقق من عنوان بريدك الإلكتروني 😉😉";
        }


        return response()->json(['data' => $sleep, 'message' => $message, 'type' => $type]);
    }

    public function getWater()
    {
        $today = Carbon::today();
        $message = '';
        $water = '';
        $arrDay = [];
        $type = 'error';
        $check = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($check) {
            $checkAtice = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($checkAtice) {
                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                $week = $dayd['week'];
                if ($day > 0) {
                    $water =  Plan::where('id', 15)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('water', '!=', null)->whereDate('targets.created_at', $today);
                    }])->first();

                    $WaterForDay = Target::selectRaw('DATE(created_at) as x, SUM(water) as y')
                        ->whereHas('goalPlan.plan', function ($q) {
                            $q->where('type', 'water');
                        })
                        ->where('user_id', auth()->id())
                        ->groupBy('x')
                        ->get();

                    foreach ($WaterForDay as $data) {
                        array_push($arrDay, ['x' => $data->x, 'y' => floatval($data->y)]);
                    }
                    $water->arrDay = $arrDay;
                    $type = 'success';
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }
            } else {
                $message = app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "If you want to see more details please register with this goal and don't forget to check your email address 😉😉" : "إذا كنت تريد رؤية المزيد من التفاصيل يرجى التسجيل بهذا الهدف ولا تنسى التحقق من عنوان بريدك الإلكتروني 😉😉";
        }


        return response()->json(['data' => $water, 'message' => $message, 'type' => $type]);
    }

    public function getExerciseForPlan($plan)
    {
        $exe =  Plan::where('id', $plan)->with(['exercise', 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }

    public function meal_linda(GetMealRequest $request)
    {
        $exe = [];
        $message = '';
        $arrDay = [];
        $type = 'error';
        $allMeals =  Plan::where('id', 10)->with(['meal' => function ($q) {
            $q->where('day', 1)->where('week', 1);
        }, 'meal.media'])->get();
        if ($request->breakfast) {
            $exe =  Plan::where('id', 10)->with(['meal' => function ($q) {
                $q->where('day', 1)->where('week', 1)->where('breakfast', 1);
            }, 'meal.media'])->get();
        } else if ($request->lunch) {
            $exe =  Plan::where('id', 10)->with(['meal' => function ($q) {
                $q->where('day', 1)->where('week', 1)->where('lunch', 1);
            }, 'meal.media'])->get();
        } else {
            $exe =  Plan::where('id', 10)->with(['meal' => function ($q) {
                $q->where('day', 1)->where('week', 1)->where('dinner', 1);
            }, 'meal.media'])->get();
        }

        $exe[0]->allMeals = $allMeals;
        $exe[0]->arrDay = $arrDay;
        $type = 'success';


        return response()->json([
            'data' => $exe,
            'type' => $type,
            'message' => $message
        ]);
    }
    public function meal(GetMealRequest $request)
    {
        $exe = [];
        $today = Carbon::now();
        $message = '';
        $arrDay = [];
        $plan_id = 0;
        $type = 'error';
        $target = null;
        $check = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($check) {

            $target = Target::where('user_id', auth()->id())->where('active', 1)->with(['goalPlan', 'users.date'])->first();
            if ($target) {

                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                $week = $dayd['week'];
                if ($day > 0) {
                    $plan_id = Plan::whereHas('goals', function ($q) use ($target) {
                        $q->where('goal_id', $target->goalPlan->goal_id);
                    })
                        ->where('type', 'food')
                        ->first();
                    $allMeals =  Plan::where('id', 10)->with(['meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week);
                    }, 'meal.media'])->get();
                    if ($request->breakfast) {
                        $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                            $q->where('day', $day)->where('week', $week)->where('meals.breakfast', 1);
                        }, 'meal.media'])->get();
                    } else if ($request->lunch) {
                        $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                            $q->where('day', $day)->where('week', $week)->where('meals.lunch', 1);
                        }, 'meal.media'])->get();
                    } else if ($request->dinner) {
                        $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                            $q->where('day', $day)->where('week', $week)->where('meals.dinner', 1);
                        }, 'meal.media'])->get();
                    } else {
                        $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                        }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                            $q->where('day', $day)->where('week', $week)->where('meals.snacks', 1);
                        }, 'meal.media'])->get();
                    }

                    //get meal bars
                    $FoodForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                        ->whereHas('goalPlan.plan', function ($q) {
                            $q->where('type', 'food');
                        })
                        ->where('user_id', auth()->id())
                        ->groupBy('x')
                        ->get();

                    foreach ($FoodForDay as $data) {
                        array_push($arrDay, ['x' => $data->x, 'y' => intval($data->y)]);
                    }


                    $exe[0]->allMeals = $allMeals;
                    $exe[0]->arrDay = $arrDay;
                    foreach ($exe[0]->allMeals[0]->meal as $meal) {
                        $meal->pivot->breakfast = $meal->breakfast;
                        $meal->pivot->lunch = $meal->lunch;
                        $meal->pivot->dinner = $meal->dinner;
                        $meal->pivot->snacks = $meal->snacks;
                    }
                    foreach ($exe[0]->meal as $meal) {
                        $meal->pivot->breakfast = $meal->breakfast;
                        $meal->pivot->lunch = $meal->lunch;
                        $meal->pivot->dinner = $meal->dinner;
                        $meal->pivot->snacks = $meal->snacks;
                    }

                    $type = 'success';
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }
            } else {
                $message = app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "If you want to see more details please register with this goal and don't forget to check your email address 😉😉" : "إذا كنت تريد رؤية المزيد من التفاصيل يرجى التسجيل بهذا الهدف ولا تنسى التحقق من عنوان بريدك الإلكتروني 😉😉";
        }

        return response()->json([
            'data' => $exe,
            'date' => $target ? $target->users->date : null,
            'id' => $plan_id ? $plan_id->id : 0,
            'type' => $type,
            'message' => $message
        ]);
    }

    public function progress()
    {
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        $message = '';
        $data[] = (object) ['myGoal' => null, 'plans' => null, 'meals' => null, 'water' => null, 'sleep' => null];
        $type = 'error';
        $today = Carbon::today();
        if ($CountGetdate) {
            $CountGetdateWithActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateWithActive) {

                $type = 'success';
                // get rate

                $c = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', '!=', 'food')->where('type', '!=', 'sleep')->where('type', '!=', 'water');
                })->where('active', 1)->get();
                if (count($c)) {
                    $sumCalories = 0;
                    foreach ($c as $da) {
                        $sumCalories += $da->calories;
                    }
                }

                //get track exerice
                $cc = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', '!=', 'food')->where('type', '!=', 'sleep')->where('type', '!=', 'water');
                })->where('active', 1)->whereDate('created_at', $today)->where('check', '!=', 0)->count();



                //get my goal
                $goal = Target::where('user_id', auth()->id())->with('goalPlan.goals', 'goalPlan.goals.media')->first();
                $totalRate = ($sumCalories / $goal->goalPlan->goals->calories_max) * 100;
                $goal->goalPlan->goals->totalRate = intval($totalRate);
                $goal->goalPlan->goals->exercise = $cc;

                $dayd = GetDate::GetDate($goal->goalPlan->goals->duration);
                $day = $dayd['day'];
                $week = $dayd['week'];
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $dates = $Getdate->users->date;

                $weeks = collect($dates)->chunk(7)->values();

                $currentDate = Carbon::today()->toDateString();
                $currentWeekNumber = $weeks->search(function ($weekDays) use ($currentDate) {
                    return collect($weekDays)->contains(function ($d) use ($currentDate) {
                        return Carbon::parse($d->date)->toDateString() === $currentDate;
                    });
                });

                // get target plan
                if ($day > 0) {
                    $plans = Plan::query()
                        ->whereHas('targets', function ($q) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0);
                        })
                        ->where('type', '!=', 'food')
                        ->where('type', '!=', 'sleep')
                        ->where('type', '!=', 'water')

                        ->with(['targets' => function ($query) {
                            $query->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', Carbon::today());
                        }, 'media', 'targets.goalPlan', 'exercise' => function ($query) use ($day, $week) {
                            $query->where('day', $day)->where('week', $week);
                        }, 'exercise.media'])
                        ->get();
                    // get food
                    $food = Plan::query()
                        ->whereHas('targets', function ($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->where('type', 'food')
                        ->with(['targets' => function ($query) {
                            $query->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', Carbon::today());
                        }, 'media', 'targets.goalPlan', 'meal' => function ($query) use ($day, $week) {
                            $query->where('day', $day)->where('week', $week);
                        }, 'meal.media'])
                        ->get();
                    // get water
                    $water = Plan::query()
                        ->whereHas('targets', function ($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->where('type', 'water')

                        ->with(['targets' => function ($query) use ($today) {
                            $query->where('user_id', auth()->id())->where('water', '!=', null)->whereDate('targets.created_at', $today);
                        }, 'media', 'targets.goalPlan'])
                        ->get();
                    // get sleep
                    $sleep = Plan::query()
                        ->whereHas('targets', function ($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->where('type', 'sleep')
                        ->with(['targets' => function ($query) use ($today) {
                            $query->where('user_id', auth()->id())->where('sleep', '!=', null)->whereDate('targets.created_at', $today);
                        }, 'media', 'targets.goalPlan'])
                        ->get();
                    foreach ($data as $d) {
                        $d->myGoal = $goal->goalPlan->goals;
                        $d->plans = $plans;
                        $d->meals = $food;
                        $d->water = $water;
                        $d->sleep = $sleep;
                        $d->date = Auth::user()->date;
                        $d->status = ['week' => $currentWeekNumber + 1, 'day' => $day];
                    }
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                    $type = 'expire';
                    $observer = new GoalPlanObserver();
                    $observer->update();
                }
            } else {
                $message =  app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "If you want to see more details please register with this goal and don't forget to check your email address 😉😉" : "إذا كنت تريد رؤية المزيد من التفاصيل يرجى التسجيل بهذا الهدف ولا تنسى التحقق من عنوان بريدك الإلكتروني 😉😉";
        }
        return response()->json(['data' => $data, 'type' => $type, 'message' => $message]);
    }


    public function getUserPlans()
    {
        $message = '';
        $newIndex = [];
        $type = 'error';
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($CountGetdate) {
            $CountGetdateWithActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateWithActive) {

                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                $week = $dayd['week'];
                if ($day > 0) {
                    $plans = Plan::query()
                        ->whereHas('targets', function ($q) {
                            $q->where('user_id', auth()->id())->where('check', '!=', 0)->where('active', 1);
                        })
                        ->where('type', '!=', 'food')
                        ->where('type', '!=', 'sleep')
                        ->where('type', '!=', 'water')

                        ->with(['targets' => function ($query) {
                            $query->where('user_id', auth()->id())->where('check', '!=', 0);
                        }, 'media', 'targets.goalPlan'])
                        ->get();
                    if ($plans) {
                        $ar = Exercise::where('type', Auth::user()->gender)->pluck('id')->toArray();
                        $newIndex = $plans->map(function ($i) use ($ar) {
                            $count = Target::where('user_id', auth()->id())
                                ->whereIn('check', $ar)
                                ->whereHas('goalPlan', function ($q) use ($i) {
                                    $q->where('plan_id', $i->id);
                                })
                                ->count();
                            $countExe = PlanExercise::where('plan_id', $i->id)->whereHas('exercises', function ($q) {
                                $q->where('exercises.type', Auth::user()->gender);
                            })->count();
                            $totalRate = intval(($count /  $countExe) * 100);
                            $i->totalRate = $totalRate;
                            return $i;
                        });
                    } else {
                        $message = app()->getLocale() == 'en' ? "Don't you want to burn the fat? 💪💪What your waiting for start your first plan with us now 🔥🔥" : 'ألا تريد حرق الدهون؟ 💪💪ما الذي تنتظره؟ ابدأ خطتك الأولى معنا الآن 🔥🔥';
                    }
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }

                $type = 'success';
            } else {
                $message =  app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "If you want to see more details please register with this goal and don't forget to check your email address 😉😉" : "إذا كنت تريد رؤية المزيد من التفاصيل يرجى التسجيل بهذا الهدف ولا تنسى التحقق من عنوان بريدك الإلكتروني 😉😉";
        }

        return response()->json(['data' => $newIndex, 'type' => $type, 'message' => $message]);
    }


    public function showPlan(Request $request, $id)
    {
        $arr = [];
        $arrDay = [];
        $arrCal = [];
        $date = [];
        $gender = Auth::user()->gender;
        $ar = Exercise::where('type', Auth::user()->gender)->pluck('id')->toArray();
        $type = 'error';
        $message = '';
        $today = Carbon::today();
        $show = [];
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->whereHas('goalPlan', function ($q) use ($id) {
            $q->where('plan_id', $id);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $dayd = GetDate::GetDate(2);
                $day = $dayd['day'];
                $week = $dayd['week'];
                if ($day > 0) {
                    $countExeForWeek = PlanExercise::where('plan_id', $id)->whereHas('exercises', function ($q) {
                        $q->where('exercises.type', Auth::user()->gender);
                    })->where('week', $week)->count();
                    $countExe = PlanExercise::where('plan_id', $id)->whereHas('exercises', function ($q) {
                        $q->where('exercises.type', Auth::user()->gender);
                    })->count();
                    $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                    $date = $Getdate->users->date;

                    //count exerice with gender 
                    $count = Target::where('user_id', auth()->id())
                        ->whereHas('goalPlan', function ($q) use ($id) {
                            $q->where('plan_id', $id);
                        })
                        ->whereIn('check', $ar)->count();

                    $dates = $Getdate->users->date;

                    $weeks = collect($dates)->chunk(7)->values();

                    $currentDate = Carbon::today()->toDateString();
                    $currentWeekNumber = $weeks->search(function ($weekDays) use ($currentDate) {
                        return collect($weekDays)->contains(function ($d) use ($currentDate) {
                            return Carbon::parse($d->date)->toDateString() === $currentDate;
                        });
                    });

                    $totalRate = 0;

                    if ($currentWeekNumber !== false) {
                        $weekDates = collect($weeks[$currentWeekNumber])->map(function ($d) {
                            return Carbon::parse($d->date)->toDateString();
                        });

                        $countWeek = Target::where('user_id', auth()->id())
                            ->whereHas('goalPlan', function ($q) use ($id) {
                                $q->where('plan_id', $id);
                            })
                            ->whereIn(DB::raw('DATE(updated_at)'), $weekDates)
                            ->whereIn('check', $ar)
                            ->count();

                        $countTotal = Target::where('user_id', auth()->id())
                            ->whereHas('goalPlan', function ($q) use ($id) {
                                $q->where('plan_id', $id);
                            })
                            ->whereIn('check', $ar)

                            ->count();
                        $totalRateForAllPlan = $countTotal > 0 ? intval(($countTotal / $countExe) * 100) : 0;

                        $totalRate = $countWeek > 0 ? intval(($countWeek / $countExeForWeek) * 100) : 0;
                        $results = [
                            'week' =>  $currentWeekNumber + 1,
                            'day' =>   $day + 1,
                            'rate'  => $totalRate
                        ];
                    }


                    $countDay = Target::where('user_id', auth()->id())
                        ->whereHas('goalPlan', function ($q) use ($id) {
                            $q->where('plan_id', $id);
                        })->whereIn('check', $ar)->WhereDate('updated_at', $today)->count();

                    $countx = PlanExercise::where('day', $day)->where('week', $week)
                        ->whereIn('exercise_id', $ar)->where('plan_id', $id)
                        ->count();
                    $countE = $countx ? $countx : 1;
                    $totalRateDay = intval(($countDay / $countE) * 100);


                    // drow proggres day rate
                    $AllDayTotal = Target::selectRaw('DATE(created_at) as x, COUNT(calories) as y')->where('user_id', auth()->id())
                        ->whereHas('goalPlan', function ($q) use ($id) {
                            $q->where('plan_id', $id);
                        })->whereIn('check', $ar)->groupBy('x')->get();

                    foreach ($AllDayTotal as $data) {
                        $countexforday = PlanExercise::where("plan_id", $id)->where('day', $data->day)->where('week', $data->week)
                            ->whereHas('exercises', function ($q) use ($ar) {
                                $q->whereIn("type",  $ar);
                            })
                            ->count();
                        $totalRateDayTotal = $countexforday > 0 ? intval($data->y /  $countexforday) * 100  : 0;
                        array_push($arrDay, ['x' => $data->x, 'y' => $totalRateDayTotal]);
                    }

                    // progress claories
                    $CalroiesForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                        ->whereHas('goalPlan', function ($q) use ($id) {
                            $q->where('plan_id', $id);
                        })
                        ->whereIn('check', $ar)
                        ->where('user_id', auth()->id())
                        ->groupBy('x')
                        ->get();
                    foreach ($CalroiesForDay as $data) {
                        if ($data->x > 0) {
                            array_push($arrCal, ['x' => $data->x, 'y' => intval($data->y)]);
                        }
                    }

                    $show = Plan::where('id', $id)
                        ->with(['targets' => function ($q) use ($today) {
                            $q->where('user_id', auth()->id())->whereDate('targets.created_at', $today)->where('check', '!=', 0);
                        }, 'targets.users', 'targets.users.date', 'media'])
                        ->first();
                    $show->date =   $date;
                    $show->totalRate =   $totalRateForAllPlan;
                    $show->arrCal =   $arrCal;
                    $show->totalRateDay =   $totalRateDay;
                    $show->totalRateWeekOne =   $results;
                    $show->arrDay = $arrDay;
                    $show->countE_week = $countExe;
                    $type = 'success';
                } else {
                    $message =  app()->getLocale() == 'en' ? 'Your goal has expired. You can extend the period or choose another goal.' : 'لقد انتهت مدة هدفك يمكنك تمديد المدة او اختيار هدف اخر';
                }
            } else {
                $message = app()->getLocale() == 'en' ? 'please wait to processing the goal' : 'الرجاء الانتظار حتى معالجة طلبك';
            }
        } else {
            $message = app()->getLocale() == 'en' ? "This plan is not available to you or is not intended for goal.😊" : 'هذه الخطة غير متاحة لك أو غير مخصصة للهدف.😊';
        }
        return response()->json(['data' => $show, 'type' => $type, 'message' => $message]);
    }

    // public function showPlan(Plan $Plan)
    // {
    //     $i = $Plan->load(['plan', 'plan.media',  'level', 'exercise', 'exercise.media']);
    //     return response()->json($i);
    // }
}
