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

use App\Models\Mantenimiento\Sucursal;
use App\Models\Mantenimiento\Persona;
use App\Models\Mantenimiento\Trabajador;
use App\Models\Mantenimiento\Matricula;
use App\Models\Proceso\Alumno;

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


                    // Sucursal
                        $sucursal = Sucursal::where('sucursal', 'like', '%'.$detfile[0].'%')
                                             ->first();
                        if (count($sucursal) == 0) 
                        {
                            $sucursal = new Sucursal;
                            $sucursal->id_externo = 1;
                            $sucursal->sucursal = trim( $detfile[0] );
                            $sucursal->estado = 1;
                            $sucursal->persona_id_created_at = Auth::user()->id;
                            $sucursal->save();
                        }
                    // --

                    // Persona
                        if($detfile[1])
                        {
                            $resmatri = explode(' ', $detfile[1]);
                            if($resmatri[0] == 'DR.' || $resmatri[0] == 'LIC') //DR. WILLIAN MOGROVEJO / LIC
                            {
                                $mat_person_nombre = $resmatri[1];
                                $mat_person_paterno = $resmatri[2];
                                $mat_person_materno = ($resmatri[3]!='')?$resmatri[3]:'';
                            }
                            else
                            {
                                $mat_person_nombre = $resmatri[0];
                                $mat_person_paterno = $resmatri[1];
                                $mat_person_materno = @$resmatri[2];
                            }
                        }

                        $persona = Persona::where('nombre', 'like', '%'.$mat_person_nombre.'%')
                                            ->where('paterno', 'like', '%'.$mat_person_paterno.'%')
                                            ->first();
                        if (count($persona) == 0) 
                        {
                            $persona = new Persona;
                            $persona->paterno = trim( $mat_person_paterno );
                            $persona->materno = trim( $mat_person_materno );
                            $persona->nombre = trim( $mat_person_nombre );
                            $persona->dni = '99999999';
                            $persona->sexo = '-';
                            $persona->password = '-';                
                            $persona->estado = 1;
                            $persona->persona_id_created_at = Auth::user()->id;
                            $persona->save();
                        }
                    // --

                    // Trabajador
                        $trabajador = Trabajador::where('codigo', 'like', '%'.$detfile[2].'%')
                                                 ->first();
                        if (count($trabajador) == 0) 
                        {
                            $trabajador = new Trabajador;
                            $trabajador->persona_id = 2;
                            $trabajador->rol_id = 1;
                            $trabajador->codigo = trim( $detfile[2] );
                            $trabajador->estado = 1;
                            $trabajador->persona_id_created_at = Auth::user()->id;
                            $trabajador->save();
                        }
                    // --

                    // Alumnos
                        $alumno = Alumno::where('dni', '=', $detfile[5])
                                        ->where('paterno', '=', $detfile[6])
                                        ->where('materno', '=', $detfile[7])
                                        ->where('nombre', '=', $detfile[8])
                                        ->first();
                        if (count($alumno) == 0) 
                        {
                            $alumno = new Alumno;
                            $alumno->persona_id = '';
                            
                            $alumno->estado = 1;
                            $alumno->persona_id_created_at = Auth::user()->id;
                            $alumno->save();
                        }
                    // --

                    // Proceso Matriculas
                        $fecha_matri = explode('/', trim($detfile[3]));
                        $matricula = Matricula::where('persona_matricula_id', '=', $persona->id)
                                                ->where('sucursal_id','=', $sucursal->id)
                                                ->where('persona_marketing_id','=', $trabajador->persona_id)
                                                ->where('fecha_matricula', '=', $fecha_matri[2].'-'.$fecha_matri[1].'-'.$fecha_matri[0])
                                                ->first();

                        if (count($matricula) == 0) 
                        {
                            $matricula = new Matricula;
                            $matricula->tipo_participante_id = 2;
                            $matricula->persona_id = '';
                            $matricula->alumno_id = '';
                            $matricula->sucursal_id = trim( $r->sucursal_id);
                            
                            $matricula->persona_matricula_id = trim( $persona->id );
                            $matricula->persona_marketing_id = trim( $trabajador->persona_id );
                            
                            $matricula->fecha_matricula = $fecha_matri[2].'-'.$fecha_matri[1].'-'.$fecha_matri[0];
                            $matricula->tipo_matricula = 1;
                        
                            $matricula->persona_id_created_at = Auth::user()->id;
                            $matricula->save();
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
