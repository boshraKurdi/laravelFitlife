<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLevel extends Model
{
    use HasFactory;
    protected $table = 'plan_levels';

    protected $fillable = [
        'plan_id',
        'level_id'
    ];
    public function goals()
    {
        return $this->belongsToMany(Goal::class, 'goal_plan_levels');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function exercise()
    {
        return $this->belongsToMany(
            Exercise::class,
            'plan_level_exercises',
            'plan_level_id',
            'exercise_id'
        )->withPivot(['day', 'week']);
    }
    public function meal()
    {
        return $this->belongsToMany(
            Meal::class,
            'plan_level_meals',
            'plan_level_id',
            'meal_id'
        );
    }
    public function targets()
    {
        return $this->hasManyThrough(Target::class, GoalPlanLevel::class);
    }
}
