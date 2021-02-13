<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Mantenimiento\Menu;
use App\Models\Reporte\Seminario;
use App\Models\Proceso\MatriculaDetalle;

class CargarCertificadoPR extends Controller
{
    //use WithoutMiddleware;

    public function __construct()
    {
        //$this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function GuardarArchivo(Request $r)
    {
        $return = array();
        if( $r->has('nombre') AND trim($r->nombre) != '' AND $r->has('archivo') AND trim($r->archivo) != '' AND $r->has('matricula_detalle_id') ){
            //$valida = Seminario::validarDeuda($r);
            $extension='';
            $type=explode(".",$r->nombre);
            $extension=".".end($type);
            
            DB::beginTransaction();
            $matricula_detalle = MatriculaDetalle::find($r->matricula_detalle_id);
            $url = "upload/certificado/C".$matricula_detalle->id.$extension;
            $matricula_detalle->archivo_certificado = $url;
            $matricula_detalle->save();

            @unlink($url);
            Menu::fileToFile($r->archivo, $url);
            DB::commit();
            $return['rst'] = 1;
            $return['msj'] = 'Archivo procesado correctamente';
        }
        else{
            $return['rst'] = 3;
            $return['msj'] = 'Faltan datos!!';
        }

        return response()->json($return);
    }


}
