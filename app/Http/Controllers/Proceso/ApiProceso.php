<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\ApiPro;
use App\Models\Proceso\Matricula;
use App\Models\Mantenimiento\Persona;
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
        $persona = Persona::where('dni', $r['dni'])->first();
        $id = 1;
        if( isset($persona->id) ){
            $id = $persona->id;
        }
        $matricula= Matricula::find($r['matricula_id']);
        $matricula->estado_mat = 'Aprobado';
        $matricula->fecha_estado = date("Y-m-d");
        $matricula->persona_id_updated_at = $id;
        $matricula->save();
        $result['rst'] = 1;
        return $result;
    }

    public function AnularMatricula($r)
    {
        $persona = Persona::where('dni', $r['dni'])->first();
        $id = 1;
        if( isset($persona->id) ){
            $id = $persona->id;
        }
        DB::beginTransaction();
        $matricula= Matricula::find($r['matricula_id']);
        $matricula->estado_mat = 'Rechazado';
        $matricula->fecha_estado = date("Y-m-d");
        $matricula->expediente = '';
        $matricula->fecha_expediente = null;
        $matricula->persona_id_updated_at = $id;
        $matricula->observacion = "Anulado por Tesorería | ".$matricula->observacion;
        $matricula->estado = 0;
        $matricula->save();

        DB::table('mat_matriculas_detalles')
        ->where('matricula_id', '=', $r['matricula_id'])
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        DB::commit();
        $result['rst'] = 1;
        return $result;
    }

}
