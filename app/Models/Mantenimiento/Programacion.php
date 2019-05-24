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

        if( !$r->has('fecha_campaña') ){
            $r->fecha_campaña=date("Y-m-d",strtotime($r->fecha_inicio));
        }
        $sucursal->fecha_campaña = $r->fecha_campaña;

        if( $r->has('meta_max') AND $r->meta_max!='' ){
            $sucursal->meta_max = trim( $r->meta_max );
        }

        if( $r->has('meta_min') AND $r->meta_min!='' ){
            $sucursal->meta_min = trim( $r->meta_min );
        }

        $sucursal->link = trim( $r->link );
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
        if( count($r->dia)>0 ){
            $dia=implode(",", $r->dia);
            $sucursal->dia = trim( $dia );
        }
        $sucursal->fecha_inicio = trim( $r->fecha_inicio );
        $sucursal->fecha_final = trim( $r->fecha_final );
        
        if( !$r->has('fecha_campaña') ){
            $r->fecha_campaña=date("Y-m-d",strtotime($r->fecha_inicio));
        }
        $sucursal->fecha_campaña = $r->fecha_campaña;

        if( $r->has('meta_max') AND $r->meta_max!='' ){
            $sucursal->meta_max = trim( $r->meta_max );
        }

        if( $r->has('meta_min') AND $r->meta_min!='' ){
            $sucursal->meta_min = trim( $r->meta_min );
        }

        $sucursal->link = trim( $r->link );
        $sucursal->estado = trim( $r->estado );
        $sucursal->persona_id_created_at=Auth::user()->id;
        $sucursal->save();
    }

    public static function runLoad($r)
    {

        $sql=DB::table('mat_programaciones as mp')
             ->select('mp.dia','mp.id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) as persona'),'mp.persona_id','mp.docente_id','c.curso','mp.curso_id','mp.sucursal_id','s.sucursal','mp.aula','mp.fecha_inicio','mp.fecha_final','mp.fecha_campaña','mp.meta_max','mp.meta_min','mp.estado','mp.link',
                'mp.cv_archivo','mp.temario_archivo')
             ->join('personas as p','p.id','=','mp.persona_id')
             ->join('sucursales as s','s.id','=','mp.sucursal_id')
             ->join('mat_cursos as c','c.id','=','mp.curso_id')
             ->where( 
                function($query) use ($r){
                    if( $r->has("persona_id") ){
                        $persona_id=trim($r->persona_id);
                        if( $persona_id !='' ){
                            $query->where('p.id','=',$persona_id);
                        }
                    }
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
                            $query->where('mp.aula','like',$aula.'%');
                        }
                    }
                    if( $r->has("dia") ){
                        $dia=trim($r->dia);
                        if( $dia !='' ){
                            $query->where('mp.dia','like',$dia.'%');
                        }
                    }
                    if( $r->has("inicio") ){
                        $inicio=trim($r->inicio);
                        if( $inicio !='' ){
                            $query->where('mp.fecha_inicio','like','%'.$inicio.'%');
                        }
                    }
                    if( $r->has("final") ){
                        $final=trim($r->final);
                        if( $final !='' ){
                            $query->where('mp.fecha_final','like','%'.$final.'%');
                        }
                    }
                    if( $r->has("campaña") ){
                        $campaña=trim($r->campaña);
                        if( $campaña !='' ){
                            $query->where('mp.fecha_campaña','like','%'.$campaña.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mp.estado','=',$estado);
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
                                $query->where('mp.sucursal_id','=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==1){
                                $query->where('mp.sucursal_id','!=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==2){
                                $query->where('mp.sucursal_id','=',1);
                            }
                            
                        }
                    }
                    else if( $r->has("tipo_modalidad") ){
                        $tipo_modalidad=trim($r->tipo_modalidad);
                        if( $tipo_modalidad !='' ){
                            if($tipo_modalidad==0){
                                $query->where('mp.sucursal_id','=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==1){
                                $query->where('mp.sucursal_id','!=',$tipo_modalidad);
                            }
                            if($tipo_modalidad==2){
                                $query->where('mp.sucursal_id','=',1);
                            }
                            
                        }
                    }
                }
            );
        $result = $sql->orderBy('mp.fecha_inicio','desc')->paginate(10);
        return $result;
    }
    
    public static function RegistrarArchivo($r)
    {
        $programacion= Programacion::find($r->id);
        $extension='';
        if( trim($r->cv_nombre)!='' ){
            $type=explode(".",$r->cv_nombre);
            $extension=".".$type[1];
        }
        $url = "img/programacion/cv_".$programacion->id.$extension; 
        if( trim($r->cv_archivo)!='' ){
            $programacion->cv_archivo=$url;
            Menu::fileToFile($r->cv_archivo, $url);
        }

        if( trim($r->temario_nombre)!='' ){
            $type=explode(".",$r->temario_nombre);
            $extension=".".$type[1];
        }
        $url = "img/programacion/temario_".$programacion->id.$extension; 
        if( trim($r->temario_archivo)!='' ){
            $programacion->temario_archivo=$url;
            Menu::fileToFile($r->temario_archivo, $url);
        }
        $programacion->persona_id_created_at=Auth::user()->id;
        $programacion->save();
    }
}
