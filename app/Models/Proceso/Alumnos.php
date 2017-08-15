<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class Alumnos extends Model
{
    protected   $table = 'alumnos';
    /*
    public static function runNew($r)
    {
        $obj = new Alumnos;

        $obj->sucursal_id = trim( $r[0] );
        $obj->id_envio = trim( $r[1] );
        $obj->nombre = trim( $r[2] );
        $obj->paterno = trim( $r[3] );
        $obj->materno = trim( $r[4] );
        $obj->dni = trim( $r[5] );
        $obj->certificado = trim( $r[6] );
        $obj->nota_certificado = trim( $r[7] );
        $obj->tipo_certificado = trim( $r[8] );
        $obj->direccion = trim( $r[9] );
        $obj->referencia = trim( $r[10] );
        $obj->region = trim( $r[11] );
        $obj->provincia = trim( $r[12] );
        $obj->distrito = trim( $r[13] );

        $obj->estado = 1;
        $obj->persona_id_created_at=Auth::user()->id;
        $obj->save();
    }
    */
}
