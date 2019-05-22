<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB; //BD

class Llamada extends Model
{
    protected   $table = 'llamadas';

    public static function RegistrarLlamada($r)
    {
        $llamada= new Llamada;
        $llamada->trabajador_id=$r->teleoperadora;
        $llamada->persona_id=$r->persona_id;
        $llamada->fecha_llamada=$r->fecha;
        if( Input::has('comentario') AND trim( $r->comentario )!='' ){
            $llamada->comentario=$r->comentario;
        }

        $llamada->tipo_llamada_id=$r->tipo_llamada;

        if( Input::has('fechas') AND trim( $r->fechas )!='' ){
            $llamada->fechas=$r->fechas;
        }

        if( Input::has('detalle_tipo_llamada') AND trim( $r->detalle_tipo_llamada )!='' ){
            $llamada->tipo_llamada_sub_id=$r->sub_tipo_llamada;
            $llamada->tipo_llamada_sub_detalle_id=$r->detalle_tipo_llamada;
        }


        $llamada->persona_id_created_at=Auth::user()->id;
        $llamada->save();
        return $llamada;
    }
    // --
}
