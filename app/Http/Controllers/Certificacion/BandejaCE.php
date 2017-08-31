<?php
namespace App\Http\Controllers\Certificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificacion\Bandeja;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BandejaCE extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function LoadAprobado(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=1;
            $renturnModel = Bandeja::runLoadAprobado($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
        public function LoadDistribucion(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=4;
            $renturnModel = Bandeja::runLoadAprobado($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
        public function EditStatusDistribucion(Request $r )
    {
        if ( $r->ajax() ) {
            Bandeja::runEditStatusDistribucion($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function LoadEmitido(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=3;
            $renturnModel = Bandeja::runLoadEmitido($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function EditStatusa4(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=4;
            Bandeja::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Enviado a Distribución correctamente';
            return response()->json($return);
        }
    }

    public function EditStatusa6(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=6;
            Bandeja::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Enviado a Pago del Alumno correctamente';
            return response()->json($return);
        }
    }

}
