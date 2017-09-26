<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Auth;

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
}
