<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class DetalleTipoLlamada extends Model
{
    protected   $table = 'tipo_llamadas_sub_detalle';

    public static function runEditStatus($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = DetalleTipoLlamada::find($r->id);
        $tipo_llamada->estado = trim( $r->estadof );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runNew($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = new DetalleTipoLlamada;
        $tipo_llamada->tipo_llamada_id = trim( $r->tipo_llamada_id );
        $tipo_llamada->tipo_llamada_sub_id = trim( $r->tipo_llamada_sub_id );
        $tipo_llamada->tipo_llamada_sub_detalle = trim( $r->tipo_llamada_sub_detalle );
        $tipo_llamada->detalle = trim( $r->detalle );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_created_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runEdit($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = DetalleTipoLlamada::find($r->id);
        $tipo_llamada->tipo_llamada_id = trim( $r->tipo_llamada_id );
        $tipo_llamada->tipo_llamada_sub_id = trim( $r->tipo_llamada_sub_id );
        $tipo_llamada->tipo_llamada_sub_detalle = trim( $r->tipo_llamada_sub_detalle );
        $tipo_llamada->detalle = trim( $r->detalle );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('tipo_llamadas_sub_detalle AS tl')
            ->select(
            'tl.id','tl.detalle',
            'tl.tipo_llamada_sub_detalle',
            'tl.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_llamada_sub_detalle") ){
                        $tipo_llamada_sub_detalle=trim($r->tipo_llamada_sub_detalle);
                        if( $tipo_llamada_sub_detalle !='' ){
                            $query->where('tl.tipo_llamada_sub_detalle','like','%'.$tipo_llamada_sub_detalle.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('tl.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('tl.tipo_llamada_sub_detalle','asc')->paginate(10);
        return $result;
    }
    
    public static function ListDetalleTipoLlamada($r)
    {
        $sql=DetalleTipoLlamada::select('id','tipo_llamada_sub_detalle','detalle','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_llamada_sub_id") ){
                        $tipo_llamada_sub_id=trim($r->tipo_llamada_sub_id);
                        if( $tipo_llamada_sub_id !='' ){
                            $query->where('tipo_llamada_sub_id','=',$tipo_llamada_sub_id);
                        }
                    }

                    $query->where('estado',1);
                }
            );
        $result = $sql->orderBy('tipo_llamada_sub_detalle','asc')->get();
        return $result;
    }
}
