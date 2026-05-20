<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'profile_id',
        'student_number',
        'program',
        'preferred_time',
        'status',
        'year_level',
        'is_verified',
        'is_suspended',
        'is_withdrawn',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function programRelation(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program');
    }

    public function educationalBackground(): HasMany
    {
        return $this->hasMany(EducationalBackground::class);
    }

    public function studentEnrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }
    public function enrolledCourses()
{
    return $this->hasMany(EnrolledCourse::class);
}
}
