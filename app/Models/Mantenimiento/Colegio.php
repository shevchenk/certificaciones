<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected   $table = 'colegios';
     
    
    public static function ListColegio($r)
    {
        $sql=Colegio::select('id','colegio','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('colegio','asc')->get();
        return $result;
    }
}
