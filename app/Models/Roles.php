<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Roles extends Model
{
    protected $fillable = [
        'role'
    ];

    public function user(): HasOne{
        return $this->hasOne(User::class);
    }
}
