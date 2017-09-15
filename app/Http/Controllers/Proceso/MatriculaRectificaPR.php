<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\MatriculaRectifica;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MatriculaRectificaPR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            MatriculaRectifica::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = MatriculaRectifica::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function verMatriculas(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = MatriculaRectifica::verMatriculas($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function verDetaMatriculas(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = MatriculaRectifica::verDetaMatriculas($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function UpdateMatriDeta(Request $r )
    {
        if ( $r->ajax() ) {
            MatriculaRectifica::UpdateMatriDeta($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }
    
    public function UpdatePagosDM(Request $r )
    {
        if ( $r->ajax() ) {
            MatriculaRectifica::UpdatePagosDM($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }
    /*
    public function ListEspecialidadDisponible (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Especialidad::ListEspecialidadDisponible($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    */

}
