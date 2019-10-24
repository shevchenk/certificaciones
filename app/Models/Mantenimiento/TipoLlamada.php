<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class TipoLlamada extends Model
{
    protected   $table = 'tipo_llamadas';

    public static function runEditStatus($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = TipoLlamada::find($r->id);
        $tipo_llamada->estado = trim( $r->estadof );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runNew($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = new TipoLlamada;
        $tipo_llamada->tipo_llamada = trim( $r->tipo_llamada );
        $tipo_llamada->obs = trim( $r->obs );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_created_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runEdit($r)
    {
        $tipo_llamada_id = Auth::user()->id;
        $tipo_llamada = TipoLlamada::find($r->id);
        $tipo_llamada->tipo_llamada = trim( $r->tipo_llamada );
        $tipo_llamada->obs = trim( $r->obs );
        $tipo_llamada->estado = trim( $r->estado );
        $tipo_llamada->persona_id_updated_at=$tipo_llamada_id;
        $tipo_llamada->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('tipo_llamadas AS tl')
            ->select(
            'tl.id',
            'tl.tipo_llamada',
            'tl.estado','tl.obs'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_llamada") ){
                        $tipo_llamada=trim($r->tipo_llamada);
                        if( $tipo_llamada !='' ){
                            $query->where('tl.tipo_llamada','like','%'.$tipo_llamada.'%');
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
        $result = $sql->orderBy('tl.tipo_llamada','asc')->paginate(10);
        return $result;
    }
    
    public static function ListTipoLlamada($r)
    {  
        $empresa_id= Auth::user()->empresa_id;
        $sql=TipoLlamada::select('id','tipo_llamada','obs','estado')
            ->where('estado','=','1')
            ->where(function($query) use ($r, $empresa_id){
                if( $r->has('plataforma') ){
                    $query->where('plataforma',1);
                }
                else{
                    $query->where('plataforma',0);
                    $query->where('empresa_id','=',$empresa_id);
                }
            });
        $result = $sql->orderBy('tipo_llamada','asc')->get();
        return $result;
    }
}
