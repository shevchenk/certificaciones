<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Trabajador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class TrabajadorEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Trabajador::runEditStatus($r);
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
                'unique'        => 'Persona ya existe como Trabajador',
            );

            $rules = array(
                'persona_id' => 
                       ['required',
                        Rule::unique('mat_trabajadores','persona_id')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where(function ($query) use($r) {
                                $query->where('rol_id',$r->rol_id );
                                $query->where('tarea_id',$r->tarea_id );
                            }),
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Trabajador::runNew($r);
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
                'unique'        => 'Persona ya existe como Trabajador',
            );

            $rules = array(
                'persona_id' => 
                       ['required',
                        Rule::unique('mat_trabajadores','persona_id')
                        ->where('empresa_id', Auth::user()->empresa_id)
                        ->ignore($r->id)
                        ->where(function ($query) use($r) {
                                $query->where('rol_id',$r->rol_id );
                                $query->where('tarea_id',$r->tarea_id );
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Trabajador::runEdit($r);
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
            $renturnModel = Trabajador::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function ListarTeleoperadora(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Trabajador::ListarTeleoperadora($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListarTeleoperadores(Request $r )
    {
        if ( $r->ajax() ) {
            $r['rol_id']=1;
            $renturnModel = Trabajador::ListarTeleoperadora($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
}
