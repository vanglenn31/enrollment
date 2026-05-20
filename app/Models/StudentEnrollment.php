<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEnrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'term_id',
        'status',
        'units',
    ];

    protected $casts = [
    'enrollment_date' => 'datetime',
    ];

    public function student()
{
    return $this->belongsTo(Student::class, 'student_id');
}

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function term(): BelongsTo {
        return $this->belongsTo(Term::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_enrollment_id');
    }

    public function enrolledCourse()
    {
        return $this->hasOne(\App\Models\EnrolledCourse::class, 'student_enrollment_id');
    }
        public function getTotalTuitionAttribute(): float
    {
        // replace with your actual tuition logic
        return $this->course?->tuition_fee ?? 0;
    }
    
}