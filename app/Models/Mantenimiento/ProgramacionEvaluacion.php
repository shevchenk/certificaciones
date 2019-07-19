<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class ProgramacionEvaluacion extends Model
{
    protected   $table = 'mat_programaciones_evaluaciones';
}
