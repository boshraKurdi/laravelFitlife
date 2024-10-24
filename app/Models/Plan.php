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
        'duration',
        'muscle',
    ];

    public function levels()
    {
        return $this->belongsToMany(
            Level::class,
            'plan_levels',
            'plan_id',
            'level_id'
        );
    }
    public function goalPlanLevel()
    {
        return $this->hasManyThrough(GoalPlanLevel::class, PlanLevel::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('plans')->singleFile();
    }
}
