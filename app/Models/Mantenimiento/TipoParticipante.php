<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TipoParticipante extends Model
{
    protected   $table = 'mat_tipos_participantes';
     
        public static function runEditStatus($r)
    {
        $trabajador = TipoParticipante::find($r->id);
        $trabajador->estado = trim( $r->estadof );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runNew($r)
    {

        $trabajador = new TipoParticipante;
        $trabajador->tipo_participante = trim( $r->tipo_participante );
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_created_at=Auth::user()->id;
        $trabajador->save();
    }
    public static function runEdit($r)
    {

        $trabajador = TipoParticipante::find($r->id);
        $trabajador->tipo_participante = trim( $r->tipo_participante );
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runLoad($r)
    {
        $sql=TipoParticipante::select('mat_tipos_participantes.id','mat_tipos_participantes.tipo_participante'
                                ,'mat_tipos_participantes.estado')
             ->where( 
                function($query) use ($r){
                 
                    if( $r->has("tipo_participante") ){
                        $tipo_participante=trim($r->tipo_participante);
                        if( $tipo_participante !='' ){
                            $query->where('mat_tipos_participantes.tipo_participante','like','%'.$tipo_participante.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mat_tipos_participantes.estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('mat_tipos_participantes.id','asc')->paginate(10);
        return $result;
    }
        public static function ListTipoParticipante($r)
    {
        $sql=TipoParticipante::select('id','tipo_participante','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('tipo_participante','asc')->get();
        return $result;
    }
}
