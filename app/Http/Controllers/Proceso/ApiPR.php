<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Api;
use Illuminate\Support\Facades\Validator;
use Auth;

class ApiPR extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function index(Request $r)
    {
        $result=array();
        if($r->opcion=='ObtenerPersona'){
            $result = $this->ObtenerPersona($r);
        }
        elseif($r->opcion=='ObtenerCursos'){
            $result = $this->ObtenerCursos($r);
        }
        elseif($r->opcion=='ObtenerTiposEvaluaciones'){
            $result = $this->ObtenerTiposEvaluaciones($r);
        }
        elseif($r->opcion=='ObtenerTiposEvaluacionesTotales'){
            $result = $this->ObtenerTiposEvaluacionesTotales($r);
        }
        elseif($r->opcion=='ObtenerCursosDocente'){
            $result = $this->ObtenerCursosDocente($r);
        }
        elseif($r->opcion=='ObtenerCursosGlobal'){
            $result = $this->ObtenerCursosGlobal($r);
        }
        elseif($r->opcion=='ObtenerProgramacionesGlobal'){
            $result = $this->ObtenerProgramacionesGlobal($r);
        }
        elseif($r->opcion=='ObtenerCursosProgramados'){
            $result = $this->ObtenerCursosProgramados($r);
        }
        elseif($r->opcion=='ObtenerEspecialidadesProgramados'){
            $result = $this->ObtenerEspecialidadesProgramados($r);
        }
        elseif($r->opcion=='RegistrarInscripciones'){
            $result = $this->RegistrarInscripciones($r);
        }
        elseif($r->opcion=='RegistrarInteresado'){
            $result = $this->RegistrarInteresado($r);
        }
        elseif($r->opcion=='ValidarInteresado'){
            $result = $this->ValidarInteresado($r);
        }
        return $result;
    }

    public function Show($opcion)
    {
        $result=array();
        if($opcion=='ObtenerKey'){
            $result = $this->ObtenerKey();
        }
        return $result;
    }

    public function Store()
    {
        //return 'hgola';
    }

    public function response($code=200, $status="", $message="")
    {
        http_response_code($code);
        if( !empty($status) && !empty($message) )
        {
            $response = array(
                        "status" => $status ,
                        "message"=>$message,
                        "server" => $_SERVER['REMOTE_ADDR']
                    );  
            echo json_encode($response, JSON_PRETTY_PRINT);
        }            
    }
    
    public function ObtenerKey ()
    {
        $renturnModel = Api::ObtenerKey();
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerPersona ( $r )
    {
        $renturnModel = Api::ObtenerPersona($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerCursos ( $r )
    {
        $renturnModel = Api::ObtenerCursos($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerTiposEvaluaciones ( $r )
    {
        $renturnModel = Api::ObtenerTiposEvaluaciones($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerTiposEvaluacionesTotales ( $r )
    {
        $renturnModel = Api::ObtenerTiposEvaluacionesTotales($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerCursosDocente ( $r )
    {
        $renturnModel = Api::ObtenerCursosDocente($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerCursosGlobal ( $r )
    {
        $renturnModel = Api::ObtenerCursosGlobal($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerProgramacionesGlobal ( $r )
    {
        $renturnModel = Api::ObtenerProgramacionesGlobal($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        $return['msj'] = "Registro realizado correctamente";
        return response()->json($return);
    }

    public function ObtenerCursosProgramados ( $r )
    {
        $renturnModel = Api::ObtenerCursosProgramados($r);
        return response()->json($renturnModel);
    }

    public function ObtenerEspecialidadesProgramados ( $r )
    {
        $renturnModel = Api::ObtenerEspecialidadesProgramados($r);
        return response()->json($renturnModel);
    }

    public function RegistrarInscripciones ( $r )
    {
        $renturnModel = Api::RegistrarInscripciones($r);
        return response()->json($renturnModel);
    }

    public function RegistrarInteresado ( $r )
    {
        $renturnModel = Api::RegistrarInteresado($r);
        return response()->json($renturnModel);
    }

    public function ValidarInteresado ( $r )
    {
        $renturnModel = Api::ValidarInteresado($r);
        return response()->json($renturnModel);
    }

}
