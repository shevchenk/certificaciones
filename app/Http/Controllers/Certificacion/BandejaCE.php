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

        public function LoadVerPagoAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=6;
            $renturnModel = Bandeja::runLoadteleoperada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
       public function LoadVerEntregaValidaAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=10;
            $renturnModel = Bandeja::runLoadteleoperada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function LoadAprobado(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=1;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function LoadEnemision(Request $r )
    {
        if ( $r->ajax() ) {
            $r->certificado_estado_id=2;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 2;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadEmitido(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=3;
            $renturnModel = Bandeja::runLoad($r);
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
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    // RAG
    public function LoadRecibeOde(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=5;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadPagoAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=6;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function LoadValidaPagoAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=7;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadEntregaCourierAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=8;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadEntregaAlumnoODE(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=9;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadEntregaValidaAlumno(Request $r )
    {
        if ( $r->ajax() ) {

            $r->certificado_estado_id=10;
            $renturnModel = Bandeja::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Bandeja::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Entregado y Validado por Alumno correctamente';
            return response()->json($return);
        }
    }
}
