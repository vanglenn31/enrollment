<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Read-only model backed by the v_students_by_program database view.
 *
 * @property int    $program_id
 * @property string $program_name
 * @property string $program_code
 * @property string $department_name
 * @property int    $student_count
 */
class StudentsByProgram extends Model
{
    protected $table = 'v_students_by_program';

    protected $primaryKey = 'program_id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $guarded = ['*'];
}
