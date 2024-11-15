<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'section_id',
        'price',
    ];
}
