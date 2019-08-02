<?php

namespace App\Models\SecureAccess;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class Persona extends Authenticatable
{
    use Notifiable;
    protected   $table = 'personas';


    public static function runEditPassword($r)
    {
        $persona_id = Auth::user()->id;
        $persona = Persona::find($persona_id);
        $bcryptpassword = bcrypt($r->password);
        if( Hash::check($r->password_actual, $persona->password) ){
            $persona->password = $bcryptpassword;
            $persona->persona_id_updated_at = $persona_id;
            $persona->save();
            return 1;
        }
        else{
            return 2;
        }
    }

    public static function Menu()
    {
        $persona=Auth::user()->id;
        $privilegio_id=Auth::user()->privilegio_id;
        $set=DB::statement('SET group_concat_max_len := @@max_allowed_packet');
        $result=DB::table('opciones as o')
                ->join('menus as m', function($join){
                        $join->on('m.id','o.menu_id')
                        ->where('m.estado',1);
                })
                ->join('privilegios_opciones as po', function($join){
                        $join->on('po.opcion_id','o.id')
                        ->where('po.estado',1);
                })
                ->join('privilegios as p', function($join){
                        $join->on('p.id','po.privilegio_id')
                        ->where('p.estado',1);
                })
                ->join('personas_privilegios_sucursales as pps', function($join){
                        $join->on('pps.privilegio_id','p.id')
                        ->where('pps.estado',1);
                })
                ->select('m.menu','p.cargo',
                    DB::raw(
                    'GROUP_CONCAT( 
                        DISTINCT( CONCAT_WS("|",o.opcion,o.ruta,o.class_icono) )
                        ORDER BY o.opcion SEPARATOR "||"
                        ) opciones, min(m.class_icono) icono'
                    )
                )
                ->where('pps.persona_id',$persona)
                ->where('pps.privilegio_id',$privilegio_id)
                ->where('o.estado',1)
                ->groupBy('m.menu','p.cargo')
                ->orderBy('m.menu')
                ->get();

        return $result;
    }

    public static function ValidaActivarAula( $tabla )
    {
        $persona=Auth::user()->id;
        $date=date('Y-m-d H:i:s');
        if( $tabla=='m' ){
            $valida=DB::table('mat_matriculas AS m')
                    ->join('mat_matriculas_detalles AS md', function($join){
                            $join->on('md.matricula_id','m.id')
                            ->where('md.estado',1);
                    })
                    ->join('mat_programaciones AS p', function($join){
                            $join->on('p.id','md.programacion_id')
                            ->where('p.estado',1);
                    })
                    ->join('mat_cursos as c', function($join){
                            $join->on('c.id','p.curso_id')
                            ->where('c.tipo_curso',1)
                            ->where('c.estado',1);
                    })
                    ->where('m.persona_id',$persona)
                    //->where('p.fecha_inicio','<=',$date)
                    ->where('p.fecha_final','>=',$date)
                    ->exists();
        }
        else{
            $valida=DB::table('mat_programaciones AS p')
                    ->join('mat_cursos as c', function($join){
                            $join->on('c.id','p.curso_id')
                            ->where('c.tipo_curso',1)
                            ->where('c.estado',1);
                    })
                    ->where('p.estado',1)
                    ->where('p.persona_id',$persona)
                    //->where('p.fecha_inicio','<=',$date)
                    ->where('p.fecha_final','>=',$date)
                    ->exists();
        }
        return $valida;
    }

    public static function ValidarAula()
    {
        $aula= DB::table('apiaula')
                ->where('estado',1)
                ->first();
        return $aula;
    }

    public static function ActivarEmpresa()
    {
        $persona_id= Auth::user()->id;
        $result=DB::table('personas as p')
                ->join('personas_empresas as pe', function($join){
                        $join->on('pe.persona_id','p.id')
                        ->where('pe.estado',1);
                })
                ->join('empresas AS e', function($join){
                        $join->on('e.id','pe.empresa_id')
                        ->where('e.estado',1);
                })
                ->select('e.id AS empresa_id','e.empresa')
                ->where('pe.persona_id',$persona_id)
                ->orderBy('e.empresa','asc')
                ->get();

        if( isset($result[0]->empresa_id) ){
            DB::table('personas')
            ->where('id', $persona_id)
            ->update(array('empresa_id' => $result[0]->empresa_id));
            Auth::loginUsingId($persona_id);
        }

        return $result;
    }

    public static function Privilegios()
    {
        $persona=Auth::user()->id;
        $result=DB::table('personas as p')
                ->join('personas_privilegios_sucursales as pps', function($join){
                        $join->on('pps.persona_id','p.id')
                        ->where('pps.estado',1);
                })
                ->join('privilegios as pr', function($join){
                        $join->on('pr.id','pps.privilegio_id')
                        ->where('pr.estado',1);
                })
                ->select('pr.id AS privilegio_id','pr.privilegio')
                ->where('pps.persona_id',$persona)
                ->groupBy('pr.id','pr.privilegio')
                ->orderBy('pr.privilegio','asc')
                ->get();

        if( isset($result[0]->privilegio_id) ){
            DB::table('personas')
            ->where('id', $persona)
            ->update(array('privilegio_id' => $result[0]->privilegio_id));
            Auth::loginUsingId($persona);
        }

        return $result;
    }
}
