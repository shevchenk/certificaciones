<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Programacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramacionEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Programacion::runEditStatus($r);
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
                'unique'        => 'Ya existe la Programación',
            );

            $rules = array(
                'docente_id' => 
                       ['required',
                        Rule::unique('mat_programaciones','docente_id')->where(function ($query) use($r) {
                                $query->where('sucursal_id',$r->sucursal_id );
                                $query->where('curso_id',$r->curso_id );
                                $query->where('fecha_inicio',$r->fecha_inicio );
                                $query->where('fecha_final',$r->fecha_final );
                                $query->where('dia',$r->dia );
                        }),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Programacion::runNew($r);
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
                'unique'        => 'Ya existe la Programación',
            );

            $rules = array(
                'docente_id' => 
                       ['required',
                        Rule::unique('mat_programaciones','docente_id')->ignore($r->id)->where(function ($query) use($r) {
                                $query->where('sucursal_id',$r->sucursal_id );
                                $query->where('curso_id',$r->curso_id );
                                $query->where('fecha_inicio',$r->fecha_inicio );
                                $query->where('fecha_final',$r->fecha_final );
                                $query->where('dia',$r->dia );
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Programacion::runEdit($r);
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
            $renturnModel = Programacion::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    
}
