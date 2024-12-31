<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Goal extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'title_ar',
        'description_ar',
        'calories_max',
        'calories_min',
        'duration',
    ];

    public function Plan()
    {
        return $this->belongsToMany(
            Plan::class,
            'goal_plans',
            'goal_id',
            'plan_id'
        );
    }

    public function targets()
    {
        return $this->hasManyThrough(Target::class, GoalPlan::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('goals')->singleFile();
    }
}
