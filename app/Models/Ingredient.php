<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ingredient extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'name',
        'name_ar',
        'num',
        'meal_id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ingredients')->singleFile();
    }
}
