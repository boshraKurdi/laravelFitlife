<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable =  [
        'text',
        'isCoach',
        'isSeen',
        'group_id'
    ];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('messages');
    }
}
