<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Meal extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'components',
        'components_ar',
        'prepare',
        'calories',
        'prepare_ar',
        'category_id'
    ];
    public function planLevel()
    {
        return $this->belongsToMany(
            PlanLevel::class,
            'plan_level_meals',
            'meal_id',
            'plan_level_id',
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('meals')->singleFile();
    }
}
