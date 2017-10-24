<?php

namespace App\Models\Certificacion;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class PagoAlumnoDetalle extends Model
{
    protected   $table = 'pago_alumno_detalles';

        public static function runLoad($r){
            
        $result=DB::table('pago_alumno_detalles AS pad')
            ->leftjoin('mat_contesta AS mc',function($leftjoin){
                $leftjoin->on('pad.contesta_id','=','mc.id')
                ->where('mc.estado','=',1);
            })
            ->join('personas as p','p.id','=','pad.persona_id_created_at')
            ->select(DB::raw('IFNULL(mc.detalle,"") as detalle'),DB::raw('CASE pad.estado_contesta  WHEN 0 THEN "NO" WHEN 1 THEN "SI" END AS estado_contesta '),'pad.observacion','pad.created_at',
                    DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) as usuario'))
            ->where('pad.certificado_id','=',$r->certificado_id)
            ->where('pad.estado','=','1')
            ->orderBy('pad.id','desc')->get();
        return $result;
    }
    
        public static function runNew($r){
            
        $pagoalumnodetalle = new PagoAlumnoDetalle;
        $pagoalumnodetalle->certificado_id = trim( $r->certificado_id );
        if(trim( $r->estado_contesto )!=0){
           $pagoalumnodetalle->contesta_id = trim( $r->contesta_id ); 
        }
        $pagoalumnodetalle->estado_contesta = trim( $r->estado_contesto );
        $pagoalumnodetalle->observacion = trim( $r->observacion );
        $pagoalumnodetalle->certificado_estado_id = trim( $r->certificado_estado_id );
        $pagoalumnodetalle->persona_id_created_at= Auth::user()->id;
        $pagoalumnodetalle->save();
    }
}
