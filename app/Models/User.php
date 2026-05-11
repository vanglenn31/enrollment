<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[Fillable([ 'email', 'password', 'role_id', 'is_active'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function role(): BelongsTo {
        return $this->belongsTo(Roles::class);
    }

    public function profile(): HasOne {
        return $this->hasOne(Profile::class);
    }

    public function registrar(): HasOne {
        return $this->hasOne(Registrar::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }
}
