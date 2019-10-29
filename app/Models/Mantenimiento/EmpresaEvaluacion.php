<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class EmpresaEvaluacion extends Model
{
    protected   $table = 'empresas_tipos_evaluaciones';
}
