<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected   $table = 'mat_roles';
     
    
        public static function ListRol($r)
    {
        $sql=Rol::select('id','rol','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('rol','asc')->get();
        return $result;
    }
}
