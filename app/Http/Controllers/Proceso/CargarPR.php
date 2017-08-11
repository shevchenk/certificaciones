<?php

class CargarController extends BaseController {

    public function postCargarmatriculas() 
    {
        ini_set('memory_limit', '512M');
        if (isset($_FILES['carga']) and $_FILES['carga']['size'] > 0) {

            $uploadFolder = 'txt/matriculas';

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
                return Response::json(
                                array(
                                    'upload' => FALSE,
                                    'rst' => '2',
                                    'msj' => $m,
                                    'error' => $_FILES['archivo'],
                                )
                );
            }

            $array = array();
            $arrayExist = array();

            $file=file('txt/matriculas/'.$archivoNuevo);

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

                        $obj = new Matriculas();
                        $obj->contabilidad_gastos_id = $conta_gastos->id;
                        $obj->tipo_expede = $detfile[1];

                        if ($detfile[0] != '')
                            $obj->alumno_id = $detfile[0];

                        if ($detfile[1] != '')
                            $obj->descripcion = $detfile[1];                        

                        $obj->estado = 1;
                        $obj->usuario_created_at = Auth::user()->id;
                        $obj->save();
                    
                }
                DB::commit();
            }// for del file
            //exit;
            return Response::json(
                            array(
                                'rst' => '1',
                                'msj' => 'Archivo procesado correctamente',
                                'file' => $archivoNuevo,
                                'upload' => TRUE,
                                //'data'      => $array,
                                'data' => array(),
                                'existe' => 0//$arrayExist
                            )
            );
        }
    }
    
                    

    

}
