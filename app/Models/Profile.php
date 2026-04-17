<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'birthdate',
        'phone_number'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function address(): HasOne {
        return $this->hasOne(Address::class);
    }
    public function student(): HasOne {
        return $this->hasOne(Student::class);
    }
}
