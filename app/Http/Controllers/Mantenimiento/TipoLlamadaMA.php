<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\TipoLlamada;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class TipoLlamadaMA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            TipoLlamada::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

   public function New(Request $r )
    {
        if ( $r->ajax() ) {

            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'      => ':attribute solo debe ser único',
            );

            $empresa_id = Auth::user()->empresa_id;
            $rules = array(
                'tipo_llamada' => 
                       ['required',
                        Rule::unique('tipo_llamadas','tipo_llamada')
                        ->where('empresa_id',$empresa_id),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                TipoLlamada::runNew($r);
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

    public function Edit(Request $r )
    {
        if ( $r->ajax() ) {
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $empresa_id = Auth::user()->empresa_id;
            $rules = array(
                'tipo_llamada' => 
                       ['required',
                        Rule::unique('tipo_llamadas','tipo_llamada')->ignore($r->id)
                        ->where('empresa_id',$empresa_id),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                TipoLlamada::runEdit($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            return response()->json($return);
        }
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            $empresa_id = Auth::user()->empresa_id;
            $r['empresa_id'] = $empresa_id;
            $renturnModel = TipoLlamada::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";    
            return response()->json($return);   
        }
    }
    
    public function ListTipoLlamada (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = TipoLlamada::ListTipoLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
