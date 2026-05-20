<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Read-only model backed by the v_enrollment_by_department database view.
 *
 * @property int    $department_id
 * @property string $department_name
 * @property int    $enrolled_students
 */
class EnrollmentByDepartment extends Model
{
    protected $table = 'v_enrollment_by_department';

    // Views have no auto-increment primary key
    protected $primaryKey = 'department_id';
    public $incrementing  = false;
    public $timestamps    = false;

    // Prevent accidental writes
    protected $guarded = ['*'];
}
