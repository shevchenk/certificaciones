<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB; //BD

class LlamadaAtencionCliente extends Model
{
    protected   $table = 'llamadas_atencion_cliente';

    public static function RegistrarLlamada($r)
    {
        $llamada= new LlamadaAtencionCliente;
        $llamada->persona_id=$r->persona_id;
        $llamada->matricula_detalle_id=$r->matricula_detalle_id;
        $llamada->fecha_registro=date('Y-m-d');
        $llamada->comentario= trim( $r->comentario );
        $llamada->persona_id_created_at=Auth::user()->id;
        $llamada->save();

        return 'Ok';
    }

    public static function ResponderLlamada($r)
    {
        $llamada= LlamadaAtencionCliente::find($r->id);
        $llamada->fecha_respuesta=date('Y-m-d');
        $llamada->respuesta= trim( $r->respuesta );
        $llamada->persona_id_updated_at=Auth::user()->id;
        $llamada->save();
        return 'Ok';
    }

    public static function CargarLlamada($r)
    {
        $sql=DB::table('llamadas_atencion_cliente AS llac')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.id','=','llac.matricula_detalle_id');
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','llac.persona_id');
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->Join('mat_cursos AS c', function($join){
                $join->on('c.id','=','mp.curso_id');
            })
            ->select(
            'llac.id','llac.fecha_registro','llac.comentario',
            'llac.fecha_respuesta','llac.respuesta',
            'p.paterno','p.materno','p.nombre','llac.persona_id','p.dni',
            DB::raw('DATE(mp.fecha_inicio) AS fecha_seminario'),'c.curso AS seminario'
            )
            ->where( 
                function($query) use ($r){
                        if( Input::has('matricula_detalle_id') AND trim($r->matricula_detalle_id) !='' ){
                            $query->where('mmd.id','=', $r->matricula_detalle_id);
                        }
                        if( $r->has("nombre") ){
                            $nombre=trim($r->nombre);
                            if( $nombre !='' ){
                                $query->where('p.nombre','like','%'.$nombre.'%');
                            }
                        }
                        if( $r->has("paterno") ){
                            $paterno=trim($r->paterno);
                            if( $paterno !='' ){
                                $query->where('p.paterno','like','%'.$paterno.'%');
                            }
                        }
                        if( $r->has("materno") ){
                            $materno=trim($r->materno);
                            if( $materno !='' ){
                                $query->where('p.materno','like','%'.$materno.'%');
                            }
                        }
                        if( $r->has('solopendiente') AND trim($r->solopendiente)==1 ){
                            $query->whereNull('llac.respuesta');
                        }
                }
            );

            $result=array();
            if( Input::has('solopendiente') AND trim($r->solopendiente)==1 ){
                $result = $sql->orderBy('llac.fecha_registro','desc')->paginate(10);
            }
            else{
                $result = $sql->orderBy('llac.fecha_registro','desc')->get();
            }

        return $result;
    }
    // --
}
