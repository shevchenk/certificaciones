<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class SubTipoLlamada extends Model
{
    protected   $table = 'tipo_llamadas_sub';

    public static function runEditStatus($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = SubTipoLlamada::find($r->id);
        $tipo_llamada->estado = trim( $r->estadof );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runNew($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = new SubTipoLlamada;
        $tipo_llamada->tipo_llamada_id = trim( $r->tipo_llamada_id );
        $tipo_llamada->tipo_llamada_sub = trim( $r->tipo_llamada_sub );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_created_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runEdit($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = SubTipoLlamada::find($r->id);
        $tipo_llamada->tipo_llamada_id = trim( $r->tipo_llamada_id );
        $tipo_llamada->tipo_llamada_sub = trim( $r->tipo_llamada_sub );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('tipo_llamadas_sub AS tl')
            ->select(
            'tl.id',
            'tl.tipo_llamada_sub',
            'tl.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_llamada_sub") ){
                        $tipo_llamada_sub=trim($r->tipo_llamada_sub);
                        if( $tipo_llamada_sub !='' ){
                            $query->where('tl.tipo_llamada_sub','like','%'.$tipo_llamada_sub.'%');
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
        $result = $sql->orderBy('tl.tipo_llamada_sub','asc')->paginate(10);
        return $result;
    }
    
    public static function ListSubTipoLlamada($r)
    {
        $sql=SubTipoLlamada::select('id','tipo_llamada_sub','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_llamada_id") ){
                        $tipo_llamada_id=trim($r->tipo_llamada_id);
                        if( $tipo_llamada_id !='' ){
                            $query->where('tipo_llamada_id','=',$tipo_llamada_id);
                        }
                    }

                    $query->where('estado',1);
                }
            );
        $result = $sql->orderBy('tipo_llamada_sub','asc')->get();
        return $result;
    }
}
