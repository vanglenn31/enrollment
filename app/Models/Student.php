<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'program',
        'preferred_time',
        'year_level',
    ];

    public function educationalbackground(): HasMany {
        return $this->hasMany(Educationalbackground::class);
    }
}
