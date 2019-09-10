<?php
namespace App\Http\Controllers\SecureAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Proceso\Matricula;
use App\Models\Mantenimiento\Persona;
use Auth;
use Hash;
use DB;

class ValidaSA extends Controller
{
    public function ValidarInscripcion(Request $r)
    {
        $key2=$r->persona.'-'.$r->matricula;
        if (Hash::check($key2, $r->key))
        {
            $persona=DB::table('personas')->find($r->persona);
            $valores['valida_ruta_url'] = '';
            
            $valores['key']=$r->key;
            $valores['persona']=$r->persona;
            $valores['matricula']=$r->matricula;
            $valores['paterno']=$persona->paterno;
            $valores['materno']=$persona->materno;
            $valores['nombre']=$persona->nombre;
            $valores['sexo']=$persona->sexo;
            $valores['dni']=$persona->dni;
            $valores['email']=$persona->email;
            $valores['celular']=$persona->celular;
            return view('secureaccess.valida')->with($valores);
        }
        else{
            return redirect('/');
        }
    }

    public function ValidarMatricula (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Matricula::ValidarMatricula($r);
            $return['rst'] = 1;
            $return['estado'] = $renturnModel;
            return response()->json($return);
        }
    }

    public function ConfirmarInscripcion(Request $r )
    {
        if ( $r->ajax() ) {
            
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',

            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->persona_id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                    Persona::ConfirmarInscripcion($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro actualizado';
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }
}
