<?php
namespace App\Http\Controllers\BasicManage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicManage\ProveedorDetalle;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProveedorDetalleBM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            ProveedorDetalle::runEditStatus($r);
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

                'categoria_id' => 
                       ['required',
                        Rule::unique('proveedores_detalles','categoria_id')->where(function ($query) use($r) {
                                $query->where('categoria_id',$r->categoria );
                        }),
                        ],                  
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                ProveedorDetalle::runNew($r);
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
                'categoria_id' => 
                       ['required',
                        Rule::unique('proveedores_detalles','categoria_id')->ignore($r->id)->where(function ($query) use($r) {
                                $query->where('categoria_id',$r->categoria );
                        }),
                        ],                  
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                ProveedorDetalle::runEdit($r);
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
            $renturnModel = ProveedorDetalle::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
