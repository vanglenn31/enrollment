<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roles extends Model
{
    protected $fillable = [
        'role'
    ];

    public function user(): HasMany{
        return $this->hasMany(User::class);
    }
}
