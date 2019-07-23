<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class MatriculaDetalle extends Model
{
    protected   $table = 'mat_matriculas_detalles';

    public static function runEditDetalleStatus($r)
    {
        $user_id = Auth::user()->id;
        $md = MatriculaDetalle::find($r->id);
        $md->estado = 0;
        $md->persona_id_updated_at=$user_id;
        $md->save();
    }

    public static function runEditDetalleEspecialidadStatus($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $md = MatriculaDetalle::find($r->id);
        $md->estado = 0;
        $md->persona_id_updated_at=$user_id;
        $md->save();

        $mdn = new MatriculaDetalle;
        $mdn->matricula_id = $md->matricula_id;
        $mdn->norden = $md->norden;
        $mdn->curso_id = $md->curso_id;
        $mdn->especialidad_id = $md->especialidad_id;
        $mdn->nro_pago = $md->nro_pago;
        $mdn->monto_pago = $md->monto_pago;
        $mdn->nro_pago_certificado = $md->nro_pago_certificado;
        $mdn->monto_pago_certificado = $md->monto_pago_certificado;
        $mdn->tipo_matricula_detalle = $md->tipo_matricula_detalle;
        $mdn->tipo_pago = $md->tipo_pago;
        $mdn->archivo_pago = $md->archivo_pago;
        $mdn->archivo_pago_certificado = $md->archivo_pago_certificado;
        $mdn->archivo_dni = $md->archivo_dni;
        $mdn->nota_curso_alum = $md->nota_curso_alum;
        $mdn->gratis = $md->gratis;
        $mdn->comentario = $md->comentario;
        $mdn->validavideo = $md->validavideo;
        $mdn->validadescarga = $md->validadescarga;
        $mdn->fecha_entrega = $md->fecha_entrega;
        $mdn->comentario_entrega = $md->comentario_entrega;
        $mdn->estado=1;
        $mdn->persona_id_created_at=$user_id;
        $mdn->save();
        DB::commit();
    }
}
