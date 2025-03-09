<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Step extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable =  [
        'content',
        'content_ar',
        'exercise_id'
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('steps');
    }
}
