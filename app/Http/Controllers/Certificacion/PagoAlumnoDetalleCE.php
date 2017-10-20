<?php
namespace App\Http\Controllers\Certificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificacion\PagoAlumnoDetalle;

class PagoAlumnoDetalleCE extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            
            
            $renturnModel = PagoAlumnoDetalle::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    

}
