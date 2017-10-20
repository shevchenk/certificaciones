<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Contesta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Excel;

class ContestaEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function ListContesta (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Contesta::ListContesta($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
