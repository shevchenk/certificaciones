<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;

class TramiteHisto extends Model
{
    protected   $table = 'certificados';
    
    public static function runLoad($r)
    {
        $sql=DB::table('certificados AS c')
            ->join('certificados_historico AS ch',function($join){
                $join->on('ch.certificado_id','=','c.id')
                ->where( 
                        function($query){
                            $query->where('ch.estado','=',1);
                        }
                    );
            })
            ->join('alumnos AS a',function($join){
                $join->on('a.id','=','c.alumno_id');
            })
            ->join('certificados_estados AS ce',function($join){
                $join->on('ce.id','=','c.certificado_estado_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','c.sucursal_id');

            })
            ->select('a.id_envio', 'a.nombre', 'a.paterno', 'a.materno', 'a.dni', 'a.certificado', 'a.nota_certificado', 
                        'a.direccion', 'a.referencia', 'a.region', 'a.provincia', 'a.distrito', 'c.fecha_estado_certificado', 's.sucursal', 'c.nro_pago', 
                    DB::raw('IFNULL(c.fecha_pago, "") as fecha_pago'),
                     'c.updated_at as fecha_inicio_bandeja', 'ce.estado_certificado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(c.fecha_estado_certificado,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }
                }
            );
        //$result = $sql->orderBy('c.id','asc')->orderBy('ch.id','asc')->get();
        $result = $sql->orderBy('c.id','asc')->get();
        return $result;
    }

    public static function runExport($r)
    {
        $rsql= TramiteHisto::runLoad($r);

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,
            'I'=>15,'J'=>15,'K'=>15,
            'L'=>15,'M'=>15,'N'=>15,'O'=>15,
            'P'=>15,'Q'=>15,
            'R'=>20
        );
        $cabecera=array(
            'ID','Nombre','Paterno','Materno','DNI','Certificado','Nota Certificado',
            'Direccion','Referencia','Region',
            'Provincia','Distrito','Fecha Estado Certi','Sucursal',
            'Nro Pago','Fecha Pago',
            'Fecha Inicio Bandeja','Estado Certificado'
        );
        $campos=array(
            '',
            'id_envio', 'nombre', 'paterno', 'materno', 'dni', 'certificado', 'nota_certificado', 
            'direccion', 'referencia', 'region', 'provincia', 'distrito', 'fecha_estado_certificado', 
            'sucursal', 'nro_pago', 'fecha_pago',
            'fecha_inicio_bandeja', 'estado_certificado'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='R'; // Max. Celda en LETRA
        return $r;
    }
}
