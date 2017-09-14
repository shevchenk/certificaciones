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

class CargarPR extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function CargarAlumnos() 
    {
        ini_set('memory_limit', '512M');
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


    public function CargarMatriculas() 
    { 
        ini_set('memory_limit', '512M');
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
                                $mat_person_materno = ($resmatri[3]!='') ? $resmatri[3] : '';
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
                            $responsable_matricula = new Persona;
                            $responsable_matricula->paterno = trim( $mat_person_paterno );
                            $responsable_matricula->materno = trim( $mat_person_materno );
                            $responsable_matricula->nombre = trim( $mat_person_nombre );
                            $responsable_matricula->dni = '99999999';
                            $responsable_matricula->sexo = '';
                            $responsable_matricula->password = '';                
                            $responsable_matricula->estado = 1;
                            $responsable_matricula->persona_id_created_at = Auth::user()->id;
                            $responsable_matricula->save();
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
                        
                        if (count($persona) == 0) 
                        {
                            if($detfile[12])
                            {
                                $fecha_nacimiento = explode('/', trim(@$detfile[12]));
                                $fecha_naci = @$fecha_nacimiento[2].'-'.@$fecha_nacimiento[1].'-'.@$fecha_nacimiento[0];
                            }
                            else
                                $fecha_naci = NULL;

                            $persona = new Persona;
                            $persona->dni = trim($detfile[6]);
                            $persona->paterno = trim($detfile[7]);
                            $persona->materno = trim($detfile[8]);
                            $persona->nombre = trim($detfile[9]);
                            $persona->email = trim($detfile[10]);
                            $persona->celular = trim($detfile[11]);
                            $persona->fecha_nacimiento = $fecha_naci;
                            $persona->sexo= substr(trim($detfile[13]), 0,1);
                            $persona->password = '';                
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
                            $fecha_matricula = $fecha_matri[2].'-'.$fecha_matri[1].'-'.$fecha_matri[0];
                        }
                        else
                            $fecha_matricula = '';

                        $matricula = Matricula::where('persona_matricula_id', '=', $persona->id)
                                                ->where('sucursal_id','=', $sucursal->id)
                                                ->where('persona_marketing_id','=', $trabajador->persona_id)
                                                ->where('fecha_matricula', '=', $fecha_matricula)
                                                ->first();

                        if (count($matricula) == 0) 
                        {
                            $month = date('m');
                            $year = date('Y');
                            $fecha_matricula = date('Y-m-d', mktime(0,0,0, $month, 1, $year));

                            $matricula = new Matricula;
                            $matricula->tipo_participante_id = $tipo_participante->id;
                            $matricula->persona_id = $persona->id;
                            $matricula->alumno_id = $alumno->id;
                            $matricula->sucursal_id = $sucursal->id;
                            $matricula->persona_matricula_id = $responsable_matricula->id;
                            $matricula->persona_marketing_id = $trabajador->persona_id;
                            $matricula->fecha_matricula = $fecha_matricula;
                            $matricula->tipo_matricula = 1;
                            $matricula->nro_pago = trim($detfile[28]);
                            $matricula->monto_pago = trim($detfile[30]);
                            $matricula->persona_id_created_at = Auth::user()->id;
                            $matricula->save();
                        
                            
                            // Matricula Detalle
                            $msg_error = '';

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
                                                        ->where('c.curso', '=',trim($detfile[19])) // Columna T
                                                        ->first();
                                if (count($programaciones) == 0)
                                {
                                    $msg_error .= trim($detfile[19]).'<br>'; 
                                    DB::rollBack();
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 1;
                                    $matriculadetalle->programacion_id = $programaciones->id;
                                    $matriculadetalle->nro_pago = trim(@$detfile[32]);
                                    $matriculadetalle->monto_pago = trim(@$detfile[34]);
                                    $matriculadetalle->nro_pago_certificado = trim(@$detfile[40]);
                                    $matriculadetalle->monto_pago_certificado = trim(@$detfile[42]);
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
                                                        ->where('c.curso','like', '%'.trim($detfile[22]).'%') // Columna T
                                                        ->first();
                                if (count($programaciones) == 0)
                                {
                                    DB::rollBack();
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 2;
                                    $matriculadetalle->programacion_id = $programaciones2->id;
                                    //$matriculadetalle->nro_pago = ;
                                    //$matriculadetalle->monto_pago = ;
                                    //$matriculadetalle->nro_pago_certificado = ;
                                    //$matriculadetalle->monto_pago_certificado = ;
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
                                                        ->where('c.curso', 'like', '%'.trim($detfile[25]).'%') // Columna T
                                                        ->first();
                                if (count($programaciones) == 0)
                                {
                                    DB::rollBack();
                                }
                                else
                                {
                                    $matriculadetalle = new MatriculaDetalle;
                                    $matriculadetalle->matricula_id = $matricula->id;
                                    $matriculadetalle->norden = 3;
                                    $matriculadetalle->programacion_id = $programaciones3->id;
                                    //$matriculadetalle->nro_pago = ;
                                    //$matriculadetalle->monto_pago = ;
                                    //$matriculadetalle->nro_pago_certificado = ;
                                    //$matriculadetalle->monto_pago_certificado = ;
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
                            $programacion->persona_id=1;
                            $programacion->docente_id=1;
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
}
