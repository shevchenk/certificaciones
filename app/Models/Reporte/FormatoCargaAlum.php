<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;

class FormatoCargaAlum extends Model
{
    protected   $table = '';

    public static function runLoad($r)
    {
    }

    public static function runExport($r)
    {
        $rsql= array();

        $length=array(
            'A'=>15,'B'=>12,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>15,
            'I'=>15,'J'=>25,'K'=>15,
            'L'=>15,'M'=>15,'N'=>15
        );
        $cabecera=array(
            'ID SUCURSAL','ID ENVIO','NOMBRE','APE. PATERNO','APE. MATERNO','DNI','CERTIFICADO','NOTA_CERTIF',
            'TIPO_CERTIF','DIRECCION','REFERENCIA',
            'REGION','PROVINCIA','DISTRITO',
        );
        $campos=array();

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='N'; // Max. Celda en LETRA
        return $r;
    }
}
