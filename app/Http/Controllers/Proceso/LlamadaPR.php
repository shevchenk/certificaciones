<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Llamada;
use App\Models\Mantenimiento\Trabajador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

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

    public function RegistrarLlamadaDistribuida (Request $r )
    {
        if ( $r->ajax() ) {
            $id=Auth::user()->id;
            $trabajador=    Trabajador::where('persona_id',$id)
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where('rol_id',1)
                            ->first();
            $trabajadorid=0;
            $return['rst'] = 1;
            $return['msj'] = "Registro realizado correctamente";
            if( isset($trabajador->id) ){
                $trabajadorid=$trabajador->id;
                $r['teleoperadora']=$trabajadorid;
                $renturnModel = Llamada::RegistrarLlamada($r);
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = "Ud, no esta registrado como Trabajador - Marketing";
            }
            return response()->json($return);
        }
    }

    public function CargarLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Llamada::CargarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarInfo(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Llamada::CargarInfo($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarLlamadaPendiente(Request $r )
    {
        if ( $r->ajax() ) {
            $id=Auth::user()->id;
            $trabajador=    Trabajador::where('persona_id',$id)
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->first();
            $trabajadorid=0;
            if( isset($trabajador->id) ){
                $trabajadorid=$trabajador->id;
            }
            $r['teleoperadora']=$trabajadorid;
            $r['pendiente']=1;
            $renturnModel = Llamada::CargarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function GuardarAsignacion(Request $r)
    {
        if ( $r->ajax() ) {
            $renturnModel = Llamada::GuardarAsignacion($r);
            $return['rst'] = 1;
            //$return['data'] = $renturnModel;
            $return['msj'] = "Asignación Finalizada";
            return response()->json($return);
        }
    }

}
