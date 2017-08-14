<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Curso;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CursoEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
            public function ListCurso (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Curso::ListCurso($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
