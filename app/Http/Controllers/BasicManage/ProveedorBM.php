<?php
namespace App\Http\Controllers\BasicManage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicManage\Proveedor;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProveedorBM extends Controller
{
    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Proveedor::runEditStatus($r);
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
                'unique'        => 'Persona solo debe ser único',
            );
            if( $r->has('empresa_id') ){        
                $mensaje['unique']=str_replace('Persona','Empresa',$mensaje['unique']);
            }
            
            $rules = array(
                'persona_id' => 
                       ['required',
                        Rule::unique('proveedores','persona_id')->where(function ($query) use($r) {
                            if( $r->has('empresa_id') ){
                                $query->where('empresa_id', $r->empresa_id);
                            }
                            else {
                               $query->where('empresa_id', null); 
                            }
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                Proveedor::runNew($r);
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
                'unique'        => 'Persona Solo debe ser único',
            );
            if( $r->has('empresa_id') ){        
                $mensaje['unique']=str_replace('Persona','Empresa',$mensaje['unique']);
            }
            
            $rules = array(
                'persona_id' => 
                       ['required',
                        Rule::unique('proveedores','persona_id')->ignore($r->id)->where(function ($query) use($r) {
                            if( $r->has('empresa_id') ){
                                $query->where('empresa_id', $r->empresa_id);
                            }
                            else {
                               $query->where('empresa_id', null); 
                            }
                        }),
                        ],
            );


            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                Proveedor::runEdit($r);
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
            $renturnModel = Proveedor::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }


}
