<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class Api extends Model
{
    protected   $table = 'apiaula';
    
    public static function ObtenerKey()
    {
        $key= DB::table('telesup_pae.apiaula')->where('estado',1)->select('key')->first();
        return $key;
    }

    public static function ObtenerPersona($r)
    {
        $key= DB::table('telesup_pae.personas')
                ->select('paterno','materno','nombre','sexo','email','telefono'
                ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento')
                )
                ->where('dni',$r->dni)
                ->first();
        return $key;
    }

    public static function ObtenerCursos($r)
    {
        $key= DB::table('telesup_pae.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( count($key)>0 ){
            $persona =  DB::table('telesup_pae.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $grupos=DB::table('telesup_pae.mat_matriculas AS mm')
                    ->Join('telesup_pae.mat_matriculas_detalles AS mmd', function($join){
                        $join->on('mmd.matricula_id','=','mm.id')
                        ->where('mmd.estado','=',1);
                    })
                    ->Join('telesup_pae.mat_programaciones AS mp', function($join){
                    $join->on('mp.id','=','mmd.programacion_id')
                    ->where('mp.estado','=',1);
                    })
                    ->Join('telesup_pae.personas AS p', function($join){
                        $join->on('p.id','=','mp.persona_id');
                    })
                    ->Join('telesup_pae.mat_cursos AS mc', function($join){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                    })
                    ->Join('telesup_pae.mat_cursos_especialidades AS mce', function($join){
                        $join->on('mce.curso_id','=','mc.id')
                        ->where('mce.estado',1);
                    })
                    ->Join('telesup_pae.mat_especialidades AS me', function($join){
                        $join->on('me.id','=','mce.especialidad_id')
                        ->where('me.estado',1);
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id', 'mp.id AS programacion_unica_externo_id'
                    ,'mp.fecha_inicio', 'mp.fecha_final', 'p.dni AS docente_dni', 'p.paterno AS docente_paterno'
                    ,'p.materno AS docente_materno','p.nombre AS docente_nombre', 'mmd.id AS programacion_externo_id'
                    ,'me.especialidad AS carrera'
                    )
                    ->where('mm.estado',1)
                    ->where('mm.persona_id',$persona[0]->id)
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'alumno' => $persona,
            'programacion' => $grupos
        );
        return $data;
    }

    public static function ObtenerTiposEvaluaciones($r)
    {
        $key= DB::table('telesup_pae.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( count($key)>0 ){
            $persona =  DB::table('telesup_pae.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $tipos=DB::table('telesup_pae.mat_matriculas AS mm')
                    ->Join('telesup_pae.mat_matriculas_detalles AS mmd', function($join){
                        $join->on('mmd.matricula_id','=','mm.id')
                        ->where('mmd.estado','=',1);
                    })
                    ->Join('telesup_pae.mat_programaciones AS mp', function($join){
                    $join->on('mp.id','=','mmd.programacion_id')
                    ->where('mp.estado','=',1);
                    })
                    ->Join('telesup_pae.mat_programaciones_evaluaciones AS mpe', function($join){
                        $join->on('mpe.programacion_id','=','mp.id')
                        ->where('mpe.estado',1);
                    })
                    ->Join('telesup_pae.tipos_evaluaciones AS te', function($join){
                        $join->on('te.id','=','mpe.tipo_evaluacion_id');
                    })
                    ->select('te.tipo_evaluacion', 'te.id AS tipo_evaluacion_externo_id'
                    ,'mpe.fecha_evaluacion_ini','mpe.fecha_evaluacion_fin'
                    )
                    ->where('mm.estado',1)
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('programacion_unica_externo_id') AND trim($r->programacion_unica_externo_id)!='' ){
                                $query->where('mp.id',$r->programacion_unica_externo_id);
                            }
                        }
                    )
                    ->where('mm.persona_id',$persona[0]->id)
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'tipo' => $tipos
        );
        return $data;
    }

    public static function ObtenerTiposEvaluacionesTotales($r)
    {
        $key= DB::table('telesup_pae.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( count($key)>0 ){
            $tipos=DB::table('telesup_pae.mat_programaciones AS mp')
                    ->Join('telesup_pae.mat_cursos AS mc', function($join){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                    })
                    ->Join('telesup_pae.mat_programaciones_evaluaciones AS mpe', function($join){
                        $join->on('mpe.programacion_id','=','mp.id')
                        ->where('mpe.estado',1);
                    })
                    ->Join('telesup_pae.tipos_evaluaciones AS te', function($join){
                        $join->on('te.id','=','mpe.tipo_evaluacion_id');
                    })
                    ->select('te.tipo_evaluacion', 'te.id AS tipo_evaluacion_externo_id'
                    ,'mpe.fecha_evaluacion_ini','mpe.fecha_evaluacion_fin'
                    ,'mp.id AS programacion_unica_externo_id'
                    )
                    ->where('mp.estado',1)
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('programacion_unica_externo_id') AND trim($r->programacion_unica_externo_id)!='' ){
                                $query->where('mp.id',$r->programacion_unica_externo_id);
                            }
                        }
                    )
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'tipo' => $tipos
        );
        return $data;
    }

    public static function ObtenerCursosDocente($r)
    {
        $key= DB::table('telesup_pae.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( count($key)>0 ){
            $persona =  DB::table('telesup_pae.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $grupos=DB::table('telesup_pae.mat_programaciones AS mp')
                    ->Join('telesup_pae.personas AS p', function($join){
                        $join->on('p.id','=','mp.persona_id');
                    })
                    ->Join('telesup_pae.mat_cursos AS mc', function($join){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                    })
                    ->Join('telesup_pae.mat_cursos_especialidades AS mce', function($join){
                        $join->on('mce.curso_id','=','mc.id')
                        ->where('mce.estado',1);
                    })
                    ->Join('telesup_pae.mat_especialidades AS me', function($join){
                        $join->on('me.id','=','mce.especialidad_id')
                        ->where('me.estado',1);
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id', 'mp.id AS programacion_unica_externo_id'
                    ,'mp.fecha_inicio', 'mp.fecha_final', 'p.dni AS docente_dni', 'p.paterno AS docente_paterno'
                    ,'p.materno AS docente_materno','p.nombre AS docente_nombre', 'me.especialidad AS carrera'
                    )
                    ->where('mp.estado',1)
                    ->where('mp.persona_id',$persona[0]->id)
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'docente' => $persona,
            'programacion' => $grupos
        );
        return $data;
    }
}
