<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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
        $sucursal_id = $r->sucursal_id;
        $curso_id =  $r->curso_id;

        if( is_array($sucursal_id) AND is_array($curso_id) ){
            DB::beginTransaction();
            for ($j=0; $j < count($curso_id) ; $j++) {
                for ($i=0; $i < count($sucursal_id) ; $i++) { 
                    $dia = '';
                    if( $r->has('dia') AND count($r->dia)>0 ){
                        $dia=implode(",", $r->dia);
                    }

                    $valida = Programacion::where('docente_id', $r->docente_id)
                            ->where('curso_id', $curso_id[$j])
                            ->where('sucursal_id', $sucursal_id[$i])
                            ->where('fecha_inicio', $r->fecha_inicio)
                            ->where('fecha_final', $r->fecha_final)
                            ->where( 
                                function($query) use ($dia){
                                    if( $dia != '' ){
                                        $query->where('dia',$dia );
                                    }
                                }
                            )
                            ->first();
                    
                        $programacion = array();
                    if( !isset($valida->id) ){
                        $programacion = new Programacion;
                    }
                    else{
                        $programacion = Programacion::find($valida->id);
                    }
                        $programacion->persona_id = trim( $r->persona_id);
                        $programacion->docente_id = trim( $r->docente_id );
                        $programacion->curso_id = trim( $curso_id[$j]);
                        $programacion->sucursal_id = $sucursal_id[$i];
                        $programacion->aula = trim( $r->aula );
                        $programacion->turno = trim( $r->turno );
                        if( $r->has('dia') AND count($r->dia)>0 ){
                            $programacion->dia = trim( $dia );
                        }
                        $programacion->fecha_inicio = trim( $r->fecha_inicio );
                        $programacion->fecha_final = trim( $r->fecha_final );
    
                        if( $r->has('hora_inicio') ){
                            $programacion->fecha_inicio = trim( $r->fecha_inicio )." ".trim( $r->hora_inicio );
                            $programacion->fecha_final = trim( $r->fecha_final )." ".trim( $r->hora_final );
                        }
    
                        if( !$r->has('fecha_campaña') ){
                            $r->fecha_campaña=date("Y-m-d",strtotime($r->fecha_inicio));
                        }
                        $programacion->fecha_campaña = $r->fecha_campaña;
    
                        if( $r->has('meta_max') AND $r->meta_max!='' ){
                            $programacion->meta_max = trim( $r->meta_max );
                        }
    
                        if( $r->has('meta_min') AND $r->meta_min!='' ){
                            $programacion->meta_min = trim( $r->meta_min );
                        }
    
                        $programacion->costo = trim( $r->costo );
                        if( $r->has('costo_ins') AND $r->costo_ins!='' ){
                            $programacion->costo_ins = trim( $r->costo_ins );
                            $programacion->costo_mat = trim( $r->costo_mat );
                        }
    
                        if( $r->has('anio') AND $r->anio!='' ){
                            $programacion->semestre = trim( $r->anio )."-".trim( $r->anio1 )." ".trim( $r->anio2 );
                        }
                        
                        $programacion->link = trim( $r->link );
                        $programacion->estado = trim( $r->estado );
                        $programacion->persona_id_created_at=Auth::user()->id;
                        $programacion->save();
                }
            }
            DB::commit();
        }
        else{
            $sucursal = new Programacion; 
            $sucursal->persona_id = trim( $r->persona_id);
            $sucursal->docente_id = trim( $r->docente_id );
            $sucursal->curso_id = trim( $r->curso_id);
            $sucursal->sucursal_id = trim( $r->sucursal_id );
            $sucursal->aula = trim( $r->aula );
            $sucursal->turno = trim( $r->turno );
            if( $r->has('dia') AND count($r->dia)>0 ){
                $dia=implode(",", $r->dia);
                $sucursal->dia = trim( $dia );
            }
            $sucursal->fecha_inicio = trim( $r->fecha_inicio );
            $sucursal->fecha_final = trim( $r->fecha_final );

            if( $r->has('hora_inicio') ){
                $sucursal->fecha_inicio = trim( $r->fecha_inicio )." ".trim( $r->hora_inicio );
                $sucursal->fecha_final = trim( $r->fecha_final )." ".trim( $r->hora_final );
            }

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

            $sucursal->costo = trim( $r->costo );
            if( $r->has('costo_ins') AND $r->costo_ins!='' ){
                $sucursal->costo_ins = trim( $r->costo_ins );
                $sucursal->costo_mat = trim( $r->costo_mat );
            }

            if( $r->has('anio') AND $r->anio!='' ){
                $sucursal->semestre = trim( $r->anio )."-".trim( $r->anio1 )." ".trim( $r->anio2 );
            }
            
            $sucursal->link = trim( $r->link );
            $sucursal->estado = trim( $r->estado );
            $sucursal->persona_id_created_at=Auth::user()->id;
            $sucursal->save();
        }
    }

    public static function runEdit($r)
    {
        $sucursal = Programacion::find($r->id);
        $sucursal->persona_id = trim( $r->persona_id);
        $sucursal->docente_id = trim( $r->docente_id );
        if( $r->has('curso_id') AND $r->curso_id!='' ){
            $sucursal->curso_id = trim( $r->curso_id);
        }

        if( $r->has('sucursal_id') ){
            $sucursal->sucursal_id = trim( $r->sucursal_id);
        }

        $sucursal->aula = trim( $r->aula );
        $sucursal->turno = trim( $r->turno );
        if( $r->has('dia') AND count($r->dia)>0 ){
            $dia=implode(",", $r->dia);
            $sucursal->dia = trim( $dia );
        }
        $sucursal->fecha_inicio = trim( $r->fecha_inicio );
        $sucursal->fecha_final = trim( $r->fecha_final );

        if( $r->has('hora_inicio') ){
            $sucursal->fecha_inicio = trim( $r->fecha_inicio )." ".trim( $r->hora_inicio );
            $sucursal->fecha_final = trim( $r->fecha_final )." ".trim( $r->hora_final );
        }
        
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

        $sucursal->costo = trim( $r->costo );
        if( $r->has('costo_ins') AND $r->costo_ins!='' ){
            $sucursal->costo_ins = trim( $r->costo_ins );
            $sucursal->costo_mat = trim( $r->costo_mat );
        }

        if( $r->has('anio') AND $r->anio!='' ){
            $sucursal->semestre = trim( $r->anio )."-".trim( $r->anio1 )." ".trim( $r->anio2 );
        }

        $sucursal->link = trim( $r->link );
        $sucursal->estado = trim( $r->estado );
        $sucursal->persona_id_created_at=Auth::user()->id;
        $sucursal->save();
    }

    public static function runLoad($r)
    {
        $sql=DB::table('mat_programaciones as mp')
             ->select('mp.dia','mp.id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) as persona'),'mp.persona_id','mp.docente_id','c.curso','mp.curso_id','mp.sucursal_id','s.sucursal','mp.aula','mp.fecha_inicio','mp.fecha_final','mp.fecha_campaña','mp.meta_max','mp.meta_min','mp.estado','mp.link','c.tipo_curso','c.tipo_inicio_curso','mp.semestre',
                'mp.cv_archivo','mp.temario_archivo','mp.diapo_archivo','mp.diapoedit_archivo',
                'mp.grabo','mp.publico','mp.expositor','mp.situaciones','p.celular','p.telefono'
                ,'p.email','mp.costo','mp.costo_ins','mp.costo_mat','mp.turno')
             ->join('personas as p','p.id','=','mp.persona_id')
             ->join('sucursales as s','s.id','=','mp.sucursal_id')
             ->join('mat_cursos AS c',function($join) use( $r ){
                $join->on('c.id','=','mp.curso_id')
                ->where('c.estado','1');
                if( !$r->has('habilita_docente') ){
                    $join->where('c.empresa_id',Auth::user()->empresa_id);
                }
            })
             ->where( 
                function($query) use ($r){
                    if( $r->has("persona_id") ){
                        $persona_id=trim($r->persona_id);
                        if( $persona_id !='' ){
                            $query->where('p.id','=',$persona_id);
                        }
                    }
                    if( $r->has("curso_id") ){
                        $curso_id=trim($r->curso_id);
                        if( $curso_id !='' ){
                            $query->where('mp.curso_id','=',$curso_id);
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
                            $query->where('mp.dia','like','%'.$dia.'%');
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
                    if( $r->has("hora_inicio") ){
                        $hora_inicio=trim($r->hora_inicio);
                        if( $hora_inicio !='' ){
                            $query->where('mp.fecha_inicio','like','%'.$hora_inicio.'%');
                        }
                    }
                    if( $r->has("hora_final") ){
                        $hora_final=trim($r->hora_final);
                        if( $hora_final !='' ){
                            $query->where('mp.fecha_final','like','%'.$hora_final.'%');
                        }
                    }
                    if( $r->has("semestre") ){
                        $semestre=trim($r->semestre);
                        if( $semestre !='' ){
                            $query->where('mp.semestre','like','%'.$semestre.'%');
                        }
                    }
                    if( $r->has("campaña") ){
                        $campaña=trim($r->campaña);
                        if( $campaña !='' ){
                            $query->where('mp.fecha_campaña','like','%'.$campaña.'%');
                        }
                    }
                    if( $r->has("estado") or $r->has("estado_filtro") ){
                        $estado='2';
                        if($r->has('estado_filtro')){
                            $estado=trim($r->estado_filtro);
                        }
                        else{
                            $estado=trim($r->estado);
                        }

                        if( $estado !='' ){
                            $query->where('mp.estado','=',$estado);
                        }
                    }
                    if( $r->has("turno") ){
                        $turno=trim($r->turno);
                        if( $turno !='' ){
                            $query->where('mp.turno','=',$turno);
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

            if( $r->has('especialidad_id') ){
                $sql->join('mat_cursos_especialidades AS ce',function($join) use( $r ){
                    $join->on('ce.curso_id','=','mp.curso_id');
                    $join->where('ce.especialidad_id',$r->especialidad_id);
                    $join->where('ce.estado',1);
                });
            }

            if( $r->has('especialidad_persona_id') ){
                $persona_id = $r->especialidad_persona_id;
                $sql->leftJoin(DB::raw("
                    (SELECT md.curso_id 
                    FROM mat_matriculas_detalles md
                    INNER JOIN mat_matriculas m ON m.id = md.matricula_id AND m.estado=1 AND m.persona_id = $persona_id
                    WHERE md.estado=1) AS ca"
                    ),'ca.curso_id','=','c.id');
                $sql->whereNull('ca.curso_id');
            }
        $result = $sql->orderBy('mp.fecha_inicio','desc')->paginate(10);
        return $result;
    }

    public static function runLoadEvaluaciones($r)
    {
        $sql=DB::table('mat_programaciones as mp')
             ->select('mp.dia','mp.id','c.curso','s.sucursal','mp.aula','mp.fecha_inicio','mp.peso_trabajo_final','mp.trabajo_final',
                'mp.activa_evaluacion',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) AS persona'),
                DB::raw('IF(mp.activa_evaluacion=1, 
                        GROUP_CONCAT(te.tipo_evaluacion," (",mpe.peso_evaluacion,") ", IF(mpe.activa_fecha=0,"", CONCAT(" desde ", 
                        mpe.fecha_evaluacion_ini, " al " ,mpe.fecha_evaluacion_fin))  ORDER BY mpe.orden SEPARATOR "|"),
                        "") AS evaluacion')
             )
             ->join('personas as p','p.id','=','mp.persona_id')
             ->join('sucursales as s','s.id','=','mp.sucursal_id')
             ->join('mat_cursos AS c',function($join){
                $join->on('c.id','=','mp.curso_id')
                ->where('c.empresa_id',Auth::user()->empresa_id);
             })
             ->leftJoin('mat_programaciones_evaluaciones AS mpe',function($join){
                $join->on('mpe.programacion_id','=','mp.id')
                ->where('mpe.estado','=',1);
             })
             ->leftJoin('tipos_evaluaciones AS te',function($join){
                $join->on('te.id','=','mpe.tipo_evaluacion_id');
             })
             ->where('mp.estado',1)
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
                            $query->where('mp.aula','like',$aula.'%');
                        }
                    }
                    if( $r->has("dia") ){
                        $dia=trim($r->dia);
                        if( $dia !='' ){
                            $query->where('mp.dia','like','%'.$dia.'%');
                        }
                    }
                    if( $r->has("inicio") ){
                        $inicio=trim($r->inicio);
                        if( $inicio !='' ){
                            $query->where('mp.fecha_inicio','like','%'.$inicio.'%');
                        }
                    }
                    if( $r->has("tipo_curso") ){
                        $tipo_curso=trim($r->tipo_curso);
                        if( $tipo_curso !='' ){
                            $query->where('c.tipo_curso','=',$tipo_curso);
                        }
                    }
                }
            );
        $result = $sql->groupBy('mp.dia','mp.id','c.curso','s.sucursal','mp.aula',
                'mp.fecha_inicio','mp.peso_trabajo_final','mp.trabajo_final',
                'p.paterno','p.materno','p.nombre','mp.activa_evaluacion'
                )
                ->orderBy('mp.fecha_inicio','desc')->paginate(10);
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

        if( trim($r->diapo_nombre)!='' ){
            $type=explode(".",$r->diapo_nombre);
            $extension=".".$type[1];
        }
        $url = "img/programacion/diapo_".$programacion->id.$extension; 
        if( trim($r->diapo_archivo)!='' ){
            $programacion->diapo_archivo=$url;
            Menu::fileToFile($r->diapo_archivo, $url);
        }

        if( trim($r->diapoedit_nombre)!='' ){
            $type=explode(".",$r->diapoedit_nombre);
            $extension=".".$type[1];
        }
        $url = "img/programacion/diapoedit_".$programacion->id.$extension; 
        if( trim($r->diapoedit_archivo)!='' ){
            $programacion->diapoedit_archivo=$url;
            Menu::fileToFile($r->diapoedit_archivo, $url);
        }

        if( Input::has('grabo') AND trim($r->grabo)!='' ){
            $programacion->grabo=$r->grabo;
        }
        if( Input::has('publico') AND trim($r->publico)!='' ){
            $programacion->publico=$r->publico;
        }
        if( Input::has('expositor') AND trim($r->expositor)!='' ){
            $programacion->expositor=$r->expositor;
        }
        if( Input::has('situaciones') AND trim($r->situaciones)!='' ){
            $programacion->situaciones=$r->situaciones;
        }
        $programacion->persona_id_created_at=Auth::user()->id;
        $programacion->save();
    }

    public static function ProgramarEvaluacion($r)
    {
        DB::beginTransaction();
        $usuario= Auth::user()->id;

        $programacion= Programacion::find($r->id);
        $programacion->trabajo_final= $r->trabajo_final;
        $programacion->peso_trabajo_final= $r->peso_trabajo_final;
        $programacion->activa_evaluacion= $r->activa_evaluacion;
        $programacion->persona_id_updated_at=$usuario;
        $programacion->save();

        $fecha_inicio= $r->fecha_inicio;
        $fecha_final= $r->fecha_final;
        $peso_evaluacion= $r->peso_evaluacion;
        $tipo_evaluacion= $r->tipo_evaluacion;
        $activa_fecha= $r->activa_fecha;

        DB::table('mat_programaciones_evaluaciones')
        ->where('programacion_id','=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
                )
            );

        if( $r->has('tipo_evaluacion') ){
            for ($i=0; $i < count($tipo_evaluacion) ; $i++) { 
                $valida=DB::table('mat_programaciones_evaluaciones')
                ->select('id')
                ->where('programacion_id', '=', $r->id)
                ->where('tipo_evaluacion_id',$tipo_evaluacion[$i])
                ->first();

                if( isset($valida->id) AND $valida->id!='' ){
                    $PE = ProgramacionEvaluacion::find($valida->id);
                    $PE->persona_id_updated_at = $usuario;
                }
                else{
                    $PE = new ProgramacionEvaluacion;
                    $PE->programacion_id = $r->id;
                    $PE->tipo_evaluacion_id = $tipo_evaluacion[$i];
                    $PE->persona_id_created_at = $usuario;
                }
                $PE->peso_evaluacion=$peso_evaluacion[$i];
                $PE->fecha_evaluacion_ini=$fecha_inicio[$i];
                $PE->fecha_evaluacion_fin=$fecha_final[$i];
                $PE->activa_fecha=$activa_fecha[$i];
                $PE->orden=($i+1);
                $PE->estado=1;
                $PE->save();
            }
        }
        DB::commit();
    }

    public static function CargarEvaluaciones($r)
    {
        $sql=DB::table('mat_programaciones_evaluaciones as mpe')
             ->join('tipos_evaluaciones as te','te.id','=','mpe.tipo_evaluacion_id')
             ->select('te.id','te.tipo_evaluacion','mpe.peso_evaluacion',
                'mpe.fecha_evaluacion_ini','mpe.fecha_evaluacion_fin',
                'mpe.activa_fecha'
             )
             ->where('mpe.programacion_id',$r->id)
             ->where('mpe.estado',1)
             ->orderBy('mpe.orden','asc')
             ->get();
        return $sql;
    }
}
