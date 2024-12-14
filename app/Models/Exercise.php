<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exercise extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable =  [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'duration',
        'counter',
        'calories'
    ];
    public function planLevel()
    {
        return $this->belongsToMany(
            PlanLevel::class,
            'plan_level_exercises',
            'exercise_id',
            'plan_level_id'
        );
    }
    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('exercises');
    }
}
