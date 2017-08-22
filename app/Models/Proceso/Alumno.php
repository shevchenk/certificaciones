<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected   $table = 'mat_alumnos';

            public static function BuscarAlumno($r)
    {
        $sql=Alumno::select('id','direccion','referencia','region_id','provincia_id','distrito_id')
            ->where('estado','=','1')
            ->where('persona_id','=',$r->persona_id);
        $result = $sql->orderBy('id','asc')->first();
        return $result;
    }
}
