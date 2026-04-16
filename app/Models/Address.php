<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable =[
        'house_number',
        'street',
        'barangay',
        'city',
        'province',
        'postal_code'
    ];

    public function profile(): BelongsTo {
        return $this->belongsTo(Profile::class);
    }

}
