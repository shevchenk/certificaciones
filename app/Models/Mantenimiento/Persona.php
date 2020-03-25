<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\Proceso\Visita;
use App\Models\Proceso\Matricula;
use App\Models\Mantenimiento\PersonaLlamada;
use DB;

class Persona extends Model
{
    protected   $table = 'personas';

    public static function ConfirmarInscripcion($r)
    {
        $persona_id= $r->persona_id;
        $matricula_id= $r->matricula_id;

        DB::beginTransaction();
        $persona= Persona::find($persona_id);
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        $persona->celular = trim( $r->celular );
        $persona->password=bcrypt($r->password);
        if( $r->has('fecha_nacimiento') AND trim( $r->fecha_nacimiento )!='' ){
            $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
        }
        $persona->estado=1;
        $persona->persona_id_updated_at=$persona_id;
        $persona->save();

        $matricula= Matricula::find($matricula_id);
        $matricula->validada=1;
        $matricula->persona_id_updated_at=$persona_id;
        $matricula->save();
        DB::commit();
    }

    public static function runEditStatus($r)
    {
        $persona_id = Auth::user()->id;
        $persona = Persona::find($r->id);
        $persona->estado = trim( $r->estadof );
        $persona->persona_id_updated_at=$persona_id;
        $persona->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = new Persona;
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        if(trim( $r->password )!=''){
            $persona->password=bcrypt($r->password);
        }
        else{
            $persona->password=bcrypt($r->dni);
        }
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );
        if( $r->has('carrera') AND $r->carrera!='' ){
            $persona->carrera = trim( $r->carrera );
        }
        if(trim( $r->fecha_nacimiento )!=''){
        $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );}
        else {
        $persona->fecha_nacimiento = null;
        }
        $persona->estado = '1';
        $persona->persona_id_created_at=$persona_id;

        if( $r->has('tenencia') AND trim($r->tenencia)!='' ){
            $persona->tenencia = $r->tenencia;
        }
        if( $r->has('estado_civil') AND trim($r->estado_civil)!='' ){
            $persona->estado_civil = $r->estado_civil;
        }
        if( $r->has('direccion_dir') AND trim($r->direccion_dir)!='' ){
            $persona->direccion_dir = $r->direccion_dir;
        }
        if( $r->has('referencia_dir') AND trim($r->referencia_dir)!='' ){
            $persona->referencia_dir = $r->referencia_dir;
        }
        if( $r->has('pais_id') AND trim($r->pais_id)!='' ){
            $persona->pais_id = $r->pais_id;
        }
        if( $r->has('colegio_id') AND trim($r->colegio_id)!='' ){
            $persona->colegio_id = $r->colegio_id;
        }
        if( $r->has('distrito_id_dir') AND trim($r->distrito_id_dir)!='' ){
            $persona->distrito_id_dir = $r->distrito_id_dir;
            $persona->provincia_id_dir = $r->provincia_id_dir;
            $persona->region_id_dir = $r->region_id_dir;
        }
        if( $r->has('distrito_id') AND trim($r->distrito_id)!='' ){
            $persona->distrito_id = $r->distrito_id;
            $persona->provincia_id = $r->provincia_id;
            $persona->region_id = $r->region_id;
        }

        $persona->save();
        
        if ($r->cargos_selec) {
                $cargos=$r->cargos_selec;
                $cargos = explode(',', $cargos);
                if (is_array($cargos)) {
                    for ($i=0; $i<count($cargos); $i++) {
                        $cargoId = $cargos[$i];

                         $areas = $r['areas'.$cargoId];

                        for ($j=0; $j<count($areas); $j++) {
                            //recorrer las areas y buscar si exten
                            $areaId = $areas[$j];
                            DB::table('personas_privilegios_sucursales')->insert(
                                array(
                                    'sucursal_id' => $areaId,
                                    'privilegio_id' => $cargoId,
                                    'persona_id' => $persona->id,
                                    'created_at'=> date('Y-m-d H:i:s'),
                                    'persona_id_created_at'=> Auth::user()->id,
                                    'estado' => 1,
                                    'persona_id_updated_at' => Auth::user()->id
                                )
                            );
                        }
                    }
                }
            }

        if( $r->has('empresa') ){
            $empresa= $r->empresa;
            for ($i=0; $i < count($empresa) ; $i++) { 
                $PE= new PersonaEmpresa;
                $PE->persona_id= $persona->id;
                $PE->empresa_id= $empresa[$i];
                $PE->estado=1;
                $PE->persona_id_created_at= Auth::user()->id;
                $PE->save();
            }
        }
        DB::commit();
    }

    public static function runEdit($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = Persona::find($r->id);
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        if(trim( $r->password )!=''){
        $persona->password=bcrypt($r->password);
        }
        else if( $r->dni!=$persona->dni){
            $persona->password=bcrypt($r->dni);
        }
        
        $persona->dni = trim( $r->dni );
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );

        if(trim( $r->fecha_nacimiento )!='') 
        {
        $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
        }
        else
        {
        $persona->fecha_nacimiento = null;
        }

        $persona->estado = trim( $r->estado );
        $persona->persona_id_updated_at=$persona_id;
        $persona->save();
        
        DB::table('personas_privilegios_sucursales')
                ->where('persona_id', $r->id)
                ->update(array('estado' => 0,
                    'persona_id_updated_at' => Auth::user()->id));
        
        $cargos = $r->cargos_selec;
         if ($cargos) {//si selecciono algun cargo
                $cargos = explode(',', $cargos);
                $areas=array();

                //recorrer os cargos y verificar si existen
                for ($i=0; $i<count($cargos); $i++) {
                    $cargoId = $cargos[$i];
                    $areas = $r['areas'.$cargoId];

                    DB::table('personas_privilegios_sucursales')
                            ->where('privilegio_id', '=', $cargoId)
                            ->where('persona_id', '=', $r->id)
                            ->update(
                                array(
                                    'estado' => 0,
                                    'persona_id_updated_at' => Auth::user()->id
                                    )
                                );
                    
                    //almacenar las areas seleccionadas
                    for ($j=0; $j<count($areas); $j++) {
                        //recorrer las areas y buscar si exten
                        $areaId = $areas[$j];
                        $areaCargoPersona=DB::table('personas_privilegios_sucursales')
                                ->where('sucursal_id', '=', $areaId)
                                ->where('privilegio_id', $cargoId)
                                ->where('persona_id', $r->id)
                                ->first();
                        if (!isset($areaCargoPersona->id)) {
                            DB::table('personas_privilegios_sucursales')->insert(
                                array(
                                    'sucursal_id' => $areaId,
                                    'privilegio_id' => $cargoId,
                                    'persona_id' => $r->id,
                                    'created_at'=> date('Y-m-d H:i:s'),
                                    'persona_id_created_at'=> Auth::user()->id,
                                    'estado' => 1,
                                    'persona_id_updated_at' => Auth::user()->id
                                )
                            );
                        } else {
                            DB::table('personas_privilegios_sucursales')
                            ->where('sucursal_id', '=', $areaId)
                            ->where('privilegio_id', '=', $cargoId)
                            ->update(
                                array(
                                    'estado' => 1,
                                    'persona_id_updated_at' => Auth::user()->id
                                ));
                        }
                    }
                }
            }


        if( $r->has('empresa') ){
            DB::table('personas_empresas')
            ->where('persona_id', '=', $persona->id)
            ->update(
                array(
                    'estado' => 0,
                    'persona_id_updated_at' => Auth::user()->id
                )
            );

            $empresa= $r->empresa;
            $PE=array();
            for ($i=0; $i < count($empresa) ; $i++) { 
                $valida= PersonaEmpresa::where('persona_id',$persona->id)
                        ->where('empresa_id',$empresa[$i])
                        ->first();
                if( isset($valida->id) ){
                    $PE= PersonaEmpresa::find($valida->id);
                    $PE->persona_id_updated_at= Auth::user()->id;
                }
                else{
                    $PE= new PersonaEmpresa;
                    $PE->persona_id= $persona->id;
                    $PE->empresa_id= $empresa[$i];
                    $PE->persona_id_created_at= Auth::user()->id;
                }
                $PE->estado=1;
                $PE->save();
            }
        }
        DB::commit();
    }

    public static function runEditLibre($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = Persona::find($r->id);
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        if( $r->dni!=$persona->dni){
            $persona->password=bcrypt($r->dni);
        }
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );
        $persona->carrera = trim( $r->carrera );
        $persona->persona_id_updated_at=$persona_id;

        $persona->tenencia = trim( $r->tenencia );
        $persona->estado_civil = trim( $r->estado_civil );
        $persona->direccion_dir = trim( $r->direccion_dir );
        $persona->referencia_dir = trim( $r->referencia_dir );
        if( $r->has('pais_id') AND trim($r->pais_id)!='' ){
            $persona->pais_id = $r->pais_id;
        }

        if( $r->has('colegio_id') AND trim($r->colegio_id)!='' ){
            $persona->colegio_id = $r->colegio_id;
        }

        if( $r->has('distrito_id_dir') AND trim($r->distrito_id_dir)!='' ){
            $persona->distrito_id_dir = $r->distrito_id_dir;
            $persona->provincia_id_dir = $r->provincia_id_dir;
            $persona->region_id_dir = $r->region_id_dir;
        }

        if( $r->has('distrito_id') AND trim($r->distrito_id)!='' ){
            $persona->distrito_id = $r->distrito_id;
            $persona->provincia_id = $r->provincia_id;
            $persona->region_id = $r->region_id;
        }

        if( $r->has('fecha_nacimiento') AND trim( $r->fecha_nacimiento )!='' ){
            $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
        }

        $persona->save();
        DB::commit();
    }

    public static function runEditLibreLlamada($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = PersonaLLamada::find($r->id);
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        if( $r->dni!=$persona->dni){
            $persona->password=bcrypt($r->dni);
        }
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );
        $persona->carrera = trim( $r->carrera );
        $persona->persona_id_updated_at=$persona_id;

        $persona->tenencia = trim( $r->tenencia );
        $persona->estado_civil = trim( $r->estado_civil );
        $persona->direccion_dir = trim( $r->direccion_dir );
        $persona->referencia_dir = trim( $r->referencia_dir );
        if( $r->has('pais_id') AND trim($r->pais_id)!='' ){
            $persona->pais_id = $r->pais_id;
        }

        if( $r->has('colegio_id') AND trim($r->colegio_id)!='' ){
            $persona->colegio_id = $r->colegio_id;
        }

        if( $r->has('distrito_id_dir') AND trim($r->distrito_id_dir)!='' ){
            $persona->distrito_id_dir = $r->distrito_id_dir;
            $persona->provincia_id_dir = $r->provincia_id_dir;
            $persona->region_id_dir = $r->region_id_dir;
        }

        if( $r->has('distrito_id') AND trim($r->distrito_id)!='' ){
            $persona->distrito_id = $r->distrito_id;
            $persona->provincia_id = $r->provincia_id;
            $persona->region_id = $r->region_id;
        }

        if( $r->has('fecha_nacimiento') AND trim( $r->fecha_nacimiento )!='' ){
            $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
        }

        $persona->save();
        DB::commit();
    }

    public static function runLoad($r)
    {
        $sql=DB::table('personas AS p')
            ->leftJoin('mat_ubicacion_region AS r','r.id','=','p.region_id_dir')
            ->leftJoin('mat_ubicacion_provincia AS pr','pr.id','=','p.provincia_id_dir')
            ->leftJoin('mat_ubicacion_distrito AS d','d.id','=','p.distrito_id_dir')
            ->leftJoin('visitas AS v', function($join){
                $join->on('v.persona_id','=','p.id')
                ->where('v.ultimo_registro',1);
            })
            ->leftJoin('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','v.tipo_llamada_id');
            })
            ->leftJoin('tipo_llamadas_sub AS tls', function($join){
                $join->on('tls.id','=','v.tipo_llamada_sub_id');
            })
            ->leftJoin('tipo_llamadas_sub_detalle AS tlsd', function($join){
                $join->on('tlsd.id','=','v.tipo_llamada_sub_detalle_id');
            })
            ->select('p.id','p.paterno','p.materno','p.nombre','p.dni','p.carrera','p.fuente','p.empresa',
            'p.email',DB::raw('IFNULL(p.fecha_nacimiento,"") as fecha_nacimiento'),'p.sexo','p.telefono',
            'p.celular','p.password','p.estado','p.frecuencia','p.medio_publicitario_id','p.sucursal_id',
            'p.hora_inicio','p.hora_final','p.region_id_dir','p.provincia_id_dir','p.distrito_id_dir',
            'r.region AS region_dir','pr.provincia AS provincia_dir','d.distrito AS distrito_dir',
            'p.referencia_dir','v.tipo_llamada_id','v.tipo_llamada_sub_id','v.tipo_llamada_sub_detalle_id',
            'v.fechas')
            ->where( 
                function($query) use ($r){
                    if( Auth::user()->id!=1 ){
                        $query->where('p.id','!=',1);
                    }
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("email") ){
                        $email=trim($r->email);
                        if( $email !='' ){
                            $query->where('p.email','like','%'.$email.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('p.estado','=',$estado);
                        }
                    }
                    if( $r->has("telefono") ){
                        $telefono=trim($r->telefono);
                        if( $telefono !='' ){
                            $query->where('p.telefono','like','%'.$telefono.'%');
                        }
                    }
                    if( $r->has("celular") ){
                        $celular=trim($r->celular);
                        if( $celular !='' ){
                            $query->where('p.celular','like','%'.$celular.'%');
                        }
                    }
                    if( $r->has("carrera") ){
                        $carrera=trim($r->carrera);
                        if( $carrera !='' ){
                            $query->where('p.carrera','like','%'.$carrera.'%');
                        }
                    }
                    if( $r->has("created_at") ){
                        $created_at=trim($r->created_at);
                        if( $created_at !='' ){
                            $query->whereRaw('DATE(p.created_at)=?',$created_at);
                        }
                    }
                }
            );
        $result = $sql->orderBy('p.paterno','asc')->paginate(10);
        return $result;
    }

    public static function runLoadDistribuida($r)
    {
        $sql=DB::table('personas_llamadas AS p')
            ->Join('personas_distribuciones AS pd', function($join){
                $join->on('pd.persona_id','=','p.id')
                ->where('pd.estado',1);
            })
            ->Join('mat_trabajadores AS t', function($join){
                $join->on('t.id','=','pd.trabajador_id')
                ->where('t.empresa_id', Auth::user()->empresa_id)
                ->where('t.estado',1);
            })
            ->Join('personas AS p2', function($join){
                $join->on('p2.id','=','t.persona_id')
                ->where('p2.estado',1);
            })
            ->leftJoin(DB::raw('(SELECT tl.peso, tl.tipo_llamada,ll.persona_id
                FROM llamadas AS ll 
                INNER JOIN mat_trabajadores t2 ON t2.id = ll.trabajador_id AND t2.empresa_id = '.Auth::user()->empresa_id.' 
                INNER JOIN `tipo_llamadas` AS `tl` ON `tl`.`id` = `ll`.`tipo_llamada_id` 
                WHERE `ll`.`estado` = 1 AND `ll`.`ultimo_registro` = 1 ) AS tl'), function($join){
                $join->on('tl.persona_id','=','p.id');
            })
            ->select('p.id','p.paterno','p.materno','p.nombre','p.dni','pd.fecha_distribucion',
            'p.email',DB::raw('IFNULL(p.fecha_nacimiento,"") as fecha_nacimiento'),'p.sexo','p.telefono','p.carrera',
            'p.celular','p.password','p.estado','p.empresa','p.fuente','p.tipo','tl.tipo_llamada','p.fecha_registro',
            DB::raw('0 AS matricula,
            CONCAT(p2.paterno," ",p2.materno," ",p2.nombre) AS vendedor, tl.peso'))
            ->where('p.id','!=',1)
            ->where( 
                function($query) use ($r){
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("email") ){
                        $email=trim($r->email);
                        if( $email !='' ){
                            $query->where('p.email','like','%'.$email.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('p.estado','=',$estado);
                        }
                    }
                    if( $r->has("teleoperadora") ){
                        $teleoperadora=trim($r->teleoperadora);
                        if( $teleoperadora !='' ){
                            $query->where('pd.trabajador_id','=',$teleoperadora);
                        }
                    }
                    if( $r->has("telefono") ){
                        $telefono=trim($r->telefono);
                        if( $telefono !='' ){
                            $query->where('p.telefono','like','%'.$telefono.'%');
                        }
                    }
                    if( $r->has("celular") ){
                        $celular=trim($r->celular);
                        if( $celular !='' ){
                            $query->where('p.celular','like','%'.$celular.'%');
                        }
                    }
                    if( $r->has("fecha_distribucion") ){
                        $fecha_distribucion=trim($r->fecha_distribucion);
                        if( $fecha_distribucion !='' ){
                            $query->where('pd.fecha_distribucion','like','%'.$fecha_distribucion.'%');
                        }
                    }
                    if( $r->has("carrera") ){
                        $carrera=trim($r->carrera);
                        if( $carrera !='' ){
                            $query->where('p.carrera','like','%'.$carrera.'%');
                        }
                    }
                    if( $r->has("tipo_llamada") ){
                        $tipo_llamada=trim($r->tipo_llamada);
                        if( $tipo_llamada !='' ){
                            $query->where('tl.tipo_llamada','like','%'.$tipo_llamada.'%');
                        }
                    }
                    if( $r->has("vendedor") ){
                        $vendedor=trim($r->vendedor);
                        if( $vendedor !='' ){
                            $query->whereRaw('CONCAT(p2.paterno," ",p2.materno," ",p2.nombre) LIKE "%'.$vendedor.'%"');
                        }
                    }
                    if( $r->has("fuente") ){
                        $fuente=trim($r->fuente);
                        if( $fuente !='' ){
                            $query->where('p.fuente','like','%'.$fuente.'%');
                        }
                    }
                    if( $r->has("tipo") ){
                        $tipo=trim($r->tipo);
                        if( $tipo !='' ){
                            $query->where('p.tipo','like','%'.$tipo.'%');
                        }
                    }
                    if( $r->has("empresa") ){
                        $empresa=trim($r->empresa);
                        if( $empresa !='' ){
                            $query->where('p.empresa','like','%'.$empresa.'%');
                        }
                    }
                    if( $r->has("fecha_registro") ){
                        $fecha_registro=trim($r->fecha_registro);
                        if( $fecha_registro !='' ){
                            $query->where('p.fecha_registro','like','%'.$fecha_registro.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('tl.peso','asc')
                    ->orderBy('tl.tipo_llamada','asc')
                    ->orderBy('pd.fecha_distribucion','desc')
                    ->paginate(10);
        return $result;
    }

    public static function runLoadDistribuidaTotal($r)
    {
        $sql=DB::table('personas AS p')
            ->leftJoin('llamadas AS ll', function($join){
                $join->on('ll.persona_id','=','p.id')
                ->where('ll.estado',1)
                ->where('ll.ultimo_registro',1);
            })
            ->leftJoin('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','ll.tipo_llamada_id');
            })
            ->select('p.id','p.paterno','p.materno','p.nombre','p.dni',
            'p.email',DB::raw('IFNULL(p.fecha_nacimiento,"") as fecha_nacimiento'),'p.sexo','p.telefono','p.carrera',
            'p.celular','p.password','p.estado','p.empresa','p.fuente','p.tipo','tl.tipo_llamada','p.fecha_registro',
            DB::raw(' (SELECT count(id) FROM mat_matriculas AS m WHERE m.persona_id=p.id AND m.estado=1) AS matricula ')
            )
            ->where('p.id','!=',1)
            ->where( 
                function($query) use ($r){
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("email") ){
                        $email=trim($r->email);
                        if( $email !='' ){
                            $query->where('p.email','like','%'.$email.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('p.estado','=',$estado);
                        }
                    }
                    if( $r->has("telefono") ){
                        $telefono=trim($r->telefono);
                        if( $telefono !='' ){
                            $query->where('p.telefono','like','%'.$telefono.'%');
                        }
                    }
                    if( $r->has("celular") ){
                        $celular=trim($r->celular);
                        if( $celular !='' ){
                            $query->where('p.celular','like','%'.$celular.'%');
                        }
                    }
                    if( $r->has("carrera") ){
                        $carrera=trim($r->carrera);
                        if( $carrera !='' ){
                            $query->where('p.carrera','like','%'.$carrera.'%');
                        }
                    }
                    if( $r->has("tipo_llamada") ){
                        $tipo_llamada=trim($r->tipo_llamada);
                        if( $tipo_llamada !='' ){
                            $query->where('tl.tipo_llamada','like','%'.$tipo_llamada.'%');
                        }
                    }
                    if( $r->has("fuente") ){
                        $fuente=trim($r->fuente);
                        if( $fuente !='' ){
                            $query->where('p.fuente','like','%'.$fuente.'%');
                        }
                    }
                    if( $r->has("tipo") ){
                        $tipo=trim($r->tipo);
                        if( $tipo !='' ){
                            $query->where('p.tipo','like','%'.$tipo.'%');
                        }
                    }
                    if( $r->has("empresa") ){
                        $empresa=trim($r->empresa);
                        if( $empresa !='' ){
                            $query->where('p.empresa','like','%'.$empresa.'%');
                        }
                    }
                    if( $r->has("fecha_registro") ){
                        $fecha_registro=trim($r->fecha_registro);
                        if( $fecha_registro !='' ){
                            $query->where('p.fecha_registro','like','%'.$fecha_registro.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('p.paterno','asc')->paginate(10);
        return $result;
    }
    
    public static function getAreas($personaId) {
        //subconsulta
        $sql =  DB::table('personas_privilegios_sucursales as cp')
                ->join(
                        'privilegios as c', 'cp.privilegio_id', '=', 'c.id'
                )
                //->join(
                //        'area_cargo_persona as acp', 'cp.id', '=', 'acp.cargo_persona_id'
                //)
                ->join(
                        'sucursales as a', 'cp.sucursal_id', '=', 'a.id'
                )
                ->select(
                        DB::raw(
                                "
                                CONCAT(c.id, '-',
                                    GROUP_CONCAT(a.id)
                                ) AS info"
                        )
                )
                ->whereRaw("cp.persona_id=$personaId AND cp.estado=1 AND c.estado=1 ")
                ->groupBy('c.id');
        //consulta
        $areas = DB::table(DB::raw("(" . $sql->toSql() . ") as a"))
                ->select(
                        DB::raw("GROUP_CONCAT( info SEPARATOR '|'  ) as DATA ")
                )
                ->get();

        return $areas;
    }

    public static function ListarFuente($personaId) {
        //subconsulta
        $sql = DB::table('personas')
                ->select(DB::raw('DISTINCT(fuente) AS fuente'))
                ->get();

        return $sql;
    }

    public static function ListarTipo($personaId) {
        //subconsulta
        $sql = DB::table('personas')
                ->select(DB::raw('DISTINCT(tipo) AS tipo'))
                ->where('tipo','!=','')
                ->get();

        return $sql;
    }
    
    public static function ListarEmpresa($personaId) {
        //subconsulta
        $sql = DB::table('personas')
                ->select(DB::raw('DISTINCT(empresa) AS empresa'))
                ->where('empresa','!=','')
                ->get();

        return $sql;
    }

    public static function runNewVisitante($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = new Persona;
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        if(trim( $r->password )!=''){
            $persona->password=bcrypt($r->password);
        }
        else{
            $persona->password=bcrypt($r->dni);
        }
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );
        if(trim( $r->fecha_nacimiento )!=''){
        $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );}
        else {
        $persona->fecha_nacimiento = null;
        }

        $persona->carrera = trim( $r->carrera );
        $persona->sucursal_id = trim( $r->sucursal );
        $persona->medio_publicitario_id= trim($r->medio_publicitario);
        $persona->hora_inicio= trim($r->hora_inicio);
        $persona->hora_final= trim($r->hora_final);
        $dia= implode(",",$r->dia);
        $persona->frecuencia= $dia;

        $persona->distrito_id_dir = $r->distrito_id_dir;
        $persona->provincia_id_dir = $r->provincia_id_dir;
        $persona->region_id_dir = $r->region_id_dir;
        $persona->referencia_dir = $r->referencia_dir;

        $persona->estado = '1';
        $persona->persona_id_created_at=$persona_id;
        $persona->save();

        DB::table('visitas')
        ->where('persona_id','=', $persona->id)
        ->where('ultimo_registro',1)
        ->update([
            'ultimo_registro' => 0,
            'persona_id_updated_at' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $llamada= new Visita;
        $llamada->trabajador_id=$r->teleoperadora;
        $llamada->persona_id=$persona->id;

        if( Input::has('tipo_llamada') AND trim( $r->tipo_llamada )!='' ){
            $llamada->tipo_llamada_id=$r->tipo_llamada;
        }

        if( Input::has('fechas') AND trim( $r->fechas )!='' ){
            $llamada->fechas=$r->fechas;
        }

        if( Input::has('detalle_tipo_llamada') AND trim( $r->detalle_tipo_llamada )!='' ){
            $llamada->tipo_llamada_sub_id=$r->sub_tipo_llamada;
            $llamada->tipo_llamada_sub_detalle_id=$r->detalle_tipo_llamada;
        }

        $llamada->persona_id_created_at=Auth::user()->id;
        $llamada->save();

        DB::commit();
    }

    public static function runEditVisitante($r)
    {
        DB::beginTransaction();
        $persona_id = Auth::user()->id;
        $persona = Persona::find($r->id);
        $persona->paterno = trim( $r->paterno );
        $persona->materno = trim( $r->materno );
        $persona->nombre = trim( $r->nombre );
        $persona->dni = trim( $r->dni );
        $persona->sexo = trim( $r->sexo );
        $persona->email = trim( $r->email );
        if(trim( $r->password )!=''){
            $persona->password=bcrypt($r->password);
        }
        else{
            $persona->password=bcrypt($r->dni);
        }
        $persona->telefono = trim( $r->telefono );
        $persona->celular = trim( $r->celular );
        if(trim( $r->fecha_nacimiento )!=''){
        $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );}
        else {
        $persona->fecha_nacimiento = null;
        }

        $persona->carrera = trim( $r->carrera );
        $persona->sucursal_id = trim( $r->sucursal );
        $persona->medio_publicitario_id= trim($r->medio_publicitario);
        $persona->hora_inicio= trim($r->hora_inicio);
        $persona->hora_final= trim($r->hora_final);
        $dia= implode(",",$r->dia);
        $persona->frecuencia= $dia;

        $persona->estado = '1';
        $persona->persona_id_updated_at=$persona_id;
        $persona->save();
        DB::commit();
    }

    public static function ListarPersonaEmpresa($personaId) {
        //subconsulta
        $sql = DB::table('personas_empresas')
                ->select( DB::raw('GROUP_CONCAT(empresa_id) empresa_id') )
                ->where('persona_id',$personaId)
                ->where('estado',1)
                ->groupBy('persona_id')
                ->first();

        return $sql;
    }

    public static function ListDistrito($r)
    {
        $sql=DB::table('mat_ubicacion_distrito as di')
            ->select('di.id','di.distrito','di.provincia_id'
            ,'pr.region_id','pr.provincia','r.region',
            DB::raw('CONCAT(pr.provincia," | ",r.region) as detalle'))
            ->join('mat_ubicacion_provincia AS pr',function($join){
                $join->on('di.provincia_id','=','pr.id')
                ->where('pr.estado','=',1);
            })
            ->join('mat_ubicacion_region AS r',function($join){
                $join->on('pr.region_id','=','r.id')
                ->where('r.estado','=',1);
            })
            ->where(
                function($query) use ($r){
                    if( $r->has("phrase") ){
                        $phrase=trim($r->phrase);
                        if( $phrase !='' ){
                            $dphrase= explode("|",$phrase);
                            $dphrase[0]=trim($dphrase[0]);
                            $query->where('di.distrito','like','%'.$dphrase[0].'%');
                            if( count($dphrase)>1 AND trim($dphrase[1])!='' ){
                                $dphrase[1]=trim($dphrase[1]);
                                $query->where('pr.provincia','like','%'.$dphrase[1].'%');
                            }
                            if( count($dphrase)>2 AND trim($dphrase[2])!='' ){
                                $dphrase[2]=trim($dphrase[2]);
                                $query->where('r.region','like','%'.$dphrase[2].'%');
                            }
                        }
                    }
                }
            )
            ->where('di.estado','=','1');
        $result = $sql->get();
        return $result;
    }

    public static function ListColegio($r)
    {
        $sql=DB::table('colegios AS co')
            ->select('di.distrito','co.id','co.colegio','co.direccion')
            ->join('mat_ubicacion_distrito AS di',function($join){
                $join->on('co.distrito_id','=','di.id')
                ->where('di.estado','=',1);
            })
            ->where(
                function($query) use ($r){
                    if( $r->has("phrase") ){
                        $colegio=trim($r->phrase);
                        if( $colegio !='' ){
                            $query->where('co.colegio','like','%'.$colegio.'%');
                        }
                    }
                }
            )
            ->where('co.estado','=','1');
        $result = $sql->get();
        return $result;
    }

    public static function ListPais($r)
    {
        $sql=DB::table('paises as pa')
            ->select('pa.id','pa.pais','pa.abreviatura')
            ->where(
                function($query) use ($r){
                    if( $r->has("phrase") ){
                        $pais=trim($r->phrase);
                        if( $pais !='' ){
                            $query->where('pa.pais','like','%'.$pais.'%');
                        }
                    }
                }
            )
            ->where('pa.estado','=','1');
        $result = $sql->get();
        return $result;
    }

    public static function LoadAdicional($r)
    {
        $result=DB::table('personas as pa')
                ->leftJoin('mat_ubicacion_distrito AS di',function($join){
                    $join->on('pa.distrito_id','=','di.id')
                    ->where('di.estado','=',1);
                })
                ->leftJoin('mat_ubicacion_provincia AS pr',function($join){
                    $join->on('pa.provincia_id','=','pr.id')
                    ->where('pr.estado','=',1);
                })
                ->leftJoin('mat_ubicacion_region AS re',function($join){
                    $join->on('pa.region_id','=','re.id')
                    ->where('re.estado','=',1);
                })
                ->leftJoin('mat_ubicacion_distrito AS di2',function($join){
                    $join->on('pa.distrito_id_dir','=','di2.id')
                    ->where('di2.estado','=',1);
                })
                ->leftJoin('mat_ubicacion_provincia AS pr2',function($join){
                    $join->on('pa.provincia_id_dir','=','pr2.id')
                    ->where('pr2.estado','=',1);
                })
                ->leftJoin('mat_ubicacion_region AS re2',function($join){
                    $join->on('pa.region_id_dir','=','re2.id')
                    ->where('re2.estado','=',1);
                })
                ->leftJoin('paises AS p',function($join){
                    $join->on('pa.pais_id','=','p.id')
                    ->where('p.estado','=',1);
                })
                ->leftJoin('colegios AS co',function($join){
                    $join->on('pa.colegio_id','=','co.id')
                    ->where('co.estado','=',1);
                })
                ->select('pa.pais_id','pa.colegio_id','pa.distrito_id','pa.provincia_id'
                ,'pa.region_id','pa.distrito_id_dir','pa.provincia_id_dir','pa.region_id_dir'
                ,'pa.direccion_dir','pa.tenencia','pa.referencia_dir','pa.estado_civil'
                ,'di.distrito','pr.provincia','re.region','p.pais'
                ,'di2.distrito AS distrito_dir','pr2.provincia AS provincia_dir'
                ,'re2.region AS region_dir','co.colegio','pa.fecha_nacimiento');

                /*if( $r->has('todo') ){
                    if( $r->todo==1 ){
                        $result->leftJoin('mat_ubicacion_distrito AS di3',function($join){
                            $join->on('co.distrito_id','=','di3.id')
                            ->where('di3.estado','=',1);
                        })
                        ->leftJoin('mat_ubicacion_provincia AS pr3',function($join){
                            $join->on('di3.provincia_id','=','pr3.id')
                            ->where('pr3.estado','=',1);
                        })
                        ->leftJoin('mat_ubicacion_region AS re3',function($join){
                            $join->on('pr3.region_id','=','re3.id')
                            ->where('re3.estado','=',1);
                        })
                        ->join('am_personas AS pe','pe.id','=','pa.persona_id')
                        ->addSelect('di3.distrito AS distrito_col'
                        ,'pr3.provincia AS provincia_col','re3.region AS region_col'
                        ,'pe.estado_civil','pe.fecha_nacimiento','pe.sexo','co.tipo'
                        );
                    }
                }*/

        $result= $result->where('pa.id',$r->persona_id)
                ->first();
        return $result;
    }
}
