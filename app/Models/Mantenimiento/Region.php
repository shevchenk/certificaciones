<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected   $table = 'mat_ubicacion_region';
     
    
        public static function ListRegion($r)
    {
        $sql=Region::select('id','region','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('region','asc')->get();
        return $result;
    }
}
