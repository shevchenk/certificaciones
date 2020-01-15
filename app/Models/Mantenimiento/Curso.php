<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class Curso extends Model
{
    protected   $table = 'mat_cursos';

    public static function runLoad($r)
    {

        $sql=Curso::select('id','curso','certificado_curso','curso_apocope'
            ,'tipo_curso as tc','hora','credito',
        	DB::raw('
        		CASE tipo_curso
        		WHEN "1" THEN "Curso"
        		WHEN "2" THEN "Seminario"
        		END tipo_curso
        		'),
        	'estado')
            ->where('empresa_id',Auth::user()->empresa_id)
            ->where( 
                function($query) use ($r){
                    if( $r->has("curso") ){
                        $curso=trim($r->curso);
                        if( $curso !='' ){
                            $query->where('curso','like','%'.$curso.'%');
                        }
                    }
                    if( $r->has("certificado_curso") ){
                        $certificado_curso=trim($r->certificado_curso);
                        if( $certificado_curso !='' ){
                            $query->where('certificado_curso','like','%'.$certificado_curso.'%');
                        }
                    }
                    if( $r->has("curso_apocope") ){
                        $curso_apocope=trim($r->curso_apocope);
                        if( $curso_apocope !='' ){
                            $query->where('curso_apocope','like','%'.$curso_apocope.'%');
                        }
                    }
                    if( $r->has("tipo_curso") ){
                        $tipo_curso=trim($r->tipo_curso);
                        if( $tipo_curso !='' ){
                            $query->where('tipo_curso','like',$tipo_curso.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('curso','asc')->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $cursoe = Auth::user()->id;
        $curso = Curso::find($r->id);
        $curso->estado = trim( $r->estadof );
        $curso->persona_id_updated_at=$cursoe;
        $curso->save();
    }

    public static function runNew($r)
    {
        $cursoe = Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $curso = new Curso;
        $curso->empresa_id = $empresa_id;
        $curso->curso = trim( $r->curso );
        $curso->certificado_curso = trim( $r->certificado_curso );
        $curso->tipo_curso = trim( $r->tipo_curso );
        $curso->curso_apocope = trim( $r->curso_apocope );
        if( $r->has('hora') ){
            $curso->hora = trim( $r->hora );
        }
        if( $r->has('credito') ){
            $curso->credito = trim( $r->credito );
        }
        $curso->estado = trim( $r->estado );
        $curso->persona_id_created_at=$cursoe;
        $curso->save();
    }

    public static function runEdit($r)
    {
        $cursoe = Auth::user()->id;
        $curso = Curso::find($r->id);
        $curso->curso = trim( $r->curso );
        $curso->certificado_curso = trim( $r->certificado_curso );
        $curso->tipo_curso = trim( $r->tipo_curso );
        $curso->curso_apocope = trim( $r->curso_apocope );
        if( $r->has('hora') ){
            $curso->hora = trim( $r->hora );
        }
        if( $r->has('credito') ){
            $curso->credito = trim( $r->credito );
        }
        $curso->estado = trim( $r->estado );
        $curso->persona_id_updated_at=$cursoe;
        $curso->save();
    }    

    
        public static function ListCurso($r)
    {
        $sql=Curso::select('id','curso','certificado_curso'
            ,'tipo_curso','estado','hora','credito')
            ->where('estado','=','1')
            ->where('empresa_id',Auth::user()->empresa_id)
            ->where( 
                function($query) use ($r){
                    if( $r->has("tipo_curso") ){
                        $tipo_curso=trim($r->tipo_curso);
                        if( $tipo_curso !='' ){
                            $query->where('tipo_curso','=',$tipo_curso);
                        }
                    }
                }
            );
        $result = $sql->orderBy('curso','asc')->get();
        return $result;
    }
    

}
