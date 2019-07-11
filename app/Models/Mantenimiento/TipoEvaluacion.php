<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class TipoEvaluacion extends Model
{
    protected   $table = 'tipos_evaluaciones';

    public static function runEditStatus($r)
    {
        $tipo_evaluacion_id = Auth::user()->id;
        $tipo_evaluacion = TipoEvaluacion::find($r->id);
        $tipo_evaluacion->estado = trim( $r->estadof );
        $tipo_evaluacion->persona_id_updated_at=$tipo_evaluacion_id;
        $tipo_evaluacion->save();
    }

    public static function runNew($r)
    {
        $tipo_evaluacion_id = Auth::user()->id;
        $tipo_evaluacion = new TipoEvaluacion;
        $tipo_evaluacion->tipo_evaluacion = trim( $r->tipo_evaluacion );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_created_at=$tipo_evaluacion_id;
        $tipo_evaluacion->save();
    }

    public static function runEdit($r)
    {
        $tipo_evaluacion_id = Auth::user()->id;
        $tipo_evaluacion = TipoEvaluacion::find($r->id);
        $tipo_evaluacion->tipo_evaluacion = trim( $r->tipo_evaluacion );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_updated_at=$tipo_evaluacion_id;
        $tipo_evaluacion->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('tipo_evaluacions AS te')
            ->select(
            'te.id',
            'te.tipo_evaluacion',
            'te.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_evaluacion") ){
                        $tipo_evaluacion=trim($r->tipo_evaluacion);
                        if( $tipo_evaluacion !='' ){
                            $query->where('te.tipo_evaluacion','like','%'.$tipo_evaluacion.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('te.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('te.tipo_evaluacion','asc')->paginate(10);
        return $result;
    }
    
    public static function ListTipoEvaluacion($r)
    {  
        $sql=TipoEvaluacion::select('id','tipo_evaluacion','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('tipo_evaluacion','asc')->get();
        return $result;
    }
}
