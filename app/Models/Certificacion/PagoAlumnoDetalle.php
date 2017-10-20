<?php

namespace App\Models\Certificacion;
use DB;
use Illuminate\Database\Eloquent\Model;


class PagoAlumnoDetalle extends Model
{
    protected   $table = 'pago_alumno_detalles';

        public static function runLoad($r)
    {
        $result=DB::table('pago_alumno_detalles AS pad')
            ->join('mat_contesta AS mc',function($join){
                $join->on('pad.contesta_id','=','mc.id')
                ->where('mc.estado','=',1);
            })
            ->select('pad.id','mc.detalle','pad.estado_contesta','pad.observacion','pad.created_at')
            ->where('pad.certificado_id','=',$r->certificado_id)
            ->orderBy('mc.detalle','asc');
        return $result;
    }
}
