<?php

namespace App\Models;

use App\Models\ProgramView;

use Illuminate\Database\Eloquent\Model;

class ProgramView extends Model
{
    protected $table = 'programs_view';

    public $timestamps = false;

    public $incrementing = false;
}
