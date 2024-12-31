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
        'carbohydrates',
        'fats',
        'proteins',
        'prepare_ar',
        'category_id'
    ];
    public function plan()
    {
        return $this->belongsToMany(
            Plan::class,
            'plan_meals',
            'meal_id',
            'plan_id',
        );
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('meals')->singleFile();
    }
}
