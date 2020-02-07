<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mantenimiento\Menu;
use App\Models\Proceso\Matricula;
use Auth;
use DB;

class MatriculaCuota extends Model
{
    protected   $table = 'mat_matriculas_cuotas';

    public static function guardarPagoCuota($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $MC = new MatriculaCuota;
        $MC->matricula_id= $r->matricula_id;
        $MC->sucursal_id= $r->sucursal[$r->index];
        $MC->cuota= $r->cuota[$r->index];
        $MC->nro_cuota= $r->nro_cuota[$r->index];
        $MC->monto_cuota= $r->monto_cuota[$r->index];
        $MC->tipo_pago_cuota= $r->tipo_pago_cuota[$r->index];

        if( trim($r->pago_nombre_cuota[$r->index])!='' ){
            $type=explode(".",$r->pago_nombre_cuota[$r->index]);
            $extension=".".$type[1];
        }
        $url = "upload/m$r->matricula_id/cuotas/C".$r->cuota[$r->index].$extension; 
        if( trim($r->pago_archivo_cuota[$r->index])!='' ){
            $MC->archivo_cuota= $url;
            Menu::fileToFile($r->pago_archivo_cuota[$r->index], $url);
        }

        $MC->estado = 1;
        $MC->persona_id_created_at=$user_id;
        $MC->save();


        /*********************** Se Agrega Saldos ******************/
        $matricula = Matricula::find($MC->matricula_id);
        $programacionVal= DB::table('mat_especialidades_programaciones_cronogramas')
                          ->where('cuota',$MC->cuota)
                          ->where('especialidad_programacion_id',$matricula->especialidad_programacion_id)
                          ->where('estado',1)
                          ->first();
        $monto_precio= $programacionVal->monto_cronograma*1;
        $monto_saldo= $programacionVal->monto_cronograma*1 - $MC->monto_cuota;
        if($monto_saldo<0){
            $monto_saldo=0;
        }

        if( $monto_saldo>0 ){
            $mtsaldo= new MatriculaSaldo;
            $mtsaldo->matricula_id= $MC->matricula_id;
            $mtsaldo->nro_pago= $MC->nro_cuota;
            $mtsaldo->archivo= $MC->archivo_cuota;
            $mtsaldo->cuota= $MC->cuota;
            $mtsaldo->saldo= $monto_saldo;
            $mtsaldo->precio= $monto_precio;
            $mtsaldo->pago= $MC->monto_cuota;
            $mtsaldo->tipo_pago= $MC->tipo_pago_cuota;
            $mtsaldo->persona_id_created_at= Auth::user()->id;
            $mtsaldo->save();
        }

        DB::commit();

        $matricula= Matricula::find($r->matricula_id);
        $return['matricula_id'] = $matricula->id;
        $return['especialidad_programacion_id'] = $matricula->especialidad_programacion_id;
        $return['rst'] = 1;
        $return['msj'] = 'Registro realizado';
        return $return;
    }

    public static function actualizarPagoCuota($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;

        $valida= MatriculaCuota::where('cuota',$r->cuota[$r->index])
                ->where('matricula_id',$r->matricula_id)
                ->where('estado',1)
                ->first();

        $MCA= MatriculaCuota::find($valida->id);
        $MCA->estado= 0;
        $MCA->persona_id_updated_at= $user_id;
        $MCA->save();


        $MC = new MatriculaCuota;
        $MC->matricula_id= $r->matricula_id;
        $MC->sucursal_id= $r->sucursal[$r->index];
        $MC->cuota= $r->cuota[$r->index];
        $MC->nro_cuota= $r->nro_cuota[$r->index];
        $MC->monto_cuota= $r->monto_cuota[$r->index];
        $MC->tipo_pago_cuota= $r->tipo_pago_cuota[$r->index];

        $extension='';
        if( trim($r->pago_nombre_cuota[$r->index])!='' ){
            $type=explode(".",$r->pago_nombre_cuota[$r->index]);
            $extension=".".$type[1];
        }
        $url = "upload/m$r->matricula_id/cuotas/C".$r->cuota[$r->index].$extension; 
        if( trim($r->pago_archivo_cuota[$r->index])!='' ){
            $MC->archivo_cuota= $url;
            Menu::fileToFile($r->pago_archivo_cuota[$r->index], $url);
        }
        else{
            $MC->archivo_cuota= $MCA->archivo_cuota;
        }

        $MC->estado = 1;
        $MC->persona_id_created_at=$user_id;
        $MC->save();
        DB::commit();

        $matricula= Matricula::find($r->matricula_id);
        $return['matricula_id'] = $matricula->id;
        $return['especialidad_programacion_id'] = $matricula->especialidad_programacion_id;
        $return['rst'] = 1;
        $return['msj'] = 'Registro actualizado';
        return $return;
    }
}
