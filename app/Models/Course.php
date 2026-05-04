<?php

namespace App\Models;

use App\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'program_id',
        'professor_id',
        'course_name',
        'course_code',
        'description',
        'units',
        'course_price',
        'time',
        'status',
        'room_id',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    public function studentEnrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }
    public function room()
{
    return $this->belongsTo(Room::class);
}
}
