<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\MatriculaDetalle;

class MPPR extends Controller
{
    //use WithoutMiddleware;

    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function CargarMP(Request $r)
    {
        if ( $r->ajax() ) {
            $renturnModel = MatriculaDetalle::CargarMP($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarProgramacionMP(Request $r)
    {
        if ( $r->ajax() ) {
            $renturnModel = MatriculaDetalle::CargarProgramacionMP($r);
            $return['rst'] = 1;
            $return['pos'] = trim( $r->pos );
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ActualizarMP(Request $r)
    {
        if ( $r->ajax() ) {
            MatriculaDetalle::ActualizarMP($r);
            $return['rst'] = 1;
            $return['msj'] = "Se actualizarón las programaciones seleccionadas";
            return response()->json($return);
        }
    }

}
