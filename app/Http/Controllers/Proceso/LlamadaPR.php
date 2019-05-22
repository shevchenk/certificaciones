<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Llamada;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LlamadaPR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }
    
    public function RegistrarLlamada (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Llamada::RegistrarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "Registro realizado correctamente";
            return response()->json($return);
        }
    }

}
