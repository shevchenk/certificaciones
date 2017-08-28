<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;

class Seminario extends Model
{
    protected   $table = 'mat_promocion';
    
    public static function runLoadSeminario($r)
    {
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                    ->where( 
                        function($query){
                            $query->where('mmd.tipo_matricula_detalle','=',4);
                        }
                    );
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');

            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.persona_id','=','p.id');

            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');

            })
            ->join('mat_tipos_participantes AS mtp',function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');

            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');

            })
            ->join('mat_cursos AS mc',function($join){
                $join->on('mc.id','=','mp.curso_id');

            })
            ->join('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');

            })
            ->join('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');

            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');

            })
            ->select('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante',
                     DB::raw('GROUP_CONCAT( mc.curso) seminario'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado) nro_pago'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado) monto_pago'),
                     DB::raw('CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre) as cajera'),
                     DB::raw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) as marketing'),
                     DB::raw('CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre) as matricula'),
                    'mm.nro_promocion','mm.monto_promocion')
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }
                }
            )
            ->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante');
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runExportSeminario($r)
    {
        $rsql= Seminario::runLoadSeminario($r);

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,'I'=>30,
            'J'=>15,'K'=>15,'L'=>15,
            //'M'=>15,'N'=>15,'O'=>15,'P'=>15,
            'M'=>15,'N'=>15,'O'=>15, //''=>15,''=>15,''=>15,''=>15,''=>15,''=>15,
            'P'=>20,'Q'=>20,'R'=>20,
        );
        $cabecera=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            //'Nro Pago','Monto Pago','Nro Pago','Monto Pago',
            'Seminario','Nro Pago','Monto Pago',//'Curso 2','Nro Pago','Monto Pago','Curso 3','Nro Pago','Monto Pago',
            'Cajera','Marketing','Matrícula'
        );
        $campos=array(
            '','id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             //'nro_pago_inscripcion','monto_pago_inscripcion',
             //'nro_pago','monto_pago',
             'seminario','nro_pago','monto_pago',
             'cajera','marketing','matricula'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='R'; // Max. Celda en LETRA
        return $r;
    }
}
