<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Trabajador extends Model
{
    protected   $table = 'mat_trabajadores';

    
    public static function runEditStatus($r)
    {
        $trabajador = Trabajador::find($r->id);
        $trabajador->estado = trim( $r->estadof );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $empresa_id = Auth::user()->empresa_id;
        $trabajador = new Trabajador;
        $trabajador->empresa_id = $empresa_id;
        $trabajador->persona_id = trim( $r->persona_id );
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->tarea_id = trim( $r->tarea_id );
        $trabajador->medio_captacion_id = null;
        if( $r->has('rol_id') AND $r->rol_id=='1' ){
            $trabajador->medio_captacion_id = trim( $r->medio_captacion_id );
        }
        $trabajador->centro_operacion_id = trim( $r->centro_operacion_id );
        $trabajador->remuneracion = trim( $r->remuneracion );
        $trabajador->horario = trim( $r->horario );
        if( $r->has('fecha_ingreso') AND trim($r->fecha_ingreso) != '' ){
            $trabajador->fecha_ingreso = trim( $r->fecha_ingreso );
        }
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_created_at=Auth::user()->id;
        $trabajador->save();

        $trabajador->codigo = 'VEN - '.trim( $r->persona_id );
        $trabajador->save();

        $trabajadorHistorico = new TrabajadorHistorico;
        $trabajadorHistorico->trabajador_id = $trabajador->id;
        $trabajadorHistorico->empresa_id = $trabajador->empresa_id;
        $trabajadorHistorico->persona_id = $trabajador->persona_id;
        $trabajadorHistorico->rol_id = $trabajador->rol_id;
        $trabajadorHistorico->codigo = $trabajador->codigo;
        $trabajadorHistorico->tarea_id = $trabajador->tarea_id;
        $trabajadorHistorico->medio_captacion_id = $trabajador->medio_captacion_id;
        $trabajadorHistorico->centro_operacion_id = $trabajador->centro_operacion_id;
        $trabajadorHistorico->remuneracion = $trabajador->remuneracion;
        $trabajadorHistorico->horario = $trabajador->horario;
        $trabajadorHistorico->fecha_ingreso = $trabajador->fecha_ingreso;
        $trabajadorHistorico->estado = 1;
        $trabajadorHistorico->persona_id_created_at=Auth::user()->id;
        $trabajadorHistorico->save();
        DB::commit();
    }
    public static function runEdit($r)
    {
        DB::beginTransaction();
        $trabajador = Trabajador::find($r->id);
        $trabajadorHistoricoA = TrabajadorHistorico::where('trabajador_id', $trabajador->id)->whereNull('fecha_termino')->first();
        
        if( $r->has('fecha_termino') AND trim($r->fecha_termino) != '' ){
            if( isset($trabajadorHistoricoA->id) ){
                $trabajadorHistoricoA->fecha_termino = $r->fecha_termino;
                $trabajadorHistoricoA->observacion = $r->observacion;
                $trabajadorHistoricoA->persona_id_updated_at=Auth::user()->id;
                $trabajadorHistoricoA->save();
            }

            $trabajadorHistorico = new TrabajadorHistorico;
            $trabajadorHistorico->trabajador_id = $trabajador->id;
            $trabajadorHistorico->empresa_id = $trabajador->empresa_id;
            $trabajadorHistorico->persona_id = $trabajador->persona_id;
            $trabajadorHistorico->rol_id = $trabajador->rol_id;
            $trabajadorHistorico->codigo = $trabajador->codigo;
            $trabajadorHistorico->tarea_id = $trabajador->tarea_id;
            $trabajadorHistorico->medio_captacion_id = $trabajador->medio_captacion_id;
            $trabajadorHistorico->centro_operacion_id = $trabajador->centro_operacion_id;
            $trabajadorHistorico->remuneracion = $trabajador->remuneracion;
            $trabajadorHistorico->horario = $trabajador->horario;
            $trabajadorHistorico->fecha_ingreso = $trabajador->fecha_ingreso;
            $trabajadorHistorico->estado = 1;
            $trabajadorHistorico->persona_id_created_at=Auth::user()->id;
            $trabajadorHistorico->save();
        }
        
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->tarea_id = trim( $r->tarea_id );
        $trabajador->medio_captacion_id = null;
        if( $r->has('rol_id') AND $r->rol_id=='1' ){
            $trabajador->medio_captacion_id = trim( $r->medio_captacion_id );
        }
        $trabajador->centro_operacion_id = trim( $r->centro_operacion_id );
        $trabajador->remuneracion = trim( $r->remuneracion );
        $trabajador->horario = trim( $r->horario );
        if( $r->has('fecha_ingreso') AND trim($r->fecha_ingreso) != '' ){
            $trabajador->fecha_ingreso = trim( $r->fecha_ingreso );
        }
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();


        if( isset($trabajadorHistoricoA->fecha_termino) AND trim($trabajadorHistoricoA->fecha_termino) == '' ){
            $trabajadorHistoricoA->rol_id = $trabajador->rol_id;
            $trabajadorHistoricoA->tarea_id = $trabajador->tarea_id;
            $trabajadorHistoricoA->medio_captacion_id = $trabajador->medio_captacion_id;
            $trabajadorHistoricoA->centro_operacion_id = $trabajador->centro_operacion_id;
            $trabajadorHistoricoA->remuneracion = $trabajador->remuneracion;
            $trabajadorHistoricoA->horario = $trabajador->horario;
            $trabajadorHistoricoA->fecha_ingreso = $trabajador->fecha_ingreso;
            $trabajadorHistoricoA->persona_id_updated_at=Auth::user()->id;
            $trabajadorHistoricoA->save();
        }

        DB::commit();
    }

    public static function runLoad($r)
    {
        $sql=DB::table('mat_trabajadores AS t')
             ->join('personas as p','p.id','=','t.persona_id')
             ->join('mat_roles as r','r.id','=','t.rol_id')
             ->join('mat_tareas as ta','ta.id','=','t.tarea_id')
             ->leftJoin('mat_centro_operaciones as co','co.id','=','t.centro_operacion_id')
             ->select('t.codigo','t.id','t.persona_id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as trabajador')
             ,'t.rol_id','r.rol','t.tarea_id','ta.tarea','t.estado','t.medio_captacion_id','t.centro_operacion_id', 't.remuneracion'
             ,'t.horario', 't.fecha_ingreso', 'co.centro_operacion')
             ->where('t.empresa_id', Auth::user()->empresa_id)
             ->where( 
                function($query) use ($r){
                    if( $r->has("trabajador") ){
                        $trabajador=trim($r->trabajador);
                        if( $trabajador !='' ){
                            $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) like "%'.$trabajador.'%"');
                        }
                    }
                    if( $r->has("rol") ){
                        $rol=trim($r->rol);
                        if( $rol !='' ){
                            $query->where('r.rol','like','%'.$rol.'%');
                        }
                    }
                    if( $r->has("rol_id") ){
                        $rol_id=trim($r->rol_id);
                        if( $rol_id !='' ){
                            $query->where('t.rol_id','=',$rol_id);
                        }
                    }
                    if( $r->has("tarea") ){
                        $tarea=trim($r->tarea);
                        if( $tarea !='' ){
                            $query->where('ta.tarea','like','%'.$tarea.'%');
                        }
                    }
                    if( $r->has("tarea_id") ){
                        $tarea_id=trim($r->tarea_id);
                        if( $tarea_id !='' ){
                            $query->where('t.tarea_id','=',$tarea_id);
                        }
                    }
                    if( $r->has("medio_captacion_id") ){
                        $medio_captacion_id=trim($r->medio_captacion_id);
                        if( $medio_captacion_id !='' ){
                            $query->where('t.medio_captacion_id','=',$medio_captacion_id);
                        }
                    }
                    if( $r->has("centro_operacion") ){
                        $centro_operacion=trim($r->centro_operacion);
                        if( $centro_operacion !='' ){
                            $query->where('co.centro_operacion','like','%'.$centro_operacion.'%');
                        }
                    }
                    if( $r->has("codigo") ){
                        $codigo=trim($r->codigo);
                        if( $codigo !='' ){
                            $query->where('t.codigo','like','%'.$codigo.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('t.estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('t.id','asc')->paginate(10);
        return $result;
    }

    public static function runLoadHistorico($r)
    {
        $sql=DB::table('mat_trabajadores_historico AS t')
             ->join('personas as p','p.id','=','t.persona_id')
             ->join('mat_roles as r','r.id','=','t.rol_id')
             ->join('mat_tareas as ta','ta.id','=','t.tarea_id')
             ->leftJoin('mat_centro_operaciones as co','co.id','=','t.centro_operacion_id')
             ->leftJoin('mat_medios_captaciones as mc','mc.id','=','t.medio_captacion_id')
             ->select('t.codigo','t.id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as trabajador')
             ,'r.rol' ,'ta.tarea' ,'mc.medio_captacion','co.centro_operacion', 't.remuneracion'
             ,'t.horario', 't.fecha_ingreso', 't.fecha_termino', 't.observacion')
             ->where('t.empresa_id', Auth::user()->empresa_id)
             ->where( 
                function($query) use ($r){
                    if( $r->has("id") ){
                        $id=trim($r->id);
                        if( $id !='' ){
                            $query->where('t.trabajador_id', $id);
                        }
                    }
                }
            );
        $result = $sql->orderBy('t.id','desc')->get();
        return $result;
    }
    
    public static function ListarTeleoperadora($r)
    {
        $empresa_id= Auth::user()->empresa_id;
        if( $r->has('empresa') ){
            $empresa_id= $r->empresa;
        }
        $sql=Trabajador::select('mat_trabajadores.codigo','mat_trabajadores.id','mat_trabajadores.persona_id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as trabajador')
                                ,'mat_trabajadores.rol_id','r.rol','mat_trabajadores.estado')
             ->join('personas as p','p.id','=','mat_trabajadores.persona_id')
             ->join('mat_roles as r','r.id','=','mat_trabajadores.rol_id')
             ->where('mat_trabajadores.empresa_id', $empresa_id)
             ->where('p.estado',1)
             ->where( 
                function($query) use ($r){
                    if( $r->has("trabajador") ){
                        $trabajador=trim($r->trabajador);
                        if( $trabajador !='' ){
                            $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) like "%'.$trabajador.'%"');
                        }
                    }
                    if( $r->has("rol") ){
                        $rol=trim($r->rol);
                        if( $rol !='' ){
                            $query->where('r.rol','like','%'.$rol.'%');
                        }
                    }
                    if( $r->has("rol_id") ){
                        $rol_id=trim($r->rol_id);
                        if( $rol_id !='' ){
                            $query->where('mat_trabajadores.rol_id','=',$rol_id);
                        }
                    }
                    if( $r->has("codigo") ){
                        $codigo=trim($r->codigo);
                        if( $codigo !='' ){
                            $query->where('mat_trabajadores.codigo','like','%'.$codigo.'%');
                        }
                    }
                    
                    $query->where('mat_trabajadores.estado','=',1);
                }
            );
        $result = $sql->orderBy('trabajador','asc')->get();
        return $result;
    }

}
