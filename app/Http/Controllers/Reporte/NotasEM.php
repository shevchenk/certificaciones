<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reporte\Notas
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }   
    
    public function LoadNOTAS(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Notas::runLoadNOTAS($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
}
