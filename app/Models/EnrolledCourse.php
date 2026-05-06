<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrolledCourse extends Model
{
    protected $fillable = [
        'student_enrollment_id',
        'course_id',
        'professor_id',
        'room_id',
        'grade',
        'course_price',
    ];

    protected $casts = [
        'grade'        => 'decimal:2',
        'course_price' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function studentEnrollment()
    {
        return $this->belongsTo(StudentEnrollment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
