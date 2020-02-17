<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Matricula;
use App\Models\Proceso\Alumno;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class MatriculaPR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 


   public function New(Request $r )
    {
        if ( $r->ajax() ) {

            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'persona_id' => 
                       ['required'
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                if( trim($r->nro_promocion)!=''){
                    $nro_p = trim( $r->nro_promocion);
                    $monto_p = trim( $r->monto_promocion);
                    $tipo_p = trim( $r->tipo_pago);
                    $sql="
                    SELECT id
                    FROM mat_matriculas
                    WHERE nro_promocion='$nro_p'
                    AND monto_promocion='$monto_p'
                    AND tipo_pago='$tipo_p'
                    UNION 
                    SELECT id
                    FROM mat_matriculas_detalles
                    WHERE nro_pago_certificado='$nro_p'
                    AND monto_pago_certificado='$monto_p'
                    AND tipo_pago='$tipo_p'
                    ";
                    $datos = DB::select($sql);
                    if( isset($datos[0]->id) ){
                        $return['rst'] = 2;
                        $return['msj'] = 'El nro:'.$nro_p.'| monto:'.$monto_p.' ya fue registrado anteriormente';
                        return response()->json($return);
                    }
                }
                else{
                    $curso_id = $r->curso_id;
                    for($i=0;$i<count($curso_id);$i++){
                        $nro_p = $r->nro_pago_certificado[$i];
                        $monto_p = $r->monto_pago_certificado[$i];
                        $tipo_p = $r->tipo_pago_detalle[$i];
                        if( $nro_p!='' AND $monto_p!='' ){
                            $sql="
                            SELECT id
                            FROM mat_matriculas
                            WHERE nro_promocion='$nro_p'
                            AND monto_promocion='$monto_p'
                            AND tipo_pago='$tipo_p'
                            UNION 
                            SELECT id
                            FROM mat_matriculas_detalles
                            WHERE nro_pago_certificado='$nro_p'
                            AND monto_pago_certificado='$monto_p'
                            AND tipo_pago='$tipo_p'
                            ";
                            $datos = DB::select($sql);
                            if( isset($datos[0]->id) ){
                                $return['rst'] = 2;
                                $return['msj'] = 'El nro:'.$nro_p.'| monto:'.$monto_p.' ya fue registrado anteriormente';
                                return response()->json($return);
                            }
                        }
                    }
                }
                $id=Matricula::runNew($r);
                $return['rst'] = 1;
                $return['matricula_id'] = $id;
                $return['msj'] = 'Registro creado';
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            return response()->json($return);
        }
    }
    
    public function BuscarAlumno (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::BuscarAlumno($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
