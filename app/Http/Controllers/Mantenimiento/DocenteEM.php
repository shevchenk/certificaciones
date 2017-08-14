<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Docente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocenteEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Docente::runEditStatus($r);
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
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'sucursal' => 
                       ['persona_id',
                        Rule::unique('mat_docentes','persona_id'),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Docente::runNew($r);
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

            $rules = array(
                'persona_id' => 
                       ['required',
                        Rule::unique('mat_docentes','persona_id')->ignore($r->id),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Docente::runEdit($r);
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
            $renturnModel = Docente::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
}
