<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;

class Reporte extends Model
{
    protected   $table = 'mat_promocion';
    
    public static function runLoadPAE($r)
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
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago',
                     DB::raw('GROUP_CONCAT( mc.curso) cursos'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado) nro_pago_certificado'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado) monto_pago_certificado'),
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
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago');
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runExportPAE($r)
    {
        $rsql= $this->runLoadPAE($r);

        $length=array(
            5,25,15,20,26,40,
            15,20,15,20,
            15,15,15,
            20,15,15,20,20,10,
            15,15,15,30,
            15,
            25,15,15,
            15,15,40
        );
        $cabecera=array(
            'N°','Persona que registró Problema','Telefono','Problema General','Tipo Problema','Descripción',
            'Sede','Persona que Contrató','Cuanto gana','Persona que Autorizó',
            'Mes que se debe','Nro cuenta','Nombre Banco',
            'Para','Área','Ode Solicitante','Nombre Cajero','Empresa','Cantidad',
            'Nro Última boleta de venta','Enviar por','Fecha y Hora aproximado de envio','Información Adicional',
            'Fecha Registro',
            'Persona que atendió Problema','Fecha Atención','Fecha Solucionado/Rechazado',
            'Tiempo Transcurrido','Estado Problema','Resultado'
        );
        $campos=array(
            '','persona','telefono','problema_general','tipo_problema','descripcion',
            'sede','contrato','gana','autorizo',
            'mes','nrocta','banco',
            'para','area','ode','cajero','empresa','cantidad',
            'ultboleta','enviar','fecha','adicional',
            'fecha_registro',
            'persona_atendio','fecha_atendio','fecha_fin',
            'tiempo_transcurrido','estado_problema','resultado'
        );
        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        return $r;
    }

    public static function runExportPAE2($r)
    {
        $rsql= DB::table('mat_cursos as c')
                ->select('curso','certificado_curso','curso_apocope',
                DB::raw('IF(tipo_curso=1,"Curso","Seminario") tipo_curso')
                );

        $length=array(
            5,20,20,15,
            17
        );
        $cabecera=array(
            'N°','Curso','Certificado','Apocope',
            'Tipo Curso',
        );
        $campos=array(
            '','curso','certificado_curso','curso_apocope',
            'tipo_curso'
        );
        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        return $r;
    }
}
