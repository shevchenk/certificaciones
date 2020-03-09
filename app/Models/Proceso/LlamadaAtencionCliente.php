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
        DB::beginTransaction();
        DB::table('llamadas_atencion_cliente')
        ->where( 
            function($query) use ($r){
                if( $r->has('matricula_detalle_id') AND $r->matricula_detalle_id!='' ){
                    $query->where('matricula_detalle_id',$r->matricula_detalle_id);
                }
                else{
                    $query->where('persona_id','=', $r->persona_id)
                    ->whereNull('matricula_detalle_id');
                }
            }
        )
        ->where('ultimo_registro',1)
        ->update([
            'ultimo_registro' => 0,
            'persona_id_updated_at' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $llamada= new LlamadaAtencionCliente;
        $llamada->persona_id=$r->persona_id;
        if( $r->has('matricula_detalle_id') AND $r->matricula_detalle_id!='' ){
            $llamada->matricula_detalle_id=$r->matricula_detalle_id;
        }
        $llamada->fecha_registro=date('Y-m-d');
        $llamada->comentario= trim( $r->comentario );
        $llamada->persona_id_created_at=Auth::user()->id;
        $llamada->save();
        DB::commit();
        return 'Ok';
    }

    public static function CerrarLlamada($r)
    {
        DB::beginTransaction();
        $valida=DB::table('llamadas_atencion_cliente')
        ->where( 
            function($query) use ($r){
                if( $r->has('matricula_detalle_id') AND $r->matricula_detalle_id!='' ){
                    $query->where('matricula_detalle_id',$r->matricula_detalle_id);
                }
                else{
                    $query->where('persona_id','=', $r->persona_id)
                    ->whereNull('matricula_detalle_id');
                }
            }
        )
        ->whereNull('respuesta')
        ->exists();

        $return['rst'] = 2;
        $return['msj'] = 'Solo se puede cerrar la llamada, cuando no exista respuesta pendiente';
        if( $valida==false ){
            DB::table('llamadas_atencion_cliente')
            ->where( 
                function($query) use ($r){
                    if( $r->has('matricula_detalle_id') AND $r->matricula_detalle_id!='' ){
                        $query->where('matricula_detalle_id',$r->matricula_detalle_id);
                    }
                    else{
                        $query->where('persona_id','=', $r->persona_id)
                        ->whereNull('matricula_detalle_id');
                    }
                }
            )
            ->where('ultimo_registro',1)
            ->update([
                'cierre' => $r->comentario,
                'fecha_cierre' => date('Y-m-d'),
                'cerrar_llamada' => 1,
                'ultimo_registro' => 2,
                'persona_id_updated_at' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $return['rst'] = 1;
            $return['msj'] = 'Registro realizado';
        }
        DB::commit();

        return $return;
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
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','llac.persona_id');
            })
            ->leftJoin('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.id','=','llac.matricula_detalle_id');
            })
            ->leftJoin('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('mat_cursos AS c', function($join){
                $join->on('c.id','=','mp.curso_id');
            })
            ->select(
            'llac.id','llac.fecha_registro','llac.comentario',
            'llac.fecha_respuesta','llac.respuesta','llac.cerrar_llamada',
            'p.paterno','p.materno','p.nombre','llac.persona_id','p.dni','p.telefono','p.celular',
            DB::raw('DATE(mp.fecha_inicio) AS fecha_seminario'),'c.curso AS seminario',
            'llac.matricula_detalle_id'
            )
            ->where( 
                function($query) use ($r){
                    if( Input::has('matricula_detalle_id') AND trim($r->matricula_detalle_id) !='' ){
                        $query->where('mmd.id','=', $r->matricula_detalle_id);
                    }
                    elseif( Input::has('persona_id') AND trim($r->persona_id)!='' ){
                        $query->where('llac.persona_id','=', $r->persona_id)
                        ->whereNull('llac.matricula_detalle_id');
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
                    if( $r->has("fecha_inicio") && $r->has("fecha_final") ){
                        $fecha_inicio=trim($r->fecha_inicio);
                        $fecha_final=trim($r->fecha_final);
                        if( $fecha_final !='' AND $fecha_inicio !='' ){
                            $query->whereBetween('llac.fecha_registro', array($fecha_inicio,$fecha_final));
                        }
                    }
                }
            )
            ->where(
                function($query) use ($r){
                    if( $r->has('pendiente') AND trim($r->pendiente)==1 ){
                        $query->whereNull('llac.respuesta')
                        ->orWhere(function($dquery){
                            $dquery->where([
                                ['llac.cerrar_llamada',0],
                                ['llac.ultimo_registro',1],
                            ]);
                        });
                    }
                }
            );

            $result=array();
            if( Input::has('solopendiente') AND trim($r->solopendiente)==1 ){
                $result = $sql->orderBy('llac.fecha_registro','desc')->orderBy('llac.id','desc')->paginate(10);
            }
            else{
                $result = $sql->orderBy('llac.fecha_registro','desc')->orderBy('llac.id','desc')->get();
            }

        return $result;
    }
    // --
}
