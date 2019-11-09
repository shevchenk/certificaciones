<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Models\Mantenimiento\Curso;
use App\Models\Mantenimiento\Persona;
use App\Mail\EmailSend;
use DB;
use Mail;

class Api extends Model
{
    protected   $table = 'apiaula';

    public static function ValidarEmail()
    {
        $valida=    DB::table(Api::mibdaux().'.validar_email')
                    ->where('ultimo',1)
                    ->first();
        session(['validar_email' => $valida->estado]);
    }

    public static function mibdaux()
    {
        $bd='prabtoea_telesup_pae';
        if( $_SERVER['SERVER_NAME']=='localhost' ){
            $bd= 'telesup_pae';
        }
        elseif( $_SERVER['SERVER_NAME']=='capafc.jssoluciones.pe' ){
            $bd='prabtoea_pae';
        }
        elseif( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $bd='formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $bd='capa_formacion_continua';
        }
        return $bd;
    }
    
    public static function ObtenerKey()
    {
        $key= DB::table(Api::mibdaux().'.apiaula')->where('estado',1)->select('key')->first();
        return $key;
    }

    public static function ObtenerPersona($r)
    {
        $key= DB::table(Api::mibdaux().'.personas')
                ->select('paterno','materno','nombre','sexo','email','telefono'
                ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento')
                )
                ->where('dni',$r->dni)
                ->first();
        return $key;
    }

