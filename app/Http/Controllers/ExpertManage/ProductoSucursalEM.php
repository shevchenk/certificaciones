<?php
namespace App\Http\Controllers\ExpertManage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExpertManage\ProductoSucursal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductoSucursalEM extends Controller
{
    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            ProductoSucursal::runEditStatus($r);
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
                'unique'        => 'Producto debe ser único',
            );

            $rules = array(
                'producto_id' => 
                       ['required',
                        Rule::unique('productos_sucursales','producto_id')->where(function ($query) use($r) {
                                $query->where('sucursal_id',$r->sucursal );
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                ProductoSucursal::runNew($r);
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
                'unique'        => 'Producto solo debe ser único',
            );

            $rules = array(
                'producto_id' => 
                       ['required',
                        Rule::unique('productos_sucursales','producto_id')->ignore($r->id)->where(function ($query) use($r) {
                                $query->where('sucursal_id',$r->sucursal );
                        }),
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);
            
            if (!$validator->fails()) {
                ProductoSucursal::runEdit($r);
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
            $renturnModel = ProductoSucursal::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    

}
