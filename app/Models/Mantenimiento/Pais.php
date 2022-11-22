<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected   $table = 'paises';
     
    
    public static function ListPais($r)
    {
        $sql=Pais::select('id','pais','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('pais','asc')->get();
        return $result;
    }
}
