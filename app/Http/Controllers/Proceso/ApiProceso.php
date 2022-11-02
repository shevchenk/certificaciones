<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\ApiPro;
use Illuminate\Support\Facades\Validator;
use Auth;

class ApiProceso extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $r)
    {
        $result=array();
        $keyvalidar = base64_encode(hash_hmac("sha256", $r['datos'].date("Ymd"), env('KEY'), true));
        $keyenviado = str_replace(" ","+", $r['key']);
        $datos = (array) json_decode($r['datos']);

        if( $keyenviado == $keyvalidar ){
            if($datos['opcion']=='ObtenerPersona'){
                $result = $this->ObtenerPersona($datos);
            }
            elseif($datos['opcion']=='AprobarMatricula'){
                $result = $this->AprobarMatricula($datos);
            }
            elseif($datos['opcion']=='AnularMatricula'){
                $result = $this->AnularMatricula($datos);
            }
            elseif($datos['opcion']=='Prueba'){
                $result = array();
            }
            else{
                $result = array();
            }
        }
        else{
            $result = array(
                    'Validación' => 'Error en validación de seguridad (Key Inválido)',
                    'keyvalidar' => $keyvalidar,
                    'keyenviado' => $keyenviado
                    );
        }
        return response()->json($result);
    }

    public function Show($opcion){}

    public function Store(){}

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
    
    public function ObtenerPersona ( $r )
    {
        $renturnModel = ApiPro::ObtenerPersona($r);
        $return['rst'] = 1;
        $return['data'] = $renturnModel;
        return $return;
    }

    public function AprobarMatricula($r)
    {
        $result['rst'] = 1;
        return $result;
    }

    public function AnularMatricula($r)
    {
        $result['rst'] = 1;
        return $result;
    }

}
