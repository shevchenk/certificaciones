<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Region;
use App\Models\Mantenimiento\Provincia;
use App\Models\Mantenimiento\Distrito;

class RegionEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
            public function ListRegion (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Region::ListRegion($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
                public function ListProvincia (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Provincia::ListProvincia($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
                    public function ListDistrito (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Distrito::ListDistrito($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
