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
            DB::raw('GROUP_CONCAT(mc.curso ORDER BY curso) cursos')
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
            $curso_especialidad->especialidad_id = $especialidad -> id;
            $curso_especialidad->persona_id_created_at = Auth::user()->id;
            $curso_especialidad->save();
        }
    }

    public static function runEdit($r)
    {
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
                $curso_especialidad->especialidad_id = $especialidad->id;
                $curso_especialidad->persona_id_created_at = Auth::user()->id;
            }
            else{
                $curso_especialidad = CursoEspecialidad::find($curso_especialidad->id);
                $curso_especialidad->estado = 1;
                $curso_especialidad->persona_id_updated_at = Auth::user()->id;
            }
            $curso_especialidad->save();
        }
    }    

    
        public static function ListEspecialidad($r)
    {
        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('especialidad','asc')->get();
        return $result;
    }
    
            public static function ListEspecialidadDisponible($r)
    {
        $sql=DB::table(DB::raw(
                '(SELECT me.id, me.especialidad,me.certificado_especialidad, COUNT(mce.curso_id) ncursos, GROUP_CONCAT( mce.curso_id ) cursos
                FROM mat_especialidades me
                INNER JOIN mat_cursos_especialidades mce ON mce.especialidad_id=me.id AND mce.estado=1
                AND me.estado=1
                GROUP BY me.id, me.especialidad,me.certificado_especialidad) as a'
                ))
                ->select('a.id','a.especialidad','a.certificado_especialidad','a.ncursos','a.cursos',
                DB::raw('ValidaCursos( CONCAT(a.cursos,","),'.$r->persona_id.' ) as validar'),
                DB::raw('IFNULL(
                    (SELECT COUNT(mt.id) 
                     FROM mat_matriculas mt
                     INNER JOIN mat_matriculas_detalles mmd ON mmd.matricula_id=mt.id AND mmd.estado=1
                     WHERE mt.persona_id='.$r->persona_id.' 
                     AND mt.estado=1
                     AND mmd.especialidad_id=a.id 
                     GROUP BY mt.persona_id
                    ),0
                ) as nveces')
                )
                ->where( 
                function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('a.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("certificado_especialidad") ){
                        $certificado_especialidad=trim($r->certificado_especialidad);
                        if( $certificado_especialidad !='' ){
                            $query->where('a.certificado_especialidad','like','%'.$certificado_especialidad.'%');
                        }
                    }
                }
                );
        $result = $sql->orderBy('especialidad','asc')->paginate(10);
        return $result;
    }
}
