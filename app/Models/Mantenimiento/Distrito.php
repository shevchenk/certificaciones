<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected   $table = 'mat_ubicacion_distrito';
     
    
        public static function ListDistrito($r)
    {
        $sql=Distrito::select('id','distrito','estado')
            ->where('estado','=','1')
            ->where('provincia_id','=',$r->provincia_id);
        $result = $sql->orderBy('distrito','asc')->get();
        return $result;
    }
}
