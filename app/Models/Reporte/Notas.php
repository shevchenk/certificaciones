<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Notas extends Model
{
    protected   $table = 'mat_promocion';
    
        public static function runLoadNOTAS($r)
    {
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                    ->where( 
                        function($query){
                            $query->where('mmd.tipo_matricula_detalle','=',1);
                            $query->orwhere('mmd.tipo_matricula_detalle','=',3);
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
                $join->on('mc.id','=','mp.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
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
            ->join('personas AS pdoc',function($join){
                $join->on('pdoc.id','=','mp.persona_id');
            })
            ->select('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email',
                     's.sucursal','mp.fecha_inicio','mp.fecha_final',
                      DB::raw('CONCAT_WS(" ",pdoc.paterno,pdoc.materno,pdoc.nombre) as docente'),
                      DB::raw('IF(mp.sucursal_id=1, "Online", "Presencial") AS modalidad'),
                      'mc.curso','mmd.nota_curso_alum',
                      DB::raw('mmd.nro_pago_certificado as nro_pago_certificado'),
                      DB::raw('mmd.monto_pago_certificado as monto_pago_certificado'),
                      'mm.nro_promocion','mm.monto_promocion')
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!='' AND $inicial != 'undefined' AND $final != 'undefined'){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_inicio_progra") AND $r->has("fecha_final_progra")){
                        $inicial=trim($r->fecha_inicio_progra);
                        $final=trim($r->fecha_final_progra);
                        if( $inicial !=''AND $final!=''){
                            $query ->where(DB::raw('DATE_FORMAT(mp.fecha_inicio,"%Y-%m")'), '<=', $inicial);
                            $query ->where(DB::raw('DATE_FORMAT(mp.fecha_final,"%Y-%m")'), '<=', $final);
                        }
                    }
                }
            );
            //->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email',
            //         's.sucursal','mp.fecha_inicio','mp.fecha_final', 'mp.sucursal_id', 'mmd.nota_curso_alum','mm.nro_promocion','mm.monto_promocion');
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runExportNotas($r)
    {
        $rsql= Notas::runLoadNOTAS($r);

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,
            'I'=>15,'J'=>15,'K'=>15,
            //'M'=>15,'N'=>15,'O'=>15,'P'=>15,
            'L'=>15,'M'=>15,'N'=>15,'O'=>15,
            'P'=>15,'Q'=>15,
            'R'=>20,'S'=>20,
        );
        $cabecera=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email',
            'Sucursal','Fecha Inicio','Fecha Final',
            'Docente','Modalidad','Curso','Nota Curso',
            'Nro Pago Certificado','Monto Pago Certificado',
            'Nro Pago Promocion','Monto Pago Promocion'
        );
        $campos=array(
            '','id','dni','nombre','paterno','materno','telefono','celular','email',
             'sucursal','fecha_inicio','fecha_final',
             'docente','modalidad','curso','nota_curso_alum',
             'nro_pago_certificado','monto_pago_certificado',
             'nro_promocion','monto_promocion'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='S'; // Max. Celda en LETRA
        return $r;
    }
}
