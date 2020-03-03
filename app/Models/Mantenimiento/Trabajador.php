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
        $empresa_id = Auth::user()->empresa_id;
        $trabajador = new Trabajador;
        $trabajador->empresa_id = $empresa_id;
        $trabajador->persona_id = trim( $r->persona_id );
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->tarea_id = trim( $r->tarea_id );
        $trabajador->codigo = trim( $r->codigo );
        $trabajador->medio_captacion_id = null;
        if( $r->has('rol_id') AND $r->rol_id=='1' ){
            $trabajador->medio_captacion_id = trim( $r->medio_captacion_id );
        }
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_created_at=Auth::user()->id;
        $trabajador->save();
    }
    public static function runEdit($r)
    {

        $trabajador = Trabajador::find($r->id);
        $trabajador->persona_id = trim( $r->persona_id );
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->tarea_id = trim( $r->tarea_id );
        $trabajador->codigo = trim( $r->codigo );
        $trabajador->medio_captacion_id = null;
        if( $r->has('rol_id') AND $r->rol_id=='1' ){
            $trabajador->medio_captacion_id = trim( $r->medio_captacion_id );
        }
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runLoad($r)
    {
        $sql=DB::table('mat_trabajadores AS t')
             ->join('personas as p','p.id','=','t.persona_id')
             ->join('mat_roles as r','r.id','=','t.rol_id')
             ->join('mat_tareas as ta','ta.id','=','t.tarea_id')
             ->select('t.codigo','t.id','t.persona_id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as trabajador')
             ,'t.rol_id','r.rol','t.tarea_id','ta.tarea','t.estado','t.medio_captacion_id')
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
