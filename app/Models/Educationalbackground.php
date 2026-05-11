<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationalBackground extends Model
{
    protected $table = 'educationbackground';
    
    protected $fillable =[
        'school',
        'grad_date',
        'strand_or_course',
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
