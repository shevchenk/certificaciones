<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Especialidad;
use App\Models\Mantenimiento\CursoEspecialidad;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class EspecialidadEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Especialidad::runEditStatus($r);
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
                'especialidad' => 
                       ['required',
                        Rule::unique('mat_especialidades','especialidad')
                        ->where('empresa_id', Auth::user()->empresa_id),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Especialidad::runNew($r);
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
                'especialidad' => 
                       ['required',
                        Rule::unique('mat_especialidades','especialidad')
                        ->where('empresa_id', Auth::user()->empresa_id)
                        ->ignore($r->id),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Especialidad::runEdit($r);
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
            $renturnModel = Especialidad::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function ListEspecialidad (Request $r )
    {
        if ( $r->ajax() ) {
            $v= true;
            $renturnModel=array();
            if( $r->has('persona_id') ){
                if( $r->has('validacion') AND $r->validacion==2 ){
                    $renturnModel = Especialidad::ListEspecialidadNuevo($r);
                    $v=false;
                }
                else if( $r->has('validacion') AND $r->validacion==3 ){
                    $renturnModel = Especialidad::ListEspecialidadAlumno($r);
                    $v=false;
                }
            }

            if( $v==true ){
                $renturnModel = Especialidad::ListEspecialidad($r);
            }
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function ListEspecialidadDisponible (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Especialidad::ListEspecialidadDisponible($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarEspecialidadCurso (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Especialidad::CargarEspecialidadCurso($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
