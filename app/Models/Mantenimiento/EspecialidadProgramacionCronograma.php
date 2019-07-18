<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class EspecialidadProgramacionCronograma extends Model
{
    protected   $table = 'mat_especialidades_programaciones_cronogramas';
}
