<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class Especialidad extends Model
{
    protected   $table = 'mat_especialidades';

    public static function runLoad($r)
    {

        $sql=DB::table('mat_especialidades AS me')
            ->leftJoin('mat_cursos_especialidades AS mce',function($join){
                $join->on('mce.especialidad_id','=','me.id')
                ->where('mce.estado','=',1);
            })
            ->leftJoin('mat_cursos AS mc',function($join){
                $join->on('mc.id','=','mce.curso_id')
                ->where('mc.empresa_id',Auth::user()->empresa_id)
                ->where('mc.estado','=',1);
            })
            ->select(
            'me.id',
            'me.especialidad',
            'me.certificado_especialidad',
            'me.estado',
            DB::raw('GROUP_CONCAT(mc.curso ORDER BY mce.orden SEPARATOR "|") cursos'),
            DB::raw('GROUP_CONCAT(mc.id) curso_id')
            )
            ->where('me.empresa_id',Auth::user()->empresa_id)
            ->where( 
                function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('me.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("certificado_especialidad") ){
                        $certificado_especialidad=trim($r->certificado_especialidad);
                        if( $certificado_especialidad !='' ){
                            $query->where('me.certificado_especialidad','like','%'.$certificado_especialidad.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('me.estado','like','%'.$estado.'%');
                        }
                    }
                }
            )
            ->groupBy('me.id','me.especialidad','me.certificado_especialidad','me.estado');
        $result = $sql->orderBy('me.especialidad','asc')->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $especialidad_id = Auth::user()->id;
        $especialidad = Especialidad::find($r->id);
        $especialidad->estado = trim( $r->estadof );
        $especialidad->persona_id_updated_at=$especialidad_id;
        $especialidad->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $especialidad_id = Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $especialidad = new Especialidad;
        $especialidad->empresa_id = $empresa_id;
        $especialidad->especialidad = trim( $r->especialidad );
        $especialidad->certificado_especialidad = trim( $r->certificado_especialidad );
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_created_at=$especialidad_id;
        $especialidad->save();
        $curso = $r->curso_id;

        //ESTO HACE QUE GRABE EN LA TABLE DETALLE LOS CURSOS, LO QUE SE ESCOJE EN EL COMBO CURSO
        if($r->has('curso_id')){
            for($i=0;$i<count($curso);$i++)
            {
                $curso_especialidad = new CursoEspecialidad;
                $curso_especialidad->curso_id = $curso[$i];
                $curso_especialidad->orden = $i+1;
                $curso_especialidad->especialidad_id = $especialidad -> id;
                $curso_especialidad->persona_id_created_at = Auth::user()->id;
                $curso_especialidad->save();
            }
        }
        DB::commit();
    }

    public static function runEdit($r)
    {
        DB::beginTransaction();
        $especialidad_id = Auth::user()->id;
        $especialidad = Especialidad::find($r->id);
        $especialidad->especialidad = trim( $r->especialidad );
        $especialidad->certificado_especialidad = trim( $r->certificado_especialidad );
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_updated_at=$especialidad_id;
        $especialidad->save();
        $curso = $r->curso_id;

        //ESTO HACE QUE GRABE EN LA TABLE DETALLE LOS CURSOS, LO QUE SE ESCOJE EN EL COMBO CURSO
        
        DB::table('mat_cursos_especialidades')
        ->where('especialidad_id', '=', $especialidad->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
                )
            );
        
        if($r->has('curso_id')){
            for($i=0;$i<count($curso);$i++)
            {
                $curso_especialidad=DB::table('mat_cursos_especialidades')
                ->where('especialidad_id', '=', $especialidad->id)
                ->where('curso_id', '=', $curso[$i])
                ->first();
                if( !isset($curso_especialidad->id) ){
                    $curso_especialidad = new CursoEspecialidad;
                    $curso_especialidad->curso_id = $curso[$i];
                    $curso_especialidad->orden = $i+1;
                    $curso_especialidad->especialidad_id = $especialidad->id;
                    $curso_especialidad->persona_id_created_at = Auth::user()->id;
                }
                else{
                    $curso_especialidad = CursoEspecialidad::find($curso_especialidad->id);
                    $curso_especialidad->estado = 1;
                    $curso_especialidad->orden = $i+1;
                    $curso_especialidad->persona_id_updated_at = Auth::user()->id;
                }
                $curso_especialidad->save();
            }
        }
        DB::commit();
    }

    
    public static function ListEspecialidad($r)
    {
        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where('estado','=','1')
            ->where('empresa_id',Auth::user()->empresa_id);
        $result = $sql->orderBy('especialidad','asc')->get();
        return $result;
    }

    public static function ListEspecialidadNuevo($r)
    {
        $empresa_id = Auth::user()->empresa_id;
        if( $r->has('sucursal_id') ){
            $sucursal= " AND eps.sucursal_id=".$r->sucursal_id;
        }
        $sql= " SELECT e.id, e.especialidad, ep.costo inscripcion, ep.costo_mat matricula, ep.fecha_inicio, ep.id id_ep
                FROM mat_especialidades e
                INNER JOIN mat_especialidades_programaciones ep ON ep.especialidad_id = e.id AND ep.tipo=2 AND ep.estado=1
                INNER JOIN mat_especialidades_programaciones_sucursales eps ON eps.especialidad_programacion_id=ep.id AND eps.estado=1
                LEFT JOIN (
                SELECT DISTINCT(md.especialidad_id) especialidad_id
                FROM mat_matriculas_detalles md
                INNER JOIN mat_matriculas m ON m.id=md.matricula_id AND m.estado=1 AND m.persona_id =$r->persona_id
                WHERE md.estado=1
                AND md.especialidad_id IS NOT NULL
                ) ea ON ea.especialidad_id=e.id
                WHERE e.estado=1
                AND e.empresa_id = $empresa_id
                AND ea.especialidad_id IS NULL
                $sucursal 
                ORDER BY e.especialidad" ;
        return DB::select($sql);
    }

    public static function ListEspecialidadAlumno($r)
    {
        $empresa_id = Auth::user()->empresa_id;
        if( $r->has('sucursal_id') ){
            $sucursal= " AND eps.sucursal_id=".$r->sucursal_id;
        }
        $sql= " SELECT e.id, e.especialidad, ep.costo inscripcion, ep.costo_mat matricula, ep.fecha_inicio, ep.id id_ep
                FROM mat_especialidades e
                INNER JOIN mat_especialidades_programaciones ep ON ep.especialidad_id = e.id AND ep.tipo=2 AND ep.estado=1
                INNER JOIN mat_especialidades_programaciones_sucursales eps ON eps.especialidad_programacion_id=ep.id AND eps.estado=1
                INNER JOIN (
                SELECT DISTINCT(md.especialidad_id) especialidad_id
                FROM mat_matriculas_detalles md
                INNER JOIN mat_matriculas m ON m.id=md.matricula_id AND m.estado=1 AND m.persona_id =$r->persona_id
                WHERE md.estado=1
                AND md.especialidad_id IS NOT NULL
                ) ea ON ea.especialidad_id=e.id
                WHERE e.estado=1
                AND e.empresa_id = $empresa_id
                $sucursal
                ORDER BY e.especialidad";
        return DB::select($sql);
    }

    public static function CargarEspecialidadCurso($r)
    {
        $result=DB::table('mat_cursos_especialidades')
            ->where('estado','=','1')
            ->where( function($query) use ($r){
                    if( $r->has("especialidad_id") ){
                        $especialidad_id=trim($r->especialidad_id);
                        if( $especialidad_id !='' ){
                            $query->where('especialidad_id','=',$especialidad_id);
                        }
                    }
            })
            ->orderBy('orden','asc')
            ->get();
        return $result;
    }
    
    public static function ListEspecialidadDisponible($r)
    {
        DB::statement(DB::raw('SET @@group_concat_max_len = 4294967295'));
        $sql =  DB::table('mat_especialidades AS me')
                ->Join('mat_especialidades_programaciones AS mep',function($join){
                    $join->on('mep.especialidad_id','=','me.id')
                    ->where('mep.tipo','=',1)
                    ->where('mep.estado','=',1);
                })
                ->Join('mat_especialidades_programaciones_sucursales AS meps',function($join){
                    $join->on('meps.especialidad_programacion_id','=','mep.id')
                    ->where('meps.estado','=',1);
                })
                ->Join('mat_cursos_especialidades AS mce',function($join){
                    $join->on('mce.especialidad_id','=','me.id')
                    ->where('mce.estado','=',1);
                })
                ->Join('mat_cursos AS mc',function($join){
                    $join->on('mc.id','=','mce.curso_id')
                    ->where('mc.empresa_id',Auth::user()->empresa_id);
                })
                ->leftJoin(DB::raw(
                    '(SELECT mepc.especialidad_programacion_id
                    , GROUP_CONCAT(mepc.fecha_cronograma," - ",mepc.monto_cronograma ORDER BY mepc.cuota SEPARATOR \'|\') cronograma
                    FROM mat_especialidades_programaciones_cronogramas AS mepc
                    WHERE mepc.estado=1
                    GROUP BY mepc.especialidad_programacion_id) AS cro'
                    ),function($join){
                    $join->on('cro.especialidad_programacion_id','=','mep.id');
                })
                ->leftJoin(DB::raw(
                    '(SELECT mp.curso_id, COUNT(mmd.id) cant, MAX(mmd.nota_curso_alum) nota
                    FROM mat_programaciones mp 
                    INNER JOIN mat_matriculas_detalles mmd ON mmd.programacion_id=mp.id AND mmd.estado=1
                    INNER JOIN mat_matriculas mm ON mm.id=mmd.matricula_id AND mm.persona_id='.$r->persona_id.' AND mm.estado=1
                    GROUP BY mp.curso_id) AS mps'
                    ),function($join){
                    $join->on('mps.curso_id','=','mc.id');
                })
                ->select(DB::raw('CONCAT(me.id,"_",mep.id) AS id'),'me.especialidad','mep.fecha_inicio','mep.tipo','mep.nro_cuota','cro.cronograma','mep.costo','mep.costo_mat'
                ,DB::raw('MIN(mep.adicional) AS adicional')
                /*,DB::raw('GROUP_CONCAT( mce.orden, "<input type=\'hidden\' class=\'curso_id\' value=\'",mce.curso_id,"\'>", "|", mc.curso, "|", IFNULL(mps.cant,0), "|", IFNULL(mps.nota,"") ORDER BY mce.orden SEPARATOR "^^" ) cursos')*/
                ,DB::raw('GROUP_CONCAT( mce.orden, "<input type=\'hidden\' class=\'curso_id\' value=\'",mce.curso_id,"\'>", "|", mc.curso ORDER BY mce.orden SEPARATOR "^^" ) cursos')
                )
                ->where('me.estado',1)
                ->where('me.empresa_id',Auth::user()->empresa_id)
                ->where( function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('me.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("fecha_inicio") ){
                        $fecha_inicio=trim($r->fecha_inicio);
                        if( $fecha_inicio !='' ){
                            $query->where('mep.fecha_inicio','like','%'.$fecha_inicio.'%');
                        }
                    }
                    if( $r->has("ode_estudio_id") ){
                        $ode_estudio_id=trim($r->ode_estudio_id);
                        if( $ode_estudio_id !='' ){
                            $query->where('meps.sucursal_id','=',$ode_estudio_id);
                        }
                    }
                })
                ->groupBy('me.id','mep.id','me.especialidad','mep.fecha_inicio','mep.tipo','mep.nro_cuota','cro.cronograma','mep.costo','mep.costo_mat');
        $result = $sql->orderBy('me.especialidad','asc')->paginate(10);
        return $result;
    }
}
