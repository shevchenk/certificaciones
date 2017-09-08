<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Programacion extends Model
{
    protected   $table = 'mat_programaciones';

    public static function runEditStatus($r)
    {

        $programacion= Programacion::find($r->id);
        $programacion->estado = trim( $r->estadof );
        $programacion->persona_id_updated_at=Auth::user()->id;
        $programacion->save();
    }

    public static function runNew($r)
    {
        $sucursal = new Programacion;
        $sucursal->persona_id = trim( $r->persona_id);
        $sucursal->docente_id = trim( $r->docente_id );
        $sucursal->curso_id = trim( $r->curso_id);
        $sucursal->sucursal_id = trim( $r->sucursal_id );
        $sucursal->aula = trim( $r->aula );
        if( count($r->dia)>0 ){
            $dia=implode(",", $r->dia);
            $sucursal->dia = trim( $dia );
        }
        $sucursal->fecha_inicio = trim( $r->fecha_inicio );
        $sucursal->fecha_final = trim( $r->fecha_final );
        $sucursal->estado = trim( $r->estado );
        $sucursal->persona_id_created_at=Auth::user()->id;
        $sucursal->save();
    }

    public static function runEdit($r)
    {
        $sucursal = Programacion::find($r->id);
        $sucursal->persona_id = trim( $r->persona_id);
        $sucursal->docente_id = trim( $r->docente_id );
        $sucursal->curso_id = trim( $r->curso_id);
        $sucursal->sucursal_id = trim( $r->sucursal_id );
        $sucursal->aula = trim( $r->aula );
        $dia=implode(",", $r->dia);
        $sucursal->dia = trim( $dia );
        $sucursal->fecha_inicio = trim( $r->fecha_inicio );
        $sucursal->fecha_final = trim( $r->fecha_final );
        $sucursal->estado = trim( $r->estado );
        $sucursal->persona_id_created_at=Auth::user()->id;
        $sucursal->save();
    }

    public static function runLoad($r)
    {

        $sql=Programacion::select('mat_programaciones.dia','mat_programaciones.id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) as persona'),'mat_programaciones.persona_id','mat_programaciones.docente_id','c.curso','mat_programaciones.curso_id','mat_programaciones.sucursal_id',
                                  's.sucursal','mat_programaciones.aula','mat_programaciones.fecha_inicio','mat_programaciones.fecha_final','mat_programaciones.estado')
             ->join('personas as p','p.id','=','mat_programaciones.persona_id')
             ->join('sucursales as s','s.id','=','mat_programaciones.sucursal_id')
             ->join('mat_cursos as c','c.id','=','mat_programaciones.curso_id')
             ->where( 
                function($query) use ($r){
                    if( $r->has("docente") ){
                        $docente=trim($r->docente);
                        if( $docente !='' ){
                            $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) like "%'.$docente.'%"');
                        }
                    }
                    if( $r->has("sucursal") ){
                        $sucursal=trim($r->sucursal);
                        if( $sucursal !='' ){
                            $query->where('s.sucursal','like','%'.$sucursal.'%');
                        }
                    }
                    if( $r->has("curso") ){
                        $curso=trim($r->curso);
                        if( $curso !='' ){
                            $query->where('c.curso','like','%'.$curso.'%');
                        }
                    }
                    if( $r->has("aula") ){
                        $aula=trim($r->aula);
                        if( $aula !='' ){
                            $query->where('mat_programaciones.aula','like',$aula.'%');
                        }
                    }
                    if( $r->has("dia") ){
                        $dia=trim($r->dia);
                        if( $dia !='' ){
                            $query->where('mat_programaciones.dia','like',$dia.'%');
                        }
                    }
                    if( $r->has("inicio") ){
                        $inicio=trim($r->inicio);
                        if( $inicio !='' ){
                            $query->where('mat_programaciones.fecha_inicio','like','%'.$inicio.'%');
                        }
                    }
                    if( $r->has("final") ){
                        $final=trim($r->final);
                        if( $final !='' ){
                            $query->where('mat_programaciones.fecha_final','like','%'.$final.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mat_programaciones.estado','=',$estado);
                        }
                    }
                    if( $r->has("tipo_curso") ){
                        $tipo_curso=trim($r->tipo_curso);
                        if( $tipo_curso !='' ){
                            $query->where('c.tipo_curso','=',$tipo_curso);
                        }
                    }
                    if( $r->has("tipo_modalidad_id") ){
                        $tipo_modalidad=trim($r->tipo_modalidad_id);
                        if( $tipo_modalidad !='' ){
                            if($tipo_modalidad==0){
                                $query->where('mat_programaciones.sucursal_id','=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==1){
                                $query->where('mat_programaciones.sucursal_id','!=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==2){
                                $query->where('mat_programaciones.sucursal_id','=',1);
                            }
                            
                        }
                    }
                    else if( $r->has("tipo_modalidad") ){
                        $tipo_modalidad=trim($r->tipo_modalidad);
                        if( $tipo_modalidad !='' ){
                            if($tipo_modalidad==0){
                                $query->where('mat_programaciones.sucursal_id','=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==1){
                                $query->where('mat_programaciones.sucursal_id','!=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==2){
                                $query->where('mat_programaciones.sucursal_id','=',1);
                            }
                            
                        }
                    }
                }
            );
        $result = $sql->orderBy('mat_programaciones.id','asc')->paginate(10);
        return $result;
    }
        
}
