<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'days',
        'height',
        'width',
        'illness',
        'age',
        'gender',
        'address',
        'google_id',
        'google_token',
        'lat',
        'lon'
    ];

    public function coachs()
    {
        return $this->belongsToMany(
            __CLASS__,
            'chats',
            'user_id',
            'coach_id'
        );
    }
    public function date()
    {
        return $this->hasMany(Date::class);
    }
    public function users()
    {
        return $this->belongsToMany(
            __CLASS__,
            'chats',
            'coach_id',
            'user_id'
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function goalPlan(): BelongsToMany
    {
        return $this->belongsToMany(
            GoalPlan::class,
            'targets',
        )->withPivot(['calories', 'updated_at']);
    }
    public function targets()
    {
        return $this->hasMany(
            Target::class,
        );
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('users')->singleFile();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