    public static function ObtenerCursos($r)
    {
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $persona =  DB::table(Api::mibdaux().'.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $grupos=DB::table(Api::mibdaux().'.mat_matriculas AS mm')
                    ->Join(Api::mibdaux().'.mat_matriculas_detalles AS mmd', function($join){
                        $join->on('mmd.matricula_id','=','mm.id')
                        ->where('mmd.estado','=',1);
                    })
                    ->Join(Api::mibdaux().'.mat_programaciones AS mp', function($join){
                    $join->on('mp.id','=','mmd.programacion_id')
                    ->where('mp.estado','=',1);
                    })
                    ->Join(Api::mibdaux().'.personas AS p', function($join){
                        $join->on('p.id','=','mp.persona_id');
                    })
                    ->Join(Api::mibdaux().'.mat_cursos AS mc', function($join){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                    })
                    ->leftJoin(Api::mibdaux().'.mat_especialidades AS me', function($join){
                        $join->on('me.id','=','mmd.especialidad_id')
                        ->where('me.estado',1);
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id', 'mp.id AS programacion_unica_externo_id'
                    ,'mp.fecha_inicio', 'mp.fecha_final', 'p.dni AS docente_dni', 'p.paterno AS docente_paterno'
                    ,'p.materno AS docente_materno','p.nombre AS docente_nombre', 'mmd.id AS programacion_externo_id'
                    ,DB::raw('IFNULL(me.especialidad,"Curso Libre") AS carrera','mc.empresa_id AS empresa_externo_id')
                    ,'mc.empresa_id AS empresa_externo_id'
                    )
                    ->where('mm.estado',1)
                    ->where('mm.persona_id',$persona[0]->id)
                    //->where(DB::raw('DATE(mp.fecha_inicio)'),'<=', date('Y-m-d'))
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
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $persona =  DB::table(Api::mibdaux().'.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $tipos=DB::table(Api::mibdaux().'.mat_matriculas AS mm')
                    ->Join(Api::mibdaux().'.mat_matriculas_detalles AS mmd', function($join){
                        $join->on('mmd.matricula_id','=','mm.id')
                        ->where('mmd.estado','=',1);
                    })
                    ->Join(Api::mibdaux().'.mat_programaciones AS mp', function($join){
                    $join->on('mp.id','=','mmd.programacion_id')
                    ->where('mp.estado','=',1);
                    })
                    ->Join(Api::mibdaux().'.mat_programaciones_evaluaciones AS mpe', function($join){
                        $join->on('mpe.programacion_id','=','mp.id')
                        ->where('mpe.estado',1);
                    })
                    ->Join(Api::mibdaux().'.tipos_evaluaciones AS te', function($join){
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
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $tipos=DB::table(Api::mibdaux().'.mat_programaciones AS mp')
                    ->Join(Api::mibdaux().'.mat_programaciones_evaluaciones AS mpe', function($join){
                        $join->on('mpe.programacion_id','=','mp.id')
                        ->where('mpe.estado',1);
                    })
                    ->Join(Api::mibdaux().'.tipos_evaluaciones AS te', function($join){
                        $join->on('te.id','=','mpe.tipo_evaluacion_id');
                    })
                    ->select('te.tipo_evaluacion', 'te.id AS tipo_evaluacion_externo_id'
                    ,'mpe.fecha_evaluacion_ini','mpe.fecha_evaluacion_fin'
                    ,'mpe.orden','mpe.activa_fecha'
                    ,'mp.id AS programacion_unica_externo_id'
                    )
                    ->where('mp.estado',1)
                    ->where('mp.activa_evaluacion',1)
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('programacion_unica_externo_id') AND trim($r->programacion_unica_externo_id)!='' ){
                                $query->where('mp.id',$r->programacion_unica_externo_id);
                            }
                        }
                    )
                    ->orderBy('mpe.orden','asc')
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

    public static function ObtenerTiposEvaluacionesEmpresas($r)
    {
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $tipos=DB::table(Api::mibdaux().'.empresas AS me')
                    ->Join(Api::mibdaux().'.empresas_tipos_evaluaciones AS mete', function($join){
                        $join->on('mete.empresa_id','=','me.id')
                        ->where('mete.estado',1);
                    })
                    ->Join(Api::mibdaux().'.tipos_evaluaciones AS te', function($join){
                        $join->on('te.id','=','mete.tipo_evaluacion_id');
                    })
                    ->select('te.tipo_evaluacion', 'te.id AS tipo_evaluacion_externo_id'
                    ,'mete.orden'
                    )
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('empresa_externo_id') AND trim($r->empresa_externo_id)!='' ){
                                $query->where('me.id',$r->empresa_externo_id);
                            }
                        }
                    )
                    ->orderBy('mete.orden','asc')
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
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $persona =  DB::table(Api::mibdaux().'.personas')
                        ->select('paterno','materno','nombre','sexo','email','telefono','dni'
                        ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento'),'id'
                        )
                        ->where('dni',$r->dni)
                        ->get();

            $grupos=DB::table(Api::mibdaux().'.mat_programaciones AS mp')
                    ->Join(Api::mibdaux().'.personas AS p', function($join){
                        $join->on('p.id','=','mp.persona_id');
                    })
                    ->Join(Api::mibdaux().'.mat_cursos AS mc', function($join){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                    })
                    ->leftJoin(Api::mibdaux().'.mat_cursos_especialidades AS mce', function($join){
                        $join->on('mce.curso_id','=','mc.id')
                        ->where('mce.estado',1);
                    })
                    ->leftJoin(Api::mibdaux().'.mat_especialidades AS me', function($join){
                        $join->on('me.id','=','mce.especialidad_id')
                        ->where('me.estado',1);
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id', 'mp.id AS programacion_unica_externo_id'
                    ,'mp.fecha_inicio', 'mp.fecha_final', 'p.dni AS docente_dni', 'p.paterno AS docente_paterno'
                    ,'p.materno AS docente_materno','p.nombre AS docente_nombre', DB::raw('IFNULL(me.especialidad,"Curso Libre") AS carrera')
                    ,'mc.empresa_id AS empresa_externo_id'
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

    public static function ObtenerCursosGlobal($r)
    {
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $grupos=DB::table(Api::mibdaux().'.mat_cursos AS mc')
                    ->join(Api::mibdaux().'.empresas AS e', function($join) use($r){
                        $join->on('e.id','=','mc.empresa_id');
                        if( $r->has('empresa_id') ){
                            $join->where('e.id',$r->empresa_id);
                        }
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id','e.id AS empresa_externo_id','e.empresa')
                    ->where('mc.tipo_curso',1)
                    ->where('mc.estado',1)
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'cursos' => $grupos
        );
        return $data;
    }

    public static function ObtenerProgramacionesGlobal($r)
    {
        $key= DB::table(Api::mibdaux().'.apiaula')
                ->where('estado',1)
                ->select('idaula AS id','key AS token')
                ->where('keycli',$r->keycli)
                ->first();

        $grupos=array();
        if( isset($key->id) ){
            $grupos=DB::table(Api::mibdaux().'.mat_programaciones AS mp')
                    ->Join(Api::mibdaux().'.personas AS p', function($join){
                        $join->on('p.id','=','mp.persona_id');
                    })
                    ->Join(Api::mibdaux().'.mat_cursos AS mc', function($join) use($r){
                        $join->on('mc.id','=','mp.curso_id')
                        ->where('mc.tipo_curso',1);
                        if( $r->has('empresa_id') ){
                            $join->where('mc.empresa_id',$r->empresa_id);
                        }
                    })
                    ->leftJoin(Api::mibdaux().'.mat_cursos_especialidades AS mce', function($join){
                        $join->on('mce.curso_id','=','mc.id')
                        ->where('mce.estado',1);
                    })
                    ->leftJoin(Api::mibdaux().'.mat_especialidades AS me', function($join){
                        $join->on('me.id','=','mce.especialidad_id')
                        ->where('me.estado',1);
                    })
                    ->select('mc.curso', 'mc.id AS curso_externo_id', 'mp.id AS programacion_unica_externo_id'
                    ,'mp.fecha_inicio', 'mp.fecha_final', 'p.dni AS docente_dni', 'p.paterno AS docente_paterno'
                    ,'p.materno AS docente_materno','p.nombre AS docente_nombre', DB::raw('IFNULL(me.especialidad,"Curso Libre") AS carrera')
                    ,'mc.empresa_id AS empresa_externo_id'
                    )
                    ->where('mp.estado',1)
                    ->get();
        }
        else{
            $key=array( 'token'=>0, 'id'=>0 );
        }

        $data = array(
            'key' => $key,
            'programacion' => $grupos
        );
        return $data;
    }

    public static function ObtenerCursosProgramados($r)
    {
        $grupos=array();
        $grupos=DB::table(Api::mibdaux().'.mat_programaciones AS mp')
                ->Join(Api::mibdaux().'.personas AS p', function($join){
                    $join->on('p.id','=','mp.persona_id');
                })
                ->Join(Api::mibdaux().'.mat_cursos AS mc', function($join){
                    $join->on('mc.id','=','mp.curso_id');
                })
                ->leftJoin(Api::mibdaux().'.mat_cursos_especialidades AS mce', function($join){
                    $join->on('mce.curso_id','=','mc.id')
                    ->where('mce.estado',1);
                })
                ->leftJoin(Api::mibdaux().'.mat_especialidades AS me', function($join){
                    $join->on('me.id','=','mce.especialidad_id')
                    ->where('me.estado',1);
                })
                ->select('mp.curso_id', 'mc.curso', 'mp.id AS programacion_id', DB::raw('DATE(mp.fecha_inicio) AS fecha_inicio')
                , DB::raw('TIME(mp.fecha_inicio) hora_inicio, TIME(mp.fecha_final) hora_final')
                , 'p.paterno AS docente_paterno', 'p.materno AS docente_materno', 'p.nombre AS docente_nombre'
                , 'mc.empresa_id AS empresa_externo_id'
                )
                ->where('mp.estado',1)
                ->whereRaw(' DATE(mp.fecha_inicio) + INTERVAL 7 DAY  >= CURDATE()')
                ->where(function($query) use($r){
                    if( $r->has('tipo_programacion') AND $r->tipo_programacion==1 ){
                        $query->where('mc.tipo_curso',1);
                    }
                    elseif( $r->has('tipo_programacion') AND $r->tipo_programacion==2 ){
                        $query->where('mc.tipo_curso',2);
                    }
                    elseif( $r->has('tipo_programacion') ){
                        $query->where('mc.tipo_curso',1);
                        if( $r->has('especialidad_id') AND $r->especialidad_id!='' ){
                            $query->where('mce.especialidad_id', $r->especialidad_id);
                        }
                        else{
                            $query->where('mce.especialidad_id', 0);
                        }
                    }

                    if( $r->has('empresa_id') ){
                        $query->where('mc.empresa_id',$r->empresa_id);
                    }
                    else{
                        $query->where('mc.empresa_id',0);
                    }
                });

                if( $r->has('tipo_programacion') AND $r->tipo_programacion==1 ){
                    $grupos->addSelect('mp.dia');
                }
        $data = array(
            'programacion' => $grupos->get()
        );
        return $data;
    }

    public static function ObtenerEspecialidadesProgramados($r)
    {
        $set=DB::statement('SET group_concat_max_len := @@max_allowed_packet');
        $data=array();
        $data=DB::table(Api::mibdaux().'.mat_especialidades AS me')
                ->Join(Api::mibdaux().'.mat_cursos_especialidades AS mce', function($join){
                    $join->on('mce.especialidad_id','=','me.id')
                    ->where('mce.estado',1);
                })
                ->Join(Api::mibdaux().'.mat_cursos AS mc', function($join){
                    $join->on('mc.id','=','mce.curso_id');
                })
                ->Join(Api::mibdaux().'.mat_especialidades_programaciones AS mep', function($join){
                    $join->on('mep.especialidad_id','=','me.id')
                    ->where('mep.estado',1);
                })
                ->Join(Api::mibdaux().'.mat_especialidades_programaciones_cronogramas AS mepc', function($join){
                    $join->on('mepc.especialidad_programacion_id','=','mep.id')
                    ->where('mepc.estado',1);
                })
                ->select('me.id AS especialidad_id', 'mep.id AS especialidad_programacion_id', 'me.especialidad', 'mep.fecha_inicio'
                , DB::raw('GROUP_CONCAT( DISTINCT(mc.curso) SEPARATOR "|" ) cursos')
                , 'mc.empresa_id AS empresa_externo_id'
                )
                ->where('me.estado',1)
                ->where(function($query) use($r){
                    if( $r->has('empresa_id') ){
                        $query->where('me.empresa_id',$r->empresa_id);
                    }
                    else{
                        $query->where('me.empresa_id',0);
                    }
                })
                ->whereRaw(' mep.fecha_inicio + INTERVAL 7 DAY  >= CURDATE()')
                ->groupBy('me.id', 'me.especialidad', 'mep.id', 'mep.fecha_inicio')
                ->havingRaw('COUNT(DISTINCT(mepc.id))>1')
                ->get();
        $data = array(
            'especialidades' => $data
        );
        return $data;
    }

    public static function RegistrarInscripciones( $r )
    {
        $return=array();
        if( !$r->has('empresa_id') ){
            return 'empresa_id => Falta Id de la Empresa';
        }
        elseif( !$r->has('dni') ){
            return 'dni => Falta DNI o Identificación de la persona';
        }
        elseif( !$r->has('paterno') ){
            return 'paterno => Falta Paterno';
        }
        elseif( !$r->has('materno') ){
            return 'materno => Falta Materno';
        }
        elseif( !$r->has('nombre') ){
            return 'nombre => Falta Nombre';
        }
        elseif( !$r->has('nro_pago') ){
            return 'nro_pago => Falta Nro Pago de Inscripción';
        }
        elseif( !$r->has('monto_pago') ){
            return 'monto_pago => Falta Monto Pago de Inscripción';
        }
        elseif( !$r->has('observacion') ){
            return 'observacion => Falta comentario de la venta( Aqui es para indicar de donde proviene la venta)';
        }
        elseif( !$r->has('programacion_id') ){
            return 'programacion_id[] => Falta Programaciones Seleccionadas';
        }
        elseif( !$r->has('curso_id') ){
            return 'curso_id[] => Falta Cursos Seleccionados';
        }
        elseif( !$r->has('nro_pago_programacion') AND !$r->has('especialidad_programacion_id') ){
            return 'nro_pago_programacion[] => Falta Nro Pago del Curso Programado';
        }
        elseif( !$r->has('monto_pago_programacion') AND !$r->has('especialidad_programacion_id') ){
            return 'monto_pago_programacion[] => Falta Monto Pago del Curso Programado';
        }
        elseif( !$r->has('especialidad_programacion_id') AND $r->has('especialidad_id') ){
            return 'especialidad_programacion_id => Falta código de programación de la especialidad';
        }
        elseif( !$r->has('especialidad_id') AND $r->has('especialidad_programacion_id') ){
            return 'especialidad_id => Falta código de especialidad';
        }
        elseif( !$r->has('nro_cuota') AND $r->has('especialidad_id') ){
            return 'nro_cuota => Falta Nro Pago de la Primera Cuota';
        }
        elseif( !$r->has('monto_cuota') AND $r->has('especialidad_id') ){
            return 'monto_cuota => Falta Monto Pago de la Primera Cuota';
        }
        $persona= Persona::where('dni',$r->dni)->first();
        $usuario= 0;
        $extension='';

        DB::beginTransaction();
        if( !isset($persona->id) ){
            $persona= new Persona;
            $persona->paterno= $r->paterno;
            $persona->materno= $r->materno;
            $persona->nombre= $r->nombre;
            $persona->dni= $r->dni;
            if( $r->has('email') ){
                $persona->email= $r->email;
                $persona->email_externo= $r->email;
            }
            if( $r->has('celular') ){
                $persona->celular= $r->celular;
            }
            $persona->empresa_interesado_id= $r->empresa_id;
            $persona->password=bcrypt($r->dni);
            $persona->persona_id_created_at= $usuario;
            $persona->save();
        }
        else{
            $persona= Persona::find($persona->id);
            $persona->email_externo= $r->email;
            $persona->persona_id_updated_at= $usuario;
            $persona->save();
        }

        /*DB::table('personas_captadas')
        ->where('persona_id', '=', $persona->id)
        ->where('empresa_id', '=', $r->empresa_id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $usuario,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        DB::table('personas_captadas')
        ->insert(
            array(
                'persona_id' => $persona->id,
                'empresa_id' => $r->empresa_id,
                'fuente' => 'WEB',
                'interesado' => 'Inscripción',
                'comentario' => 'Inscripción',
                'estado' => 1,
                'created_at'=> date('Y-m-d h:m:s'),
                'persona_id_created_at'=> $usuario,
                'persona_id_updated_at' => $usuario
            )
        );*/

        $privilegio =DB::table('personas_privilegios_sucursales')
        ->where('privilegio_id',14)
        ->where('estado',1)
        ->where('persona_id',$persona->id)
        ->first();

        if( !isset($privilegio->id) ){
            DB::table('personas_privilegios_sucursales')->insert(
                array(
                    'privilegio_id' => 14,
                    'sucursal_id' => 1,
                    'persona_id' => $persona->id,
                    'created_at'=> date('Y-m-d h:m:s'),
                    'persona_id_created_at'=> $usuario,
                    'estado' => 1,
                    'persona_id_updated_at' => $usuario
                )
            );
        }

        $al=Alumno::where('persona_id',$persona->id)->first();

        if( !isset($al->id) ){
           $al= new Alumno;
           $al->persona_id=$persona->id;
           $al->persona_id_created_at= $usuario;
           $al->save();
        }

        $matricula = new Matricula;
        $matricula->alumno_id = $al->id;
        $matricula->tipo_participante_id = 1;
        $matricula->persona_id = $persona->id;
        $matricula->sucursal_id = 1;
        $matricula->sucursal_destino_id = 1;

        $matricula->persona_caja_id = 12293; //12293 id persona banco
        $matricula->persona_matricula_id = 12293;
        $matricula->persona_marketing_id = 12293;

        $matricula->fecha_matricula = date('Y-m-d');
        $matricula->tipo_matricula = 2;
        
        if( trim($r->nro_pago)!=''){
            $matricula->nro_promocion = trim( $r->nro_pago);
            $matricula->monto_promocion = trim( $r->monto_pago);
            $matricula->tipo_pago = 0;
        }
    
        $matricula->persona_id_created_at= $usuario;
        $matricula->observacion='Pago en Línea.'.$r->observacion;

            if( Input::has('especialidad_programacion_id') ){
                $matricula->especialidad_programacion_id= $r->especialidad_programacion_id;
            }

        $matricula->save();

        $programacion_id= $r->programacion_id;
        $curso_id= $r->curso_id;
        
        if( $r->has('especialidad_id') AND $r->especialidad_id!='' ){
            $cursos= DB::table('mat_cursos_especialidades')
                    ->where('estado', 1)
                    ->where('especialidad_id', $r->especialidad_id)
                    ->get();
            foreach ($cursos as $key => $value) {
                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->norden=$key+1;
                $mtdetalle->matricula_id=$matricula->id;
                $mtdetalle->especialidad_id=$r->especialidad_id;
                $mtdetalle->tipo_matricula_detalle=2;
                $mtdetalle->nro_pago=0;
                $mtdetalle->monto_pago=0;
                $mtdetalle->nro_pago_certificado=0;
                $mtdetalle->monto_pago_certificado=0;
                $mtdetalle->tipo_pago=0;
                $mtdetalle->curso_id=$value->curso_id;

                for ($i=0; $i < count($programacion_id); $i++) { 
                    if( $curso_id[$i]==$value->curso_id ){
                        $mtdetalle->programacion_id= $programacion_id[$i];
                    }
                }
                $mtdetalle->persona_id_created_at= $usuario;
                $mtdetalle->save();
            }
        }
        else{
            $nro_pago= $r->nro_pago_programacion;
            $monto_pago= $r->monto_pago_programacion;
            for ($i=0; $i < count($programacion_id); $i++) { 

                $curso= Curso::find( $curso_id[$i] );

                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->norden=$i+1;
                $mtdetalle->matricula_id=$matricula->id;
                $mtdetalle->programacion_id=$programacion_id[$i]; 
                
                if( $curso->tipo_curso==2 ){
                    $mtdetalle->tipo_matricula_detalle=4;
                }
                else{
                    $mtdetalle->tipo_matricula_detalle=3;
                }

                $mtdetalle->nro_pago=0;
                $mtdetalle->monto_pago=0;
                $mtdetalle->nro_pago_certificado=$nro_pago[$i];
                $mtdetalle->monto_pago_certificado=$monto_pago[$i];
                $mtdetalle->tipo_pago=0;
                $mtdetalle->curso_id=$curso_id[$i];
                $mtdetalle->persona_id_created_at= $usuario;
                $mtdetalle->save();
            }
        }

        if( $r->has('nro_cuota') AND $r->has('especialidad_id') ){
            $matriculaCuotas= new MatriculaCuota;
            $matriculaCuotas->matricula_id= $matricula->id;
            $matriculaCuotas->cuota= 1;
            $matriculaCuotas->nro_cuota= $r->nro_cuota;
            $matriculaCuotas->monto_cuota= $r->monto_cuota;
            $matriculaCuotas->tipo_pago_cuota= 0;
            $matriculaCuotas->persona_id_created_at= $usuario;
            $matriculaCuotas->save();
        }
        DB::commit();

        $email=$persona->email_externo;
        $emailseguimiento='jorgeshevchenk@gmail.com';
        $parametros=array(
            'id'=>'123',
            'subject'=>'.::Bienvenido, inscripción realizada con éxito::.',
            'blade' => 'emails.inscripcion.inscripcion',
        );

        if( session('validar_email')==1 ){
            /*Mail::to($email)
            ->cc([$emailseguimiento])
            ->send( new EmailSend($parametros) );*/
        }

        $result= array(
            'dni'=> $r->dni,
            'msj'=> 'Inscripción realizada con éxito'
        );

        return $result;
    }

    public static function RegistrarInteresado( $r )
    {
        $return=array();
        if( !$r->has('dni') ){
            return 'dni => Falta DNI o Identificación de la persona';
        }
        if( !$r->has('tipo_documento') ){
            return 'tipo_documento => Falta tipo de documento de la persona';
        }
        elseif( !$r->has('paterno') ){
            return 'paterno => Falta Paterno';
        }
        elseif( !$r->has('materno') ){
            return 'materno => Falta Materno';
        }
        elseif( !$r->has('nombre') ){
            return 'nombre => Falta Nombre';
        }
        elseif( !$r->has('email') ){
            return 'email => Falta Email';
        }
        elseif( !$r->has('celular') ){
            return 'celular => Falta Celular';
        }
        elseif( !$r->has('empresa_id') ){
            return 'empresa_id => Falta Id Empresa';
        }
        elseif( !$r->has('carrera') AND !$r->has('curso')  ){
            return 'carrera => Falta Carrera o Curso interesado';
        }
        
        $comentario='';
        if( $r->has('comentario') ){
            $comentario= $r->comentario;
        }
        
        $persona= Persona::where('dni',$r->dni)
                  ->orWhere('email', $r->email)
                  ->first();
        $usuario= 0;
        $extension='';
        $interesado='';
        if( $r->has('carrera') or $r->has('curso') ){
            if( $r->has('carrera') ){
                $interesado= $r->carrera;
            }
            else{
                $interesado= $r->curso;
            }
        }

        DB::beginTransaction();
        $nuevo=true;
        if( !isset($persona->id) ){
            $persona= new Persona;
            $persona->paterno= $r->paterno;
            $persona->materno= $r->materno;
            $persona->nombre= $r->nombre;
            $persona->tipo_documento= $r->tipo_documento;
            $persona->dni= $r->dni;
            $persona->email= $r->email;
            $persona->email_externo= $r->email;
            $persona->celular= $r->celular;
            $persona->descripcion= $comentario;
            $persona->carrera= $interesado;
            $persona->empresa_interesado_id= $r->empresa_id;
            $persona->password=bcrypt($r->dni);
            $persona->persona_id_created_at= $usuario;
            $persona->save();
        }
        else{
            $nuevo=false;
            $persona= Persona::find($persona->id);
            $persona->email_externo= $r->email;
            $persona->persona_id_updated_at= $usuario;
            $persona->descripcion= $comentario;
            $persona->carrera= $interesado;
            $persona->save();
        }

        if( $nuevo==false ){
            DB::table('personas_captadas')
            ->where('persona_id', '=', $persona->id)
            ->where('empresa_id', '=', $r->empresa_id)
            ->where('interesado', '=', $interesado)
            ->update(
                array(
                    'estado' => 0,
                    'persona_id_updated_at' => $usuario,
                    'updated_at' => date('Y-m-d H:i:s')
                )
            );
        }

        DB::table('personas_captadas')
        ->insert(
            array(
                'persona_id' => $persona->id,
                'empresa_id' => $r->empresa_id,
                'fuente' => 'WEB',
                'interesado' => $interesado,
                'comentario' => $comentario,
                'estado' => 1,
                'created_at'=> date('Y-m-d h:m:s'),
                'persona_id_created_at'=> $usuario,
                'persona_id_updated_at' => $usuario
            )
        );

        /*if( $nuevo==true ){
            DB::table('personas_distribuciones')
            ->insert(
                array(
                    'persona_id' => $persona->id,
                    'trabajador_id' => (2+$r->empresa_id),
                    'fecha_distribucion' => date('Y-m-d'),
                    'estado' => 1,
                    'created_at'=> date('Y-m-d h:m:s'),
                    'persona_id_created_at'=> $usuario,
                    'persona_id_updated_at' => $usuario
                )
            );
        }*/

        DB::commit();

        $email=$persona->email_externo;
        $emailseguimiento='jorgeshevchenk@gmail.com';
        $parametros=array(
            'id'=>'123',
            'subject'=>'.::Bienvenido '.$persona->nombre.' '.$persona->paterno.'::.',
            'blade' => 'emails.interesado.api',
        );

        if( session('validar_email')==1 ){
            /*Mail::to($email)
            ->cc([$emailseguimiento])
            ->send( new EmailSend($parametros) );*/
        }

        $result= array(
            'dni'=> $r->dni,
            'msj'=> 'Registro de Interesado(a) con éxito'
        );

        return $result;
    }

    public static function ValidarInteresado( $r )
    {
        $return=array();
        $dni=-1;
        $email=-1;

        if( $r->has('dni') ){
            $dni=$r->dni;
        }
        elseif( $r->has('email') ){
            $email=$r->email;
        }

        $persona= DB::table('personas AS p')
                    ->join('personas_captadas AS pc','pc.persona_id','=','p.id')
                    ->join('empresas AS e','e.id','=','pc.empresa_id')
                    ->select('p.paterno','p.materno','p.nombre','p.dni','p.email','p.celular','pc.comentario','pc.interesado AS carrera_curso_interesado','e.empresa')
                    ->where('dni',$dni)
                    ->orWhere('email', $email)
                    ->where( function($query) use($r){
                        if( $r->has('fecha') ){
                            $query->whereRaw('DATE(pc.created_at)=\''.$r->fecha.'\'');
                        }
                    })
                    ->get();


        $result= array(
            'persona'=> $persona
        );

        return $result;
    }
}
