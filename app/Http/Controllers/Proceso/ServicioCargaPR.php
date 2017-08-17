<?php
namespace App\Http\Controllers\Proceso;

//use Illuminate\Foundation\Testing\WithoutMiddleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumnos;
use App\Models\Proceso\Certificados;

class ServicioCargaPR extends Controller
{
    //use WithoutMiddleware;

    public function __construct()
    {
        //$this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function index(){
        //
    }

    public function store()
    {
        $obj = json_decode( file_get_contents('php://input') );   
        $objArr = (array)$obj;

        if (empty($objArr))
        {
            $this->response(422,"error","Ingrese sus datos de envio");                           
        }
        else if(isset($obj->key[0]->id) && isset($obj->key[0]->token))
        {
            $tab_cli = DB::table('clientes_accesos')->select('id', 'nombre', 'key')
                                                    ->where('id','=', $obj->key[0]->id)
                                                    ->where('key','=', $obj->key[0]->token)
                                                    ->first();

            if($obj->key[0]->id == @$tab_cli->id && $obj->key[0]->token == @$tab_cli->key)
            {
                $val = $this->insertAlumno($objArr);
                if($val == true)
                    $this->response(200,"success","Proceso ejecutado satisfactoriamente");
                else
                    $this->response(422,"error","Revisa tus parametros de envio");
            }
            else
            {
                $this->response(422 ,"error","Su Key no es valido");
            }
        }
        else
        {
            $this->response(422,"error","Revisa tus parametros de envio");
        }
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


    public function insertAlumno($objArr)
    {
        $arr_lis = array();

        DB::beginTransaction();
        try 
        {
            foreach ($objArr['alumnos'] as $k=>$value)
            {
                $con = 0;
                foreach ($objArr['alumnos'][$k] as $l) $con ++;

                $arr_lis[0] = $value->sucursal_id;
                $arr_lis[1] = $value->id_envio;
                $arr_lis[2] = $value->nombre;
                $arr_lis[3] = $value->paterno;
                $arr_lis[4] = $value->materno;
                $arr_lis[5] = $value->dni;
                $arr_lis[6] = $value->certificado;
                $arr_lis[7] = $value->nota_certificado;
                $arr_lis[8] = $value->tipo_certificado;

                $alumnos = Alumnos::where('sucursal_id', '=', trim($arr_lis[0]))
                                    ->where('id_envio', '=', trim($arr_lis[1]))
                                    ->where('nombre', '=', trim($arr_lis[2]))
                                    ->where('paterno', '=', trim($arr_lis[3]))
                                    ->where('materno', '=', trim($arr_lis[4]))
                                    ->where('dni', '=', trim($arr_lis[5]))
                                    ->where('certificado', '=', trim($arr_lis[6]))
                                    ->where('nota_certificado', '=', trim($arr_lis[7]))
                                    ->where('tipo_certificado', '=', trim($arr_lis[8]))
                                    ->first();

                if (count($alumnos) == 0) 
                {
                    // Graba Alumnos
                    $obj = new Alumnos;
                    $obj->sucursal_id = trim( $value->sucursal_id );
                    $obj->id_envio = trim( $value->id_envio );
                    $obj->nombre = trim( $value->nombre );
                    $obj->paterno = trim( $value->paterno );
                    $obj->materno = trim( $value->materno );
                    $obj->dni = trim( $value->dni );
                    $obj->certificado = trim( $value->certificado );
                    $obj->nota_certificado = trim( $value->nota_certificado );
                    $obj->tipo_certificado = trim( $value->tipo_certificado );
                    $obj->direccion = trim( $value->direccion );
                    $obj->referencia = trim( $value->referencia );
                    $obj->region = trim( $value->region );
                    $obj->provincia = trim( $value->provincia );
                    $obj->distrito = trim( $value->distrito );
                    $obj->tipo_reg = 'RF';

                    $obj->estado = 1;
                    $obj->persona_id_created_at=1;
                    $obj->save();
                    // --

                    // Graba Alumnos Historial
                    if($con > 14)
                        DB::table('alumnos_historico')->insert([
                            'alumno_id' => $obj->id,
                            'campo_1' => trim( @$value->campo_1 ),
                            'campo_2' => trim( @$value->campo_2 ),
                            'campo_3' => trim( @$value->campo_3 ),
                            'campo_4' => trim( @$value->campo_4 ),
                            'campo_5' => trim( @$value->campo_5 ),
                            'campo_6' => trim( @$value->campo_6 ),
                            'campo_7' => trim( @$value->campo_7 ),
                            'campo_8' => trim( @$value->campo_8 ),
                            'campo_9' => trim( @$value->campo_9 ),
                            'campo_10' => trim( @$value->campo_10 ),
                            'campo_11' => trim( @$value->campo_11 ),
                            'campo_12' => trim( @$value->campo_12 ),
                            'campo_13' => trim( @$value->campo_13 ),
                            'campo_14' => trim( @$value->campo_14 ),
                            'campo_15' => trim( @$value->campo_15 ),
                            'campo_16' => trim( @$value->campo_16 ),
                            'campo_17' => trim( @$value->campo_17 ),
                            'campo_18' => trim( @$value->campo_18 ),
                            'campo_19' => trim( @$value->campo_19 ),
                            'campo_20' => trim( @$value->campo_20 ),
                            'estado' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'persona_id_created_at' => 1
                        ]);
                    // --
                
                    // Graba Certificado
                    $obj_c = new Certificados;
                    $obj_c->alumno_id = $obj->id; // Id Alumnos
                    $obj_c->certificado_estado_id = 1;
                    $obj_c->fecha_estado_certificado = date("Y-m-d H:i:s");
                    $obj_c->sucursal_id = trim( $value->sucursal_id );
                    $obj_c->nota_final = trim( $value->nota_certificado );

                    $obj_c->estado = 1;
                    $obj_c->persona_id_created_at=1;
                    $obj_c->save();
                    // --

                    // Graba Certificado Historico
                    DB::table('certificados_historico')->insert([
                        'certificado_id' => $obj_c->id,
                        'certificado_estado_id' => 1,
                        'estado' => 1,
                        'created_at' => date("Y-m-d H:i:s"),
                        'persona_id_created_at' => 1
                    ]);
                    // --
                }
            }
            $msg = true;
            DB::commit();
        } 
        catch (\Exception $e) 
        {
            $msg = false;
            DB::rollback();
        }
        return $msg;
    }


}
