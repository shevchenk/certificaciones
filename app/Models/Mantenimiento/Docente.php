<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Docente extends Model
{
    protected   $table = 'mat_docentes';

    
        public static function ListDocente($r)
    {
        $sql=Docente::select('mat_docentes.id','mat_docentes.persona_id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) as persona'),'mat_docentes.estado')
                      ->join('personas as p','p.id','=','mat_docentes.persona_id')
            ->where('mat_docentes.estado','=','1');
        $result = $sql->orderBy('mat_docentes.id','asc')->get();
        return $result;
    }
    

}
