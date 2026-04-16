<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfiles extends Model
{
    protected $table ='profile';
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
}
