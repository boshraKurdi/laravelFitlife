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
        'calories',
        'duration',
    ];

    public function PlanLevel()
    {
        return $this->belongsToMany(
            PlanLevel::class,
            'goal_plan_levels',
            'goal_id',
            'plan_level_id'
        );
    }

    public function targets()
    {
        return $this->hasManyThrough(Target::class, GoalPlanLevel::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('goals')->singleFile();
    }
}
