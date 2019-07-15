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
        $especialidad = new Especialidad;
        $especialidad->especialidad = trim( $r->especialidad );
        $especialidad->certificado_especialidad = trim( $r->certificado_especialidad );
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_created_at=$especialidad_id;
        $especialidad->save();
        $curso = $r->curso_id;

        //ESTO HACE QUE GRABE EN LA TABLE DETALLE LOS CURSOS, LO QUE SE ESCOJE EN EL COMBO CURSO
        for($i=0;$i<count($curso);$i++)
        {
            $curso_especialidad = new CursoEspecialidad;
            $curso_especialidad->curso_id = $curso[$i];
            $curso_especialidad->orden = $i+1;
            $curso_especialidad->especialidad_id = $especialidad -> id;
            $curso_especialidad->persona_id_created_at = Auth::user()->id;
            $curso_especialidad->save();
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
        if( count($curso)>0 ){
            DB::table('mat_cursos_especialidades')
            ->where('especialidad_id', '=', $especialidad->id)
            ->update(
                array(
                    'estado' => 0,
                    'persona_id_updated_at' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                    )
                );
        }
        for($i=0;$i<count($curso);$i++)
        {
            $curso_especialidad=DB::table('mat_cursos_especialidades')
            ->where('especialidad_id', '=', $especialidad->id)
            ->where('curso_id', '=', $curso[$i])
            ->first();
            if( count($curso_especialidad)==0 ){
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
        DB::commit();
    }

    
    public static function ListEspecialidad($r)
    {
        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('especialidad','asc')->get();
        return $result;
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
        /*$sql= 'SELECT me.id, me.especialidad
                , GROUP_CONCAT( mc.curso, "|", IFNULL(mps.cant,0), "|", IFNULL(mps.nota,"") ORDER BY mce.orden SEPARATOR "^^" ) cursos
                FROM mat_especialidades me
                INNER JOIN mat_cursos_especialidades mce ON mce.especialidad_id=me.id AND mce.estado=1
                INNER JOIN mat_cursos mc ON mc.id=mce.curso_id
                LEFT JOIN (
                    SELECT mp.curso_id, COUNT(mmd.id) cant, MAX(mmd.nota_curso_alum) nota
                    FROM mat_programaciones mp 
                    INNER JOIN mat_matriculas_detalles mmd ON mmd.programacion_id=mp.id AND mmd.estado=1
                    INNER JOIN mat_matriculas mm ON mm.id=mmd.matricula_id AND mm.persona_id=\'.$r->persona_id.\' AND mm.estado=1
                    GROUP BY mp.curso_id
                ) AS mps ON mps.curso_id=mc.id
                WHERE me.estado=1
                GROUP BY me.id,me.especialidad';*/
        $sql =  DB::table('mat_especialidades AS me')
                ->Join('mat_cursos_especialidades AS mce',function($join){
                    $join->on('mce.especialidad_id','=','me.id')
                    ->where('mce.estado','=',1);
                })
                ->Join('mat_cursos AS mc',function($join){
                    $join->on('mc.id','=','mce.curso_id');
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
                ->select('me.id','me.especialidad'
                ,DB::raw('GROUP_CONCAT( mce.orden, "<input type=\'hidden\' class=\'curso_id\' value=\'",mce.curso_id,"\'>", "|", mc.curso, "|", IFNULL(mps.cant,0), "|", IFNULL(mps.nota,"") ORDER BY mce.orden SEPARATOR "^^" ) cursos')
                )
                ->where( function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('me.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                })
                ->groupBy('me.id','me.especialidad');
        $result = $sql->orderBy('me.especialidad','asc')->paginate(10);
        return $result;
    }
}
