<?php
namespace App\Http\Controllers\Certificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificacion\PagoAlumnoDetalle;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PagoAlumnoDetalleCE extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            
            
            $renturnModel = PagoAlumnoDetalle::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
       public function New(Request $r )
    {
        if ( $r->ajax() ) {

            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'certificado_id' => 
                       ['required',
                        Rule::unique('mat_cursos','curso'),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
            PagoAlumnoDetalle::runNew($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro creado';
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            return response()->json($return);
        }
    }
    

}
