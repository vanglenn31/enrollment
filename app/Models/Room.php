<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
   protected $fillable = ['room_name', 'room_building'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
