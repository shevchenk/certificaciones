<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected   $table = 'mat_ubicacion_provincia';
     
    
        public static function ListProvincia($r)
    {
        $sql=Provincia::select('id','provincia','estado')
            ->where('estado','=','1')
            ->where('region_id','=',$r->region_id);
        $result = $sql->orderBy('provincia','asc')->get();
        return $result;
    }
}
