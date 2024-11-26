<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable =  [
        'content',
        'content_ar',
        'exercise_id'
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
