<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\Persona;
use App\Models\Mantenimiento\Trabajador;
use App\Models\Mantenimiento\Privilegio;
use App\Models\Proceso\Matricula;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class PersonaEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Persona::runEditStatus($r);
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
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->where(function ($query) use($r) {
                            if( $r->dni!='99999999' ){
                                $query->where('dni', $r->dni);
                            }
                            else {
                               $query->where('dni','!=' ,$r->dni); 
                            }
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                Persona::runNew($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro creado';
            }else{
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
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                Persona::runEdit($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }

    public function EditLibre(Request $r )
    {
        if ( $r->ajax() ) {
            
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',

            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                /*$valida= Matricula::where('persona_id',$r->id)->where('estado','1')->exists();
                if( $valida ){
                    $return['rst'] = 2;
                    $return['msj'] = 'Persona fue inscrita en algunos de nuestros productos, coordinar con responsable para modificar en caso sea necesario';
                }
                else{*/
                    Persona::runEditLibre($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro actualizado';
                //}
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }

    public function EditLibreLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',

            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                $valida= Matricula::where('persona_id',$r->id)->where('estado','1')->exists();
                if( $valida ){
                    $return['rst'] = 2;
                    $return['msj'] = 'Persona fue inscrita en algunos de nuestros productos, coordinar con responsable para modificar en caso sea necesario';
                }
                else{
                    Persona::runEditLibreLlamada($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro actualizado';
                }
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }

    public function EditLibreG(Request $r )
    {
        if ( $r->ajax() ) {
            
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',

            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                    Persona::runEditLibre($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro actualizado';
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadDistribuidaTotal(Request $r )
    {
        if ( $r->ajax() ) {
            $r['estado']=1;
            $renturnModel = Persona::runLoadDistribuida($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadDistribuida(Request $r )
    {
        if ( $r->ajax() ) {
            $id=Auth::user()->id;
            $empresa_id= Auth::user()->empresa_id;
            $trabajador= Trabajador::where('persona_id',$id)
                         ->where('empresa_id',$empresa_id)
                         ->where('rol_id',1)
                         ->first();
            $trabajadorid=0;
            if( isset($trabajador->id) ){
                $trabajadorid=$trabajador->id;
            }
            $r['teleoperadora']=$trabajadorid;
            $r['estado']=1;
            $renturnModel = Persona::runLoadDistribuida($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function ListPrivilegio (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Privilegio::ListPrivilegio($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function CargarAreas(Request $r)
    {
            if ( $r->ajax() ) {
                $personaId =$r->persona_id;
                $return = Persona::getAreas($personaId);
                return response()->json($return);
            }

    }
    
    public function ListarFuente (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListarFuente($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListarTipo (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListarTipo($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListarEmpresa (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListarEmpresa($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function EditStatusVisitante(Request $r )
    {
        if ( $r->ajax() ) {
            Persona::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function LoadVisitante(Request $r )
    {
        if ( $r->ajax() ) {
            $r['created_at']=date('Y-m-d');
            $renturnModel = Persona::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function NewVisitante(Request $r )
    {
        if ( $r->ajax() ) {
           
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->where(function ($query) use($r) {
                            if( $r->dni!='99999999' ){
                                $query->where('dni', $r->dni);
                            }
                            else {
                               $query->where('dni','!=' ,$r->dni); 
                            }
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                $id=Auth::user()->id;
                $trabajador=    Trabajador::where('persona_id',$id)
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->where('rol_id',1)
                                ->first();
                $trabajadorid=0;
                $return['rst'] = 1;
                $return['msj'] = "Registro realizado correctamente";
                if( isset($trabajador->id) ){
                    $trabajadorid=$trabajador->id;
                    $r['teleoperadora']=$trabajadorid;
                    Persona::runNewVisitante($r);
                }
                else{
                    $return['rst'] = 2;
                    $return['msj'] = "Ud, no esta registrado como Trabajador - Marketing";
                }
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }

            return response()->json($return);
        }
    }

    public function EditVisitante(Request $r )
    {
        if ( $r->ajax() ) {
            
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',

            );

            $rules = array(
                'dni' => 
                       ['required',
                        Rule::unique('personas','dni')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->dni=='99999999' ){
                                $query->where('dni','!=' ,$r->dni);
                            }
                        }),
                        ],
           
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                Persona::runEditVisitante($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
            }else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            
            return response()->json($return);
        }
    }

    public function ListarPersonaEmpresa (Request $r )
    {
        if ( $r->ajax() ) {
            $personaId= Auth::user()->id;
            if( isset($r->persona_id) ){
                $personaId= $r->persona_id;
            }
            $renturnModel = Persona::ListarPersonaEmpresa($personaId);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListDistrito(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListDistrito($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListPais(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListPais($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListColegio(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::ListColegio($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadAdicional(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Persona::LoadAdicional($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
}
