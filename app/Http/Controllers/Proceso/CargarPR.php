<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumnos;
use App\Models\Proceso\Certificados;

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

                    for ($j = 0; $j < count($detfile); $j++) {
                        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "�", "\r", "\n\n", "\xEF", "\xBB", "\xBF");
                        $reemplazar = "";
                        $detfile[$j] = trim(str_replace($buscar, $reemplazar, $detfile[$j]));
                        $array[$i][$j] = $detfile[$j];
                    }

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

                    $obj->estado = 1;
                    $obj->persona_id_created_at=Auth::user()->id;
                    $obj->save();
                    // --

                    // Graba Alumnos Historial
                    DB::table('alumnos_historico')->insert([
                        'campo_1' => trim( $detfile[0] ),
                        'campo_2' => trim( $detfile[1] ),
                        'campo_3' => trim( $detfile[2] ),
                        'campo_4' => trim( $detfile[3] ),
                        'campo_5' => trim( $detfile[4] ),
                        'campo_6' => trim( $detfile[5] ),
                        'campo_7' => trim( $detfile[6] ),
                        'campo_8' => trim( $detfile[7] ),
                        'campo_9' => trim( $detfile[8] ),
                        'campo_10' => trim( $detfile[9] ),
                        'campo_11' => trim( $detfile[10] ),
                        'campo_12' => trim( $detfile[11] ),
                        'campo_13' => trim( $detfile[12] ),
                        'campo_14' => trim( $detfile[13] ),
                        'campo_15' => trim( @$detfile[14] ),
                        'campo_16' => trim( @$detfile[15] ),
                        'campo_17' => trim( @$detfile[16] ),
                        'campo_18' => trim( @$detfile[17] ),
                        'campo_19' => trim( @$detfile[18] ),
                        'campo_20' => trim( @$detfile[19] ),
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
                DB::commit();
            }// for del file
            

            $return['rst'] = 1;
            $return['msj'] = 'Archivo procesado correctamente';
            return response()->json($return);
        }
    }
    
                    

    

}
