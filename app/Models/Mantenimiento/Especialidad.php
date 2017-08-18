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

        $sql=Especialidad::select(
            'mat_especialidades.id',
            'mat_especialidades.especialidad',
            'mat_especialidades.certificado_especialidad',
            'mat_especialidades.estado'
            /*DB::raw(
                'c.curso',
                'mat_especialidades.curso_id',
                'mat_especialidades.estado'
                   )*/
            )
          //  ->join('mat_cursos as c','c.id','=','mat_especialidades.curso_id')
            ->where( 
                function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('mat_especialidades.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("certificado_especialidad") ){
                        $certificado_especialidad=trim($r->certificado_especialidad);
                        if( $certificado_especialidad !='' ){
                            $query->where('mat_especialidades.certificado_especialidad','like','%'.$certificado_especialidad.'%');
                        }
                    }

                    /*if( $r->has("curso") ){
                        $curso=trim($r->curso);
                        if( $curso !='' ){
                            $query->where('c.curso','like','%'.$curso.'%');
                        }
                    }*/

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mat_especialidades.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('mat_especialidades.id','asc')->paginate(10);
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
        $especialidad->curso_id = trim( $r->curso_id); 
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_updated_at=$especialidad_id;
        $especialidad->save();
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
        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('especialidad','asc')->paginate(10);
        return $result;
    }
}
