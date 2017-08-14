<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Docente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocenteEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
            public function ListDocente (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Docente::ListDocente($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
