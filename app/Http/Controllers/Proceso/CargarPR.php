<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumnos;
use App\Models\Mantenimiento\Especialidad;
use App\Models\Mantenimiento\Curso;
use App\Models\Mantenimiento\CursoEspecialidad;
use App\Models\Mantenimiento\Programacion;
use App\Models\Proceso\Certificados;
use App\Models\Proceso\Matricula;
use App\Models\Mantenimiento\Sucursal;
use App\Models\Mantenimiento\Persona;
use App\Models\Mantenimiento\Trabajador;

use App\Models\Mantenimiento\Provincia;
use App\Models\Mantenimiento\Distrito;
use App\Models\Mantenimiento\Region;
use App\Models\Mantenimiento\TipoParticipante;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\MatriculaDetalle;

use App\Models\Certificacion\Bandeja;

class CargarPR extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function CargarInteresadosCSV(Request $r) 
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        if (isset($_FILES['carga']) and $_FILES['carga']['size'] > 0) {

            $uploadFolder = 'csv';
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }
            $uploadFolder = 'csv/interesados';
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }

            $nombreArchivo = explode(".", $_FILES['carga']['name']);
            $tmpArchivo = $_FILES['carga']['tmp_name'];
            $archivoNuevo = $nombreArchivo[0] . "_u" . Auth::user()->id . "_" . date("Ymd_His") . "." . $nombreArchivo[1];
            $file = $uploadFolder . '/' . $archivoNuevo;

            //@unlink($file);
            $m = "Archivo no es formato csv";
            if ( $nombreArchivo[1]!='csv' ) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $csvxrow = file($tmpArchivo);   // ---- csv rows to array ----
            $keydata = explode(";",$csvxrow[0]);
            $keynumb = count($keydata); 
            $m = "Archivo no cuenta con la cabecera de asignar(0:Quitar Asignación | 1: Asignar)";
            if( $keynumb<14 ){
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $narchivo= $nombreArchivo[0]."_u";
            $interesado= DB::table('interesados')
                        ->whereRaw(' file LIKE "'.$uploadFolder.'/'.$narchivo.'%" ')
                        ->where('dni_final','!=','')
                        ->first();
            $m = "Archivo fue procesado anteriormente";
            if ( isset($interesado->file) ) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $m = "Ocurrio un error al subir el archivo. No pudo guardarse.";
            if (!move_uploaded_file($tmpArchivo, $file)) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $usuario= Auth::user()->id;
            $empresa_id= Auth::user()->empresa_id;
            /*$sql="SET GLOBAL local_infile = 'ON';";
            DB::statement($sql);*/
            $sql="SET @numero=0";
            DB::statement($sql);
            DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '$file'
            INTO TABLE interesados
            character set latin1
            FIELDS TERMINATED BY ';'
            LINES TERMINATED BY '\n'
            IGNORE 1 ROWS 
            (
              FECHA_REGISTRO, EMPRESA, TIPO, FUENTE
              , DISTRITO, CARRERA, DNI, NOMBRE, PATERNO, MATERNO
              , CELULAR, EMAIL, COSTO, asignar, COD_VENDEDOR
            ) 
            SET usuario = ".$usuario.", file = '".$file."', pos= @numero:= @numero+1,
            DNI= IF(DNI REGEXP '^[0-9]+$',SUBSTRING(DNI,1,12),0),
            FECHA_REGISTRO= IF(FECHA_REGISTRO='0000-00-00', CURDATE(), FECHA_REGISTRO);");
            
            $sql="";
            $correlativo= Persona::where('persona_id_created_at',0)
                            ->where('dni','like','ID-%')
                            ->select('dni')
                            ->orderBy('dni','desc')
                            ->first();
            $inicial=0;
            if( isset($correlativo->dni) ){
                $dni= explode("-", $correlativo->dni);
                $inicial= $dni[1];
            }
            $return['inicial']= $inicial;

            DB::beginTransaction();

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON (p.email=i.EMAIL AND p.email!='') or (p.dni=i.DNI AND p.dni!='')
                    SET i.dni_final=p.dni
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND (i.DNI*1 > 0 OR i.EMAIL!='')";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    LEFT JOIN mat_matriculas m ON m.persona_id=p.id
                    SET p.carrera=i.CARRERA, p.fuente=i.FUENTE, p.email_externo= i.EMAIL
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND i.dni_final!=''
                    AND i.asignar=1
                    AND m.id IS NULL";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    SET i.dni_final='xxxx'
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND i.dni_final=''
                    AND i.DNI*1 = 0 
                    AND i.EMAIL=''";
            DB::update($sql);

            $sql="SET @numero=".$inicial.";";
            DB::statement($sql);

            $sql="  INSERT INTO personas (`password`, fuente, tipo, fecha_registro
                    , dni, paterno, materno, nombre, celular, email, email_externo, distrito_domicilio
                    , carrera, estado, created_at, persona_id_created_at, persona_id_updated_at)
                    SELECT \"\$2y\$10\$wOoTWVzNC4892hQXE97ne.7wfOfEfP4zp2XdjrBnMck0IXf2DRCwu\"
                    , MIN(i.FUENTE), MIN(i.TIPO), MIN(i.FECHA_REGISTRO)
                    , IF(MIN(i.DNI)*1=0,CONCAT('ID-',LPAD(@numero:=@numero+1,9,'0')),MIN(i.DNI))
                    , i.PATERNO, i.MATERNO, i.NOMBRE, MIN(i.CELULAR), MIN(i.EMAIL)
                    , MIN(i.EMAIL), MIN(i.DISTRITO), MIN(i.CARRERA), 3, NOW(), IF(MIN(i.DNI)*1=0,0,$usuario), $usuario
                    FROM interesados AS i
                    WHERE i.dni_final=''
                    AND i.usuario=".$usuario."
                    AND i.asignar=1
                    AND i.file='".$file."'
                    GROUP BY i.PATERNO, i.MATERNO, i.NOMBRE
                    ";
            DB::insert($sql);

            $sql="SET @numero=".$inicial.";";
            DB::statement($sql);

            $sql="  UPDATE interesados
                    SET dni_final=IF(DNI*1=0,CONCAT('ID-',LPAD(@numero:=@numero+1,9,'0')),DNI)
                    WHERE dni_final=''
                    AND usuario=".$usuario."
                    AND asignar=1
                    AND file='".$file."'";
            DB::update($sql);

            //Actualiza a estado activo a los usuarios
            $sql="  UPDATE personas
                    SET estado=1
                    WHERE estado=3";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    INNER JOIN personas_captadas pc ON pc.interesado=i.CARRERA AND pc.persona_id=p.id AND pc.empresa_id=$empresa_id 
                    SET pc.estado=0
                    WHERE i.usuario=".$usuario."
                    AND i.asignar=1
                    AND i.file='".$file."'";
            DB::update($sql);

            $sql="  INSERT INTO personas_captadas (persona_id, empresa_id, ad_name, campaign_name, fuente, interesado, fecha_registro, costo, estado, created_at, persona_id_created_at)
                    SELECT p.id, $empresa_id, i.EMPRESA, i.TIPO, i.FUENTE, i.CARRERA, i.FECHA_REGISTRO, i.COSTO, 1, NOW(), $usuario
                    FROM interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    WHERE i.usuario=".$usuario."
                    AND i.asignar=1
                    AND i.file='".$file."'";
            DB::insert($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    INNER JOIN personas_distribuciones pd ON pd.persona_id=p.id 
                    INNER JOIN mat_trabajadores mt ON mt.id=pd.trabajador_id AND mt.empresa_id=$empresa_id 
                    SET pd.estado=0, pd.updated_at= NOW()
                    WHERE i.usuario=".$usuario."
                    AND i.asignar=0
                    AND i.file='".$file."'";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    INNER JOIN personas_captadas pc ON pc.persona_id=p.id AND pc.empresa_id=$empresa_id 
                    SET pc.estado=0, pc.updated_at= NOW()
                    WHERE i.usuario=".$usuario."
                    AND i.asignar=0
                    AND i.file='".$file."'";
            DB::update($sql);

            DB::commit();

            $data = DB::table('interesados')
                    ->select('pos', 'DNI', 'EMAIL', 'asignar', 'FECHA_REGISTRO','FECHA_ENTREGA','dni_final')
                    ->where('usuario',$usuario)
                    ->where('file',$file)
                    ->where('dni_final','like','xxxx%')
                    ->get();
            if( count($data) > 0)
            {
                $return['data'] = $data;
                $return['rst'] = 3;
                $return['msj'] = 'Algunos datos no procesaron';
            }
            else
            {
                $return['rst'] = 1;
                $return['msj'] = 'Archivo procesado correctamente';
            }
            
            return response()->json($return);
        }
    }

    public function CargarInteresados(Request $r) 
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(600);
        if (isset($_FILES['carga']) and $_FILES['carga']['size'] > 0) {

            $uploadFolder = 'txt';
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }
            $uploadFolder = 'txt/interesados';
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }

            $nombreArchivo = explode(".", $_FILES['carga']['name']);
            $tmpArchivo = $_FILES['carga']['tmp_name'];
            $archivoNuevo = $nombreArchivo[0] . "_u" . Auth::user()->id . "_" . date("Ymd_His") . "." . $nombreArchivo[1];
            $file = $uploadFolder . '/' . $archivoNuevo;

            //@unlink($file);

            $m = "Ocurrio un error al subir el archivo. No pudo guardarse.";
            if (!move_uploaded_file($tmpArchivo, $file)) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $usuario= Auth::user()->id;
            $empresa_id= Auth::user()->empresa_id;
            /*$sql="SET GLOBAL local_infile = 'ON';";
            DB::statement($sql);*/
            $sql="SET @numero=0";
            DB::statement($sql);
            DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '$file'
            INTO TABLE interesados
            FIELDS TERMINATED BY '\t'
            LINES TERMINATED BY '\n'
            (
              EMPRESA, FUENTE, TIPO, FECHA_REGISTRO
              , DNI, PATERNO, MATERNO, NOMBRE, CELULAR, EMAIL, DISTRITO
              , SEDE, CARRERA, VENDEDOR, COD_VENDEDOR, FECHA_ENTREGA
            ) 
            SET usuario = ".$usuario.", file = '".$file."', pos= @numero:= @numero+1, 
            FECHA_ENTREGA= IF(FECHA_ENTREGA='0000-00-00', CURDATE(), FECHA_ENTREGA),
            FECHA_REGISTRO= IF(FECHA_REGISTRO='0000-00-00', CURDATE(), FECHA_REGISTRO);");
            
            $sql="";
            $correlativo= Persona::where('persona_id_created_at',0)
                            ->where('dni','like','ID-%')
                            ->select('dni')
                            ->orderBy('dni','desc')
                            ->first();
            $inicial=0;
            if( isset($correlativo->dni) ){
                $dni= explode("-",$correlativo->dni);
                $inicial= $dni[1]*1;
            }
            $return['inicial']= $inicial;

            DB::beginTransaction();

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON (p.email=i.EMAIL AND p.email!='') or (p.dni=i.DNI AND p.dni!='')
                    SET i.dni_final=p.dni
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final AND i.dni_final!=''
                    LEFT JOIN mat_matriculas m ON m.persona_id=p.id
                    SET p.carrera=i.CARRERA, p.empresa=i.EMPRESA, p.fuente=i.FUENTE, p.email_externo= i.EMAIL
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND m.id IS NULL";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    SET i.dni_final='xxxx'
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND i.DNI=''
                    AND i.EMAIL=''";
            DB::update($sql);

            $sql="  UPDATE interesados i 
                    LEFT JOIN mat_trabajadores t ON t.codigo=i.COD_VENDEDOR AND t.codigo!='' AND t.empresa_id='$empresa_id' 
                    SET i.dni_final='xxxxxxxx'
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND t.id IS NULL";
            DB::update($sql);

            $sql="SET @numero=".$inicial.";";
            DB::statement($sql);

            $sql="  INSERT INTO personas (`password`, empresa, fuente, tipo, fecha_registro
                    , dni, paterno, materno, nombre, celular, email, email_externo, distrito_domicilio
                    , sede, carrera, estado, created_at, persona_id_created_at, persona_id_updated_at)
                    SELECT \"\$2y\$10\$wOoTWVzNC4892hQXE97ne.7wfOfEfP4zp2XdjrBnMck0IXf2DRCwu\", i.EMPRESA, i.FUENTE, i.TIPO, i.FECHA_REGISTRO
                    ,IF(i.DNI*1=0,CONCAT('ID-',LPAD(@numero:=@numero+1,9,'0')),i.DNI), i.PATERNO, i.MATERNO, i.NOMBRE, i.CELULAR, i.EMAIL, i.EMAIL, i.DISTRITO
                    , i.SEDE, i.CARRERA, 3, NOW(), IF(i.DNI*1=0,0,$usuario), $usuario
                    FROM interesados AS i
                    WHERE i.dni_final=''
                    AND i.usuario=".$usuario."
                    AND i.file='".$file."'";
            DB::insert($sql);

            $sql="SET @numero=".$inicial.";";
            DB::statement($sql);

            $sql="  UPDATE interesados
                    SET dni_final=IF(DNI*1=0,CONCAT('ID-',LPAD(@numero:=@numero+1,9,'0')),DNI)
                    WHERE dni_final=''
                    AND usuario=".$usuario."
                    AND file='".$file."'";
            DB::update($sql);

            //Actualiza a estado activo a los usuarios
            $sql="  UPDATE personas
                    SET estado=1
                    WHERE estado=3";
            DB::update($sql);

            $sql="  UPDATE interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    INNER JOIN personas_captadas pc ON pc.interesado=i.CARRERA AND pc.persona_id=p.id 
                    SET pc.estado=0
                    AND i.usuario=".$usuario."
                    AND i.file='".$file."'
                    AND pc.empresa_id='".$empresa_id."'";
            DB::update($sql);

            $sql="  INSERT INTO personas_captadas (persona_id, empresa_id, fuente, interesado, estado, created_at, persona_id_created_at)
                    SELECT p.id, $empresa_id, i.FUENTE, i.CARRERA, 1, i.FECHA_REGISTRO, $usuario
                    FROM interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'";
            DB::insert($sql);

            $sql="  UPDATE personas_distribuciones pd
                    INNER JOIN mat_trabajadores t2 ON t2.id=pd.trabajador_id AND t2.empresa_id='$empresa_id'
                    INNER JOIN personas p ON p.id=pd.persona_id
                    INNER JOIN interesados i ON i.dni_final=p.dni
                    INNER JOIN mat_trabajadores t ON t.codigo=i.COD_VENDEDOR AND t.codigo!='' AND t.empresa_id='$empresa_id'
                    SET pd.estado=0
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'";
            DB::update($sql);

            //-- Distribucion de los vendedores
            $sql="  INSERT INTO personas_distribuciones 
                    (persona_id, trabajador_id, fecha_distribucion,estado,created_at,persona_id_created_at, persona_id_updated_at)
                    SELECT p.id,t.id,i.FECHA_ENTREGA,1,NOW(),0,$usuario
                    FROM interesados i
                    INNER JOIN personas p ON p.dni=i.dni_final
                    INNER JOIN mat_trabajadores t ON t.codigo=i.COD_VENDEDOR AND t.codigo!='' AND t.empresa_id='$empresa_id'
                    WHERE i.usuario=".$usuario."
                    AND i.file='".$file."'";
            DB::insert($sql);

            $sql="  UPDATE personas_distribuciones pd 
                    INNER JOIN mat_trabajadores t ON t.id=pd.trabajador_id AND t.empresa_id='$empresa_id'
                    INNER JOIN (
                        SELECT MAX(pd2.id) idmax, pd2.persona_id
                        FROM personas_distribuciones pd2 
                        INNER JOIN mat_trabajadores t2 ON t2.id=pd2.trabajador_id AND t2.empresa_id='$empresa_id'
                        WHERE pd2.estado=1 
                        GROUP BY pd2.persona_id
                        HAVING COUNT(pd2.id)>1
                    ) un on un.persona_id=pd.persona_id
                    SET pd.estado=0
                    WHERE pd.id!=un.idmax
                    AND pd.estado=1";
            DB::update($sql);

            DB::commit();

            $data = DB::table('interesados')
                    ->select('pos', 'DNI', 'EMAIL', 'COD_VENDEDOR', 'FECHA_REGISTRO','FECHA_ENTREGA','dni_final')
                    ->where('usuario',$usuario)
                    ->where('file',$file)
                    ->where('dni_final','like','xxxx%')
                    ->get();
            if( count($data) > 0)
            {
                $return['data'] = $data;
                $return['rst'] = 3;
                $return['msj'] = 'Algunos datos no procesaron';
            }
            else
            {
                $return['rst'] = 1;
                $return['msj'] = 'Archivo procesado correctamente';
            }
            
            return response()->json($return);
        }
    }

    public function CargarAlumnos(Request $r) 
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(600);
        if (isset($_FILES['carga']) and $_FILES['carga']['size'] > 0) {

            $uploadFolder = 'txt/alumnos';

            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }

            $nombreArchivo = explode(".", $_FILES['carga']['name']);
            $tmpArchivo = $_FILES['carga']['tmp_name'];
            $archivoNuevo = $nombreArchivo[0] . "_u" . Auth::user()->id . "_" . date("Ymd_his") . "." . $nombreArchivo[1];
            $file = $uploadFolder . '/' . $archivoNuevo;

            //@unlink($file);

            $m = "Ocurrio un error al subir el archivo. No pudo guardarse.";
            if (!move_uploaded_file($tmpArchivo, $file)) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $array = array();
            $arrayExist = array();

            $file=file('txt/alumnos/'.$archivoNuevo);
            
            for ($i = 0; $i < count($file); $i++) {

                DB::beginTransaction();
                if (trim($file[$i]) != '') {
                    $detfile = explode("\t", $file[$i]);

                    $con = 0;
                    for ($j = 0; $j < count($detfile); $j++) {
                        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "�", "\r", "\n\n", "\xEF", "\xBB", "\xBF",'\xD3N');
                        $reemplazar = "";
                        $detfile[$j] = trim(str_replace($buscar, $reemplazar, $detfile[$j]));
                        $array[$i][$j] = $detfile[$j];
                        $con++;
                    }

                    $alumnos = Alumnos::where('sucursal_id', '=', trim($detfile[0]))
                                        ->where('id_envio', '=', trim($detfile[1]))
                                        ->where('nombre', '=', trim($detfile[2]))
                                        ->where('paterno', '=', trim($detfile[3]))
                                        ->where('materno', '=', trim($detfile[4]))
                                        ->where('dni', '=', trim($detfile[5]))
                                        ->where('certificado', '=', trim( utf8_encode($detfile[6]) ) )
                                        ->where('nota_certificado', '=', trim($detfile[7]))
                                        ->where('tipo_certificado', '=', trim($detfile[8]))
                                        ->where('estado', '=', 1)
                                        ->first();

                    if (count($alumnos) == 0) 
                    {
                        // Graba Alumnos
                        $obj = new Alumnos;
                        $obj->sucursal_id = trim( $detfile[0] );
                        $obj->id_envio = trim( $detfile[1] );
                        $obj->nombre = trim( $detfile[2] );
                        $obj->paterno = trim( $detfile[3] );
                        $obj->materno = trim( $detfile[4] );
                        $obj->dni = trim( $detfile[5] );
                        $obj->certificado = trim( utf8_encode($detfile[6]) );
                        $obj->nota_certificado = trim( $detfile[7] );
                        $obj->tipo_certificado = trim( $detfile[8] );
                        $obj->direccion = trim( $detfile[9] );
                        $obj->referencia = trim( $detfile[10] );
                        $obj->region = trim( $detfile[11] );
                        $obj->provincia = trim( $detfile[12] );
                        $obj->distrito = trim( $detfile[13] );
                        $obj->tipo_reg = 'CM';

                        $obj->estado = 1;
                        $obj->persona_id_created_at=Auth::user()->id;
                        $obj->save();
                        // --

                        // Graba Alumnos Historial
                        if($con > 14)
                            DB::table('alumnos_historico')->insert([
                                'alumno_id' => $obj->id,
                                'campo_1' => trim( @$detfile[14] ),
                                'campo_2' => trim( @$detfile[15] ),
                                'campo_3' => trim( @$detfile[16] ),
                                'campo_4' => trim( @$detfile[17] ),
                                'campo_5' => trim( @$detfile[18] ),
                                'campo_6' => trim( @$detfile[19] ),
                                'campo_7' => trim( @$detfile[20] ),
                                'campo_8' => trim( @$detfile[21] ),
                                'campo_9' => trim( @$detfile[22] ),
                                'campo_10' => trim( @$detfile[23] ),
                                'campo_11' => trim( @$detfile[24] ),
                                'campo_12' => trim( @$detfile[25] ),
                                'campo_13' => trim( @$detfile[26] ),
                                'campo_14' => trim( @$detfile[27] ),
                                'campo_15' => trim( @$detfile[28] ),
                                'campo_16' => trim( @$detfile[29] ),
                                'campo_17' => trim( @$detfile[30] ),
                                'campo_18' => trim( @$detfile[31] ),
                                'campo_19' => trim( @$detfile[32] ),
                                'campo_20' => trim( @$detfile[33] ),
                                'estado' => 1,
                                'created_at' => date("Y-m-d H:i:s"),
                                'persona_id_created_at' => Auth::user()->id
                            ]);
                        // --
                    
                        // Graba Certificado
                        $obj_c = new Certificados;
                        $obj_c->alumno_id = $obj->id; // Id Alumnos
                        $obj_c->certificado_estado_id = 1;
                        $obj_c->fecha_estado_certificado = date("Y-m-d H:i:s");
                        $obj_c->sucursal_id = trim( $detfile[0] );
                        $obj_c->nota_final = trim( $detfile[7] );

                        $obj_c->estado = 1;
                        $obj_c->persona_id_created_at=Auth::user()->id;
                        $obj_c->save();
                        // --

                        // Graba Certificado Historico
                        DB::table('certificados_historico')->insert([
                            'certificado_id' => $obj_c->id,
                            'certificado_estado_id' => 1,
                            'estado' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'persona_id_created_at' => Auth::user()->id
                        ]);
                        // --
                        $r->id= array($obj_c->id);
                        Bandeja::runEditStatus($r);
                    }
                    else
                    {
                        $no_pasa = ($i+1);
                    }

                }
                DB::commit();
            }// for del file
            
            if(@$no_pasa > 0)
            {
                $return['no_pasa'] = $no_pasa;
                $return['rst'] = 3;
                $return['msj'] = 'Algunos datos no procesaron';
            }
            else
            {
                $return['rst'] = 1;
                $return['msj'] = 'Archivo procesado correctamente';
            }
            
            return response()->json($return);
        }
    }

    public function CargarMatriculas() 
    { 
        ini_set('memory_limit', '1024M');
        set_time_limit(600);
        if (isset($_FILES['carga_m']) and $_FILES['carga_m']['size'] > 0) {

            $uploadFolder = 'txt/matricula';

            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }

            $nombreArchivo = explode(".", $_FILES['carga_m']['name']);
            $tmpArchivo = $_FILES['carga_m']['tmp_name'];
            $archivoNuevo = $nombreArchivo[0] . "_u" . Auth::user()->id . "_" . date("Ymd_his") . "." . $nombreArchivo[1];
            $file = $uploadFolder . '/' . $archivoNuevo;

            //@unlink($file);

            $m = "Ocurrio un error al subir el archivo. No pudo guardarse.";
            if (!move_uploaded_file($tmpArchivo, $file)) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $array = array();
            $arrayExist = array();

            $file=file('txt/matricula/'.$archivoNuevo);
            
            $array_error = array();
            //$array_rollBack = array();

            for ($i = 0; $i < count($file); $i++) {

                DB::beginTransaction();
                if (trim($file[$i]) != '') {
                    $detfile = explode("\t", $file[$i]);

                    $con = 0;
                    for ($j = 0; $j < count($detfile); $j++) {
                        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "�", "\r", "\n\n", "\xEF", "\xBB", "\xBF", "ÿ", '�');
                        $reemplazar = array('','','','','','','','','','','');
                        $detfile[$j] = trim(str_replace($buscar, $reemplazar, $detfile[$j]) );
                        $array[$i][$j] = $detfile[$j];
                        $con++;
                    }
                   
                    // ODE
                        $sucursal = Sucursal::where('sucursal', 'like', '%'.$detfile[1].'%')
                                             ->first();
                        if (count($sucursal) == 0) 
                        {
                            $sucursal = new Sucursal;
                            $sucursal->sucursal = trim( $detfile[1] );
                            $sucursal->estado = 1;
                            $sucursal->persona_id_created_at = Auth::user()->id;
                            $sucursal->save();
                        }
                    // --
                    
                    // Responsable Matrícula
                        if($detfile[2])
                        {
                            $resmatri = explode(' ', $detfile[2]);
                            if($resmatri[1] == 'DR.' || $resmatri[1] == 'LIC') //DR. WILLIAN MOGROVEJO / LIC
                            {
                                $mat_person_nombre = $resmatri[1];
                                $mat_person_paterno = $resmatri[2];
                                $mat_person_materno = (@$resmatri[3]!='') ? $resmatri[3] : '';
                            }
                            else
                            {
                                $mat_person_nombre = $resmatri[0];
                                $mat_person_paterno = $resmatri[1];
                                $mat_person_materno = @$resmatri[2];
                            }
                        }

                        $responsable_matricula = Persona::where('nombre', 'like', '%'.$mat_person_nombre.'%')
                                                        ->where('paterno', 'like', '%'.$mat_person_paterno.'%')
                                                        ->first();
                        
                        if (count($responsable_matricula) == 0) 
                        {
                            $msg_error = trim($detfile[9]).': No se encontro responsable registrado: '.$mat_person_paterno.' '.$mat_person_materno.' '.$mat_person_nombre.'<br>'; 
                            array_push($array_error, $msg_error);
                            DB::rollBack();
                            continue;
                            /*$responsable_matricula = new Persona;
                            $responsable_matricula->paterno = trim( $mat_person_paterno );
                            $responsable_matricula->materno = trim( $mat_person_materno );
                            $responsable_matricula->nombre = trim( $mat_person_nombre );
                            $responsable_matricula->dni = '99999999';
                            $responsable_matricula->sexo = '';
                            $responsable_matricula->password = '';                
                            $responsable_matricula->estado = 1;
                            $responsable_matricula->persona_id_created_at = Auth::user()->id;
                            $responsable_matricula->save();*/
                        }
                    // --

                    // Trabajador
                        $trabajador = Trabajador::where('codigo','=',$detfile[3])
                                                 ->first();
                        if (count($trabajador) == 0) 
                        {
                            $trabajador = new Trabajador;
                            $trabajador->persona_id = 2;
                            $trabajador->rol_id = 1;
                            $trabajador->codigo = trim( $detfile[3] );
                            $trabajador->estado = 1;
                            $trabajador->persona_id_created_at = Auth::user()->id;
                            $trabajador->save();
                        }
                    // --

                    // Tipo de Participante
                        $tipo_participante = TipoParticipante::where('tipo_participante', 'like', '%'.trim($detfile[5]).'%')
                                                                ->first();
                        if (count($tipo_participante) == 0) 
                        {
                            $tipo_participante = new TipoParticipante;
                            $tipo_participante->tipo_participante = trim($detfile[5]);
                            $tipo_participante->estado = 1;
                            $tipo_participante->persona_id_created_at = Auth::user()->id;
                            $tipo_participante->save();
                        }
                    // --

                    // Persona
                        $persona = Persona::where('dni', '=', trim($detfile[6]))
                                            ->first();

                        if( count($persona) == 0 ){
                            $persona = Persona::where('paterno', '=', utf8_encode(trim($detfile[7])) )
                                            ->where('materno','=', utf8_encode(trim($detfile[8])) )
                                            ->where('nombre','=', utf8_encode(trim($detfile[9])) )
                                            ->first();
                        }
                        
                        if (count($persona) == 0) 
                        {
                            if( trim($detfile[12])!='' AND strlen(trim($detfile[12]))==10 AND count(explode("/",$detfile[12]))==3 )
                            {
                                $fecha_nacimiento = explode('/', trim(@$detfile[12]));
                                $fecha_naci = @$fecha_nacimiento[2].'-'.@$fecha_nacimiento[1].'-'.@$fecha_nacimiento[0];
                            }
                            elseif( trim($detfile[12])!='' AND strlen(trim($detfile[12]))==10 AND count(explode("-",$detfile[12]))==3){
                                $fecha_naci = trim(@$detfile[12]);
                            }
                            else
                                $fecha_naci = NULL;

                            if(strlen(trim($detfile[6]))!=10 AND strlen(trim($detfile[6]))!=8){
                                //$detfile[6]='99999999';
                                $msg_error = trim($detfile[9]).': DNI inválido: '.$detfile[6].'<br>'; 
                                array_push($array_error, $msg_error);
                                DB::rollBack();
                                continue;
                            }

                            $persona = new Persona;
                            $persona->dni = trim($detfile[6]);
                            $persona->paterno = utf8_encode(trim($detfile[7]));
                            $persona->materno = utf8_encode(trim($detfile[8]));
                            $persona->nombre = utf8_encode(trim($detfile[9]));
                            $persona->email = trim($detfile[10]);
                            $persona->celular = trim($detfile[11]);
                            $persona->fecha_nacimiento = $fecha_naci;
                            $persona->sexo= substr(trim($detfile[13]), 0,1);
                            $bcryptpassword = bcrypt(trim($detfile[6]));
                            $persona->password=$bcryptpassword;
                            $persona->estado = 1;
                            $persona->persona_id_created_at = Auth::user()->id;
                            $persona->save();
                        }
                    // --

                    // Alumno
                        $alumno = Alumno::where('persona_id', '=',$persona->id)
                                        ->first();
                        
                        if(count($alumno) == 0){
                            
                            $region = Region::where('region','=', utf8_encode(trim($detfile[15])))
                                            ->first();
                            
                            $provincia = Provincia::where('provincia','=',utf8_encode(trim($detfile[16])))
                                                ->first();
                            
                            $distrito = Distrito::where('distrito','=',utf8_encode(trim($detfile[17])))
                                                ->first();
                            
                            $alumno = new Alumno;
                            $alumno->persona_id = $persona->id;
                            $alumno->direccion =trim($detfile[14]);
                            $alumno->referencia = '';
                            if($region)
                               $alumno->region_id = $region->id; 

                            if($provincia)
                               $alumno->provincia_id = $provincia->id; 

                            if($distrito)
                               $alumno->distrito_id = $distrito->id; 

                            $alumno->estado = 1;
                            $alumno->persona_id_created_at = Auth::user()->id;
                            $alumno->save();
                        }
                    //--

                    // Matriculas
                        if($detfile[4])
                        {
                            $fecha_matri = explode('/', trim($detfile[4]));
                            if( count($fecha_matri)>1){
                                $fecha_matricula = $fecha_matri[2].'-'.$fecha_matri[1].'-'.$fecha_matri[0];
                            }
                            else{
                                $fecha_matricula=trim($detfile[4]);
                            }
                        }
                        else
                            $fecha_matricula = '';

                        $matricula = Matricula::where('persona_matricula_id', '=', $responsable_matricula->id)
                                                ->where('sucursal_id','=', $sucursal->id)
                                                ->where('fecha_matricula', '=', $fecha_matricula)
                                                ->where('alumno_id', '=', $alumno->id)
                                                ->first();

                        if (count($matricula) == 0) 
                        {
                            $month = date('m');
                            $year = date('Y');
                            //$fecha_matricula = date('Y-m-d', mktime(0,0,0, $month, 1, $year));

                            $matricula = new Matricula;
                            $matricula->tipo_participante_id = $tipo_participante->id;
                            $matricula->persona_id = $persona->id;
                            $matricula->alumno_id = $alumno->id;
                            $matricula->sucursal_id = $sucursal->id;
                            $matricula->sucursal_destino_id = $sucursal->id;
                            $matricula->persona_caja_id = 2;
                            $matricula->persona_matricula_id = $responsable_matricula->id;
                            $matricula->persona_marketing_id = $trabajador->persona_id;
                            $matricula->fecha_matricula = $fecha_matricula;
                            $matricula->tipo_matricula = 1;
                            $matricula->nro_pago = (@$resmatri[28]!='') ? trim($resmatri[28]) : '';
                            $matricula->monto_pago = (@$resmatri[30]!='') ? trim($resmatri[30]) : 0;
                            $matricula->persona_id_created_at = Auth::user()->id;
                            $matricula->save();
                        
                            
                            // Matricula Detalle
                            if(trim($detfile[19])!='')
                            {
                                $programaciones = DB::table('mat_programaciones AS p')
                                                        ->join('mat_cursos AS c',function($join){
                                                            $join->on('c.id','=','p.curso_id')
                                                                ->where(
                                                                    function($query){
                                                                        $query->where('p.estado','=',1);
                                                                    }
                                                                );
                                                        })
                                                        ->select('p.id')
                                                        ->where('p.sucursal_id','=', 1) 
                                                        ->where('c.curso', '=',trim($detfile[19]))
                                                        ->first();
                                if (count($programaciones) == 0)
                                {
                                    $msg_error = trim($detfile[9]).': No se encontro programacion con el curso: '.trim($detfile[19]).'<br>'; 
                                    array_push($array_error, $msg_error);
                                    DB::rollBack();
                                    continue;
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 1;
                                    $matriculadetalle->programacion_id = $programaciones->id;

                                    if((@$resmatri[32]*1)>0)
                                        $matriculadetalle->nro_pago = trim($resmatri[32]);
                                    $matriculadetalle->monto_pago = (@$resmatri[34]!='') ? trim($resmatri[34]) : 0;

                                    if((@$resmatri[40]*1)>0)
                                        $matriculadetalle->nro_pago_certificado = trim($resmatri[40]);
                                    $matriculadetalle->monto_pago_certificado = (@$resmatri[42]!='') ? trim($resmatri[42]) : 0;

                                    $matriculadetalle->tipo_matricula_detalle = 1;
                                    $matriculadetalle->estado = 1;
                                    $matriculadetalle->persona_id_created_at = Auth::user()->id;
                                    $matriculadetalle->save();
                                }
                            }

                            // Deta 2
                            if(trim($detfile[22])!='')
                            {
                                $programaciones2 = DB::table('mat_programaciones AS p')
                                                        ->join('mat_cursos AS c',function($join){
                                                            $join->on('c.id','=','p.curso_id')
                                                                ->where(
                                                                    function($query){
                                                                        $query->where('p.estado','=',1);
                                                                    }
                                                                );
                                                        })
                                                        ->select('p.id')
                                                        ->where('p.sucursal_id','=', 1) //$sucursal->id
                                                        ->where('c.curso','=', trim($detfile[22]))
                                                        ->first();
                                if (count($programaciones2) == 0)
                                {
                                    $msg_error = trim($detfile[9]).': No se encontro programacion con el curso: '.trim($detfile[22]).'<br>'; 
                                    array_push($array_error, $msg_error);
                                    DB::rollBack();
                                    continue;
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 2;
                                    $matriculadetalle->programacion_id = $programaciones2->id;
                                    if((@$resmatri[32]*1)>0)
                                        $matriculadetalle->nro_pago = trim($resmatri[32]);
                                    $matriculadetalle->monto_pago = (@$resmatri[34]!='') ? trim($resmatri[34]) : 0;

                                    if((@$resmatri[40]*1)>0)
                                        $matriculadetalle->nro_pago_certificado = trim($resmatri[40]);
                                    $matriculadetalle->monto_pago_certificado = (@$resmatri[42]!='') ? trim($resmatri[42]) : 0;
                                    $matriculadetalle->tipo_matricula_detalle = 1;
                                    $matriculadetalle->estado = 1;
                                    $matriculadetalle->persona_id_created_at = Auth::user()->id;
                                    $matriculadetalle->save();
                                }
                            }

                                
                            // Deta 3
                            if(trim($detfile[25])!='')
                            {
                                $programaciones3 = DB::table('mat_programaciones AS p')
                                                        ->join('mat_cursos AS c',function($join){
                                                            $join->on('c.id','=','p.curso_id')
                                                                ->where(
                                                                    function($query){
                                                                        $query->where('p.estado','=',1);
                                                                    }
                                                                );
                                                        })
                                                        ->select('p.id')
                                                        ->where('p.sucursal_id','=', 1) //$sucursal->id
                                                        ->where('c.curso', '=', trim($detfile[25]))
                                                        ->first();
                                if (count($programaciones3) == 0)
                                {
                                    $msg_error = trim($detfile[9]).': No se encontro programacion con el curso: '.trim($detfile[25]).'<br>';
                                    array_push($array_error, $msg_error);
                                    DB::rollBack();
                                    continue;
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 3;
                                    $matriculadetalle->programacion_id = $programaciones3->id;
                                    if((@$resmatri[32]*1)>0)
                                        $matriculadetalle->nro_pago = trim($resmatri[32]);
                                    $matriculadetalle->monto_pago = (@$resmatri[34]!='') ? trim($resmatri[34]) : 0;

                                    if((@$resmatri[40]*1)>0)
                                        $matriculadetalle->nro_pago_certificado = trim($resmatri[40]);
                                    $matriculadetalle->monto_pago_certificado = (@$resmatri[42]!='') ? trim($resmatri[42]) : 0;
                                    $matriculadetalle->tipo_matricula_detalle = 1;
                                    $matriculadetalle->estado = 1;
                                    $matriculadetalle->persona_id_created_at = Auth::user()->id;
                                    $matriculadetalle->save();
                                }
                            }
                            // --

                        }
                        else
                        {
                            $no_pasa = ($i+1);
                        }
                    // --                    

                }
                DB::commit();
            }// for del file
            
            if(count($array_error) > 0)
            {
                $return['error_carga'] = $array_error;
                $return['rst'] = 4;
                $return['msj'] = 'Existieron algunos errores';
            }
            else
            {
                if(@$no_pasa > 0)
                {
                    $return['no_pasa'] = $no_pasa;
                    $return['rst'] = 3;
                    $return['msj'] = 'Algunos datos no procesaron';
                }
                else
                {
                    $return['rst'] = 1;
                    $return['msj'] = 'Archivo procesado correctamente';
                }
            }

            return response()->json($return);
        }
    }
    
    public function CargaProgramacion() 
    {
        ini_set('memory_limit', '512M');
        if (isset($_FILES['carga']) and $_FILES['carga']['size'] > 0) {

            $uploadFolder = 'txt/matricula';

            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder);
            }

            $nombreArchivo = explode(".", $_FILES['carga']['name']);
            $tmpArchivo = $_FILES['carga']['tmp_name'];
            $archivoNuevo = $nombreArchivo[0] . "_u" . Auth::user()->id . "_" . date("Ymd_his") . "." . $nombreArchivo[1];
            $file = $uploadFolder . '/' . $archivoNuevo;

            //@unlink($file);

            $m = "Ocurrio un error al subir el archivo. No pudo guardarse.";
            if (!move_uploaded_file($tmpArchivo, $file)) {
                $return['rst'] = 2;
                $return['msj'] = $m;
                return response()->json($return);
            }

            $array = array();
            $arrayExist = array();

            $file=file('txt/matricula/'.$archivoNuevo);
            
            for ($i = 0; $i < count($file); $i++) {

                DB::beginTransaction();
                if (trim($file[$i]) != '') {
                    $detfile = explode("\t", $file[$i]);

                    $con = 0;
                    for ($j = 0; $j < count($detfile); $j++) {
                        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "�", "\r", "\n\n", "\xEF", "\xBB", "\xBF");
                        $reemplazar = "";
                        $detfile[$j] = trim(str_replace($buscar, $reemplazar, $detfile[$j]));
                        $array[$i][$j] = $detfile[$j];
                        $con++;
                    }
                    
                    $especialidad= Especialidad::where('especialidad','=',trim($detfile[0]))
                                         ->first();
                        if (count($especialidad) == 0) 
                        {
                            $especialidad=new Especialidad;
                            $especialidad->especialidad=trim($detfile[0]);
                            $especialidad->certificado_especialidad='';
                            $especialidad->persona_id_created_at=Auth::user()->id;
                            $especialidad->save();
                        }
                    
                    $curso= Curso::where('curso','=',trim($detfile[1]))
                                         ->first();
                        if (count($curso) == 0){
                            $curso=new Curso;
                            $curso->curso=trim($detfile[1]);
                            $curso->certificado_curso='';
                            $curso->tipo_curso=1;
                            $curso->persona_id_created_at=Auth::user()->id;
                            $curso->save();
                        }
                    $curso_especialidad= CursoEspecialidad::where('curso_id','=',$curso->id)
                                         ->where('especialidad_id','=',$especialidad->id)
                                         ->first();
                    
                        if (count($curso_especialidad) == 0){
                            $curso_especialidad=new CursoEspecialidad;
                            $curso_especialidad->curso_id=$curso->id;
                            $curso_especialidad->especialidad_id=$especialidad->id;
                            $curso_especialidad->persona_id_created_at=Auth::user()->id;
                            $curso_especialidad->save();
                        }
                        
                    $fecha_inicio=explode('/',trim($detfile[3]));
                    $fecha_final=explode('/',trim($detfile[4]));
                    
                    $programaciones = Programacion::where('docente_id', '=', 1)
                                                    ->where('sucursal_id','=',1)
                                                    ->where('curso_id','=',$curso->id)
                                                    ->where('fecha_inicio','=',$fecha_inicio[2].'-'.$fecha_inicio[1].'-'.$fecha_inicio[0].' '.trim($detfile[6]).':00')
                                                    ->where('fecha_final','=',$fecha_final[2].'-'.$fecha_final[1].'-'.$fecha_final[0].' '.trim($detfile[7]).':00')
                                                    ->where('dia','=',substr(trim($detfile[5]), 0, 2))
                                                    ->first();

                        if (count($programaciones)== 0) 
                        {
                            $programacion=new Programacion;
                            $programacion->persona_id=2;
                            $programacion->docente_id=20;
                            $programacion->curso_id=$curso->id;
                            $programacion->sucursal_id=1;
                            $programacion->aula='-';
                            $programacion->dia=substr(trim($detfile[5]), 0, 2);
                            $programacion->fecha_inicio=$fecha_inicio[2].'-'.$fecha_inicio[1].'-'.$fecha_inicio[0].' '.trim($detfile[6]).':00';
                            $programacion->fecha_final=$fecha_final[2].'-'.$fecha_final[1].'-'.$fecha_final[0].' '.trim($detfile[7]).':00';
                            $programacion->persona_id_created_at=Auth::user()->id;
                            $programacion->save();
                        }
                        else
                        {
                            $no_pasa = ($i+1);
                        }

                }
                DB::commit();
            }// for del file
            
            if(@$no_pasa > 0)
            {
                $return['no_pasa'] = $no_pasa;
                $return['rst'] = 3;
                $return['msj'] = 'Algunos datos no procesaron';
            }
            else
            {
                $return['rst'] = 1;
                $return['msj'] = 'Archivo procesado correctamente';
            }
            
            return response()->json($return);
        }
    }

    public function CargarSeminarios() 
    {
        ini_set('memory_limit', '512M');

            $uploadFolder = 'txt/alumnos';

            $array = array();
            $arrayExist = array();

            $file=file('txt/alumnos/'.$archivoNuevo);
            
            for ($i = 0; $i < count($file); $i++) {

                DB::beginTransaction();
                if (trim($file[$i]) != '') {
                    $detfile = explode("\t", $file[$i]);

                    $con = 0;
                    for ($j = 0; $j < count($detfile); $j++) {
                        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "�", "\r", "\n\n", "\xEF", "\xBB", "\xBF");
                        $reemplazar = "";
                        $detfile[$j] = trim(str_replace($buscar, $reemplazar, $detfile[$j]));
                        $array[$i][$j] = $detfile[$j];
                        $con++;
                    }

                    $alumnos = Alumnos::where('sucursal_id', '=', trim($detfile[0]))
                                        ->where('id_envio', '=', trim($detfile[1]))
                                        ->where('nombre', '=', trim($detfile[2]))
                                        ->where('paterno', '=', trim($detfile[3]))
                                        ->where('materno', '=', trim($detfile[4]))
                                        ->where('dni', '=', trim($detfile[5]))
                                        ->where('certificado', '=', trim($detfile[6]))
                                        ->where('nota_certificado', '=', trim($detfile[7]))
                                        ->where('tipo_certificado', '=', trim($detfile[8]))
                                        ->first();

                    if (count($alumnos) == 0) 
                    {
                        // Graba Alumnos
                        $obj = new Alumnos;
                        $obj->sucursal_id = trim( $detfile[0] );
                        $obj->id_envio = trim( $detfile[1] );
                        $obj->nombre = trim( $detfile[2] );
                        $obj->paterno = trim( $detfile[3] );
                        $obj->materno = trim( $detfile[4] );
                        $obj->dni = trim( $detfile[5] );
                        $obj->certificado = trim( $detfile[6] );
                        $obj->nota_certificado = trim( $detfile[7] );
                        $obj->tipo_certificado = trim( $detfile[8] );
                        $obj->direccion = trim( $detfile[9] );
                        $obj->referencia = trim( $detfile[10] );
                        $obj->region = trim( $detfile[11] );
                        $obj->provincia = trim( $detfile[12] );
                        $obj->distrito = trim( $detfile[13] );
                        $obj->tipo_reg = 'CM';

                        $obj->estado = 1;
                        $obj->persona_id_created_at=Auth::user()->id;
                        $obj->save();
                        // --

                        // Graba Alumnos Historial
                        if($con > 14)
                            DB::table('alumnos_historico')->insert([
                                'alumno_id' => $obj->id,
                                'campo_1' => trim( @$detfile[14] ),
                                'campo_2' => trim( @$detfile[15] ),
                                'campo_3' => trim( @$detfile[16] ),
                                'campo_4' => trim( @$detfile[17] ),
                                'campo_5' => trim( @$detfile[18] ),
                                'campo_6' => trim( @$detfile[19] ),
                                'campo_7' => trim( @$detfile[20] ),
                                'campo_8' => trim( @$detfile[21] ),
                                'campo_9' => trim( @$detfile[22] ),
                                'campo_10' => trim( @$detfile[23] ),
                                'campo_11' => trim( @$detfile[24] ),
                                'campo_12' => trim( @$detfile[25] ),
                                'campo_13' => trim( @$detfile[26] ),
                                'campo_14' => trim( @$detfile[27] ),
                                'campo_15' => trim( @$detfile[28] ),
                                'campo_16' => trim( @$detfile[29] ),
                                'campo_17' => trim( @$detfile[30] ),
                                'campo_18' => trim( @$detfile[31] ),
                                'campo_19' => trim( @$detfile[32] ),
                                'campo_20' => trim( @$detfile[33] ),
                                'estado' => 1,
                                'created_at' => date("Y-m-d H:i:s"),
                                'persona_id_created_at' => Auth::user()->id
                            ]);
                        // --
                    
                        // Graba Certificado
                        $obj_c = new Certificados;
                        $obj_c->alumno_id = $obj->id; // Id Alumnos
                        $obj_c->certificado_estado_id = 1;
                        $obj_c->fecha_estado_certificado = date("Y-m-d H:i:s");
                        $obj_c->sucursal_id = trim( $detfile[0] );
                        $obj_c->nota_final = trim( $detfile[7] );

                        $obj_c->estado = 1;
                        $obj_c->persona_id_created_at=Auth::user()->id;
                        $obj_c->save();
                        // --

                        // Graba Certificado Historico
                        DB::table('certificados_historico')->insert([
                            'certificado_id' => $obj_c->id,
                            'certificado_estado_id' => 1,
                            'estado' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'persona_id_created_at' => Auth::user()->id
                        ]);
                        // --
                    }
                    else
                    {
                        $no_pasa = ($i+1);
                    }

                }
                DB::commit();
            }// for del file
            
            if(@$no_pasa > 0)
            {
                $return['no_pasa'] = $no_pasa;
                $return['rst'] = 3;
                $return['msj'] = 'Algunos datos no procesaron';
            }
            else
            {
                $return['rst'] = 1;
                $return['msj'] = 'Archivo procesado correctamente';
            }
            
            return response()->json($return);
        
    }
}
