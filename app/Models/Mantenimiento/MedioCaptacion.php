<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class MedioCaptacion extends Model
{
    protected   $table = 'mat_medios_captaciones';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = MedioCaptacion::find($r->id);
        $medio_captacion->estado = trim( $r->estadof );
        $medio_captacion->persona_id_updated_at=$usuario;
        $medio_captacion->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = new MedioCaptacion;
        $medio_captacion->medio_captacion = trim( $r->medio_captacion );
        $medio_captacion->tipo_medio = trim( $r->tipo_medio );
        $medio_captacion->estado = trim( $r->estado );
        $medio_captacion->persona_id_created_at=$usuario;
        $medio_captacion->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = MedioCaptacion::find($r->id);
        $medio_captacion->medio_captacion = trim( $r->medio_captacion );
        $medio_captacion->tipo_medio = trim( $r->tipo_medio );
        $medio_captacion->estado = trim( $r->estado );
        $medio_captacion->persona_id_updated_at=$usuario;
        $medio_captacion->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('mat_medios_captaciones AS t')
            ->select(
            't.id',
            't.medio_captacion',
            't.tipo_medio',
            't.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("medio_captacion") ){
                        $medio_captacion=trim($r->medio_captacion);
                        if( $medio_captacion !='' ){
                            $query->where('t.medio_captacion','like','%'.$medio_captacion.'%');
                        }
                    }

                    if( $r->has("tipo_medio") ){
                        $tipo_medio=trim($r->tipo_medio);
                        if( $tipo_medio !='' ){
                            $query->where('t.tipo_medio',$tipo_medio);
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('t.estado',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('t.medio_captacion','asc')->paginate(10);
        return $result;
    }
    
    public static function ListMedioCaptacion($r)
    {  
        $sql=MedioCaptacion::select('id','medio_captacion','tipo_medio','estado')
            ->where('estado','=','1')
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_medio") ){
                        $tipo_medio=trim($r->tipo_medio);
                        if( $tipo_medio !='' ){
                            $query->where('tipo_medio',$tipo_medio);
                        }
                    }
                }
            );
        $result = $sql->orderBy('medio_captacion','asc')->get();
        return $result;
    }
}
