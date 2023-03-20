<?php
namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\EspecialidadProgramacion;
use App\Models\Mantenimiento\EspecialidadProgramacionCronograma;
use App\Models\Mantenimiento\EspecialidadProgramacionSucursal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EspecialidadProgramacionEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            $valE = EspecialidadProgramacion::find($r->id);
            $val = EspecialidadProgramacion::where('estado', 1)
                    ->where('especialidad_id', $valE->especialidad_id)
                    ->where('tipo', $valE->tipo)
                    ->where('costo', $valE->costo)
                    ->where('costo_mat', $valE->costo_mat)
                    ->where( 
                        function($query) use ($valE){
                            if( $valE->nro_cuota!='' ){
                                $query->where('nro_cuota', $valE->nro_cuota);
                            }
                        }
                    )
                    ->where('id', '!=', $r->id)
                    ->first();

            if( isset($val->id) AND $r->estadof == 1 ){
                $return['rst'] = 2;
                $return['msj'] = 'Programación de Pago - ya existe';
            }
            else{
                EspecialidadProgramacion::runEditStatus($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
            }
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
                'especialidad_id' => 
                       ['required'],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            $val = EspecialidadProgramacion::where('estado', 1)
                    ->where('especialidad_id', $r->especialidad_id)
                    ->where('tipo', $r->tipo)
                    ->where('costo', $r->costo)
                    ->where('costo_mat', $r->costo_mat)
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('nro_cuota') && $r->nro_cuota!='' ){
                                $query->where('nro_cuota', $r->nro_cuota."-".$r->monto_cuota);
                            }
                        }
                    )
                    ->first();

            if( isset($val->id) ){
                $return['rst'] = 2;
                $return['msj'] = 'Programación de Pago - ya existe';
            }
            else{
                if ( !$validator->fails() ) {
                    EspecialidadProgramacion::runNew($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro creado';
                }
                else{
                    $return['rst'] = 2;
                    $return['msj'] = $validator->errors()->all()[0];
                }
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
                'id' => 
                       ['required'],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            $valE = EspecialidadProgramacion::find($r->id);
            $val = EspecialidadProgramacion::where('estado', 1)
                    ->where('especialidad_id', $valE->especialidad_id)
                    ->where('tipo', $r->tipo)
                    ->where('costo', $r->costo)
                    ->where('costo_mat', $r->costo_mat)
                    ->where( 
                        function($query) use ($r){
                            if( $r->has('nro_cuota') && $r->nro_cuota!='' ){
                                $query->where('nro_cuota', $r->nro_cuota."-".$r->monto_cuota);
                            }
                        }
                    )
                    ->where('id', '!=', $r->id)
                    ->first();

            if( isset($val->id) ){
                $return['rst'] = 2;
                $return['msj'] = 'Programación de Pago - ya existe';
            }
            else{
                if ( !$validator->fails() ) {
                    EspecialidadProgramacion::runEdit($r);
                    $return['rst'] = 1;
                    $return['msj'] = 'Registro actualizado';
                }
                else{
                    $return['rst'] = 2;
                    $return['msj'] = $validator->errors()->all()[0];
                }
            }
            return response()->json($return);
        }
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = EspecialidadProgramacion::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarCronograma(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = EspecialidadProgramacion::CargarCronograma($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
