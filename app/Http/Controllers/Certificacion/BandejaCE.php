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

}
