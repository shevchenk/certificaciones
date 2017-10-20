<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;


class Contesta extends Model
{
    protected   $table = 'mat_contesta';

    public static function ListContesta(){
        $result= Contesta::select('id','detalle as nombre','estado')
            ->where('estado','=','1')
            ->orderBy('detalle','asc')->get();
        return $result;
    }
}
