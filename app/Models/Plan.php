<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Plan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'title',
        'description',
        'title_ar',
        'description_ar',
        'duration',
        'muscle',
        'muscle_ar',
        'type',
        'type_ar'
    ];


    public function goalPlan()
    {
        return $this->hasManyThrough(GoalPlan::class, Plan::class);
    }
    public function targets()
    {
        return $this->hasManyThrough(Target::class, GoalPlan::class);
    }
    public function exercise()
    {
        return $this->belongsToMany(
            Exercise::class,
            'plan_exercises',
            'plan_id',
            'exercise_id',
        )->withPivot(['day', 'week']);
    }
    public function goals()
    {
        return $this->belongsToMany(
            Goal::class,
            'goal_plans',
            'plan_id',
            'goal_id',
        );
    }
    public function meal()
    {
        return $this->belongsToMany(
            Meal::class,
            'plan_meals',
            'plan_id',
            'meal_id',
        )->withPivot(['breakfast', 'lunch', 'dinner', 'snacks']);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('plans')->singleFile();
    }
}
