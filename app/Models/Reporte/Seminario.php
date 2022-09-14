<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Seminario extends Model
{
    protected   $table = 'mat_promocion';
    
    public static function runLoadSeminario($r)
    {
        $id=Auth::user()->id;
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mm.sucursal_destino_id');
            })
            ->join('mat_tipos_participantes AS mtp',function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('empresas AS e',function($join){
                $join->on('e.id','=','mc.empresa_id');
            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');
            })
            ->leftJoin('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');
            })
            ->leftJoin('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');
            })
            ->leftJoin('mat_medios_captaciones AS meca',function($join){
                $join->on('meca.id','=','mm.medio_captacion_id');
            })
            ->leftJoin('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->leftJoin('sucursales AS s3',function($join){
                $join->on('s3.id','=','mp.sucursal_id');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula',DB::raw('GROUP_CONCAT( DISTINCT(s3.sucursal) ) AS lugar_estudio'),'e.empresa AS empresa_inscripcion'
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") AS tipo_formacion')
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) AS formacion')
                    ,'mc.curso AS curso', 'mp.dia AS frecuencia'
                    , DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) AS horario')
                    , 'mp.turno', DB::raw('DATE(mp.fecha_inicio) AS inicio')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n")
                                ) AS nro_pago')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n")
                                ) AS monto_pago')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( 
                                CASE 
                                    WHEN mmd.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mmd.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mmd.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mmd.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mmd.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END ORDER BY mmd.id SEPARATOR "\n")
                                ) AS tipo_pago')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    //,DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total')
                    ,'s.sucursal','s2.sucursal AS recogo_certificado'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre))) as cajera')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre))) as marketing')
                    ,'meca.medio_captacion'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre))) as matricula')
                    )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            //$query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has('vendedor') AND $r->vendedor==1 ){
                        $persona_id=Auth::user()->id;
                        $query->where('mm.persona_marketing_id',$persona_id);
                    }
                }
            )
            ->where('mm.estado',1)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id','mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula','e.empresa'
                    ,'mmd.especialidad_id','mc.curso','mc.tipo_curso','me.especialidad','mp.dia','mp.turno','mp.fecha_inicio','mp.fecha_final'
                    ,'mm.especialidad_programacion_id'
                    ,'s.sucursal','s2.sucursal','mm.nro_promocion','mm.monto_promocion'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion','meca.medio_captacion');
            
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runLoadSeminario2($r)
    {
        $id=Auth::user()->id;
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mm.sucursal_destino_id');
            })
            ->join('mat_tipos_participantes AS mtp',function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('empresas AS e',function($join){
                $join->on('e.id','=','mc.empresa_id');
            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->join('sucursales AS s3',function($join){
                $join->on('s3.id','=','mp.sucursal_id');
            })
            ->leftJoin('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');
            })
            ->leftJoin('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');
            })
            ->leftJoin('mat_medios_captaciones AS meca',function($join){
                $join->on('meca.id','=','mm.medio_captacion_id');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula',DB::raw('GROUP_CONCAT( DISTINCT(s3.sucursal) ) AS lugar_estudio'),'e.empresa AS empresa_inscripcion'
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") AS tipo_formacion')
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) AS formacion')
                    ,'mc.curso AS curso', 'mp.dia AS frecuencia'
                    , DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) AS horario')
                    , 'mp.turno', DB::raw('DATE(mp.fecha_inicio) AS inicio')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n")
                                ) AS nro_pago')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n")
                                ) AS monto_pago')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                "",
                                GROUP_CONCAT( 
                                CASE 
                                    WHEN mmd.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mmd.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mmd.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mmd.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mmd.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END ORDER BY mmd.id SEPARATOR "\n")
                                ) AS tipo_pago')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    //,DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total')
                    ,'s.sucursal','s2.sucursal AS recogo_certificado'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre))) as cajera')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre))) as marketing')
                    ,'meca.medio_captacion'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre))) as matricula')
                    )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            //$query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has('vendedor') AND $r->vendedor==1 ){
                        $persona_id=Auth::user()->id;
                        $query->where('mm.persona_marketing_id',$persona_id);
                    }
                }
            )
            ->where('mm.estado',1)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id','mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula','e.empresa'
                    ,'mmd.especialidad_id','mc.curso','mc.tipo_curso','me.especialidad','mp.dia','mp.turno','mp.fecha_inicio','mp.fecha_final'
                    ,'mm.especialidad_programacion_id'
                    ,'s.sucursal','s2.sucursal','mm.nro_promocion','mm.monto_promocion'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion','meca.medio_captacion');
            
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runLoadSeminarioDetalle($r)
    {
        $id=Auth::user()->id;
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');

            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');

            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');

            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mm.sucursal_destino_id');

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
            ->select('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante',

                     DB::raw('mc.curso AS seminario'),
                     DB::raw('mp.fecha_inicio AS fecha_inicio'),
                     DB::raw('mmd.nro_pago_certificado AS nro_pago'),
                     DB::raw('mmd.monto_pago_certificado AS monto_pago'),
                     DB::raw('mmd.tipo_pago AS tipo_pago'),
                     DB::raw('mmd.monto_pago_certificado AS subtotal'),
                     DB::raw('mm.monto_promocion AS total'),

                     DB::raw('CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre) as cajera'),
                     DB::raw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) as marketing'),
                     DB::raw('CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre) as matricula'),
                    'mm.nro_promocion','mm.monto_promocion','p.empresa','s2.sucursal AS recogo_certificado')
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
            ->where('mm.estado',1)
            ->where('mc.tipo_curso',2)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')');
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runExportSeminario($r)
    {
        $rsql= Seminario::runLoadSeminario2($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,20,20,20,15,15,25,30,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15,15,
            20,20,20,
            15,15,15,20,20
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'DATOS','DATOS DEL INSCRITO','SOBRE LA FORMACIÓN CONTINUA','PAGO POR CURSO','PAGO POR CONJUNTO DE CURSOS','PAGO POR INSCRIPCIÓN','DATOS DE LA VENTA'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(1,6,10,3,2,2,5);
        $colorTit=array('#FDE9D9','#F2DCDB','#C5D9F1','#FFFF00','#8DB4E2','#8DB4E2','#FCD5B4');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°','Fuente de Datos','Fuente de Datos de Empresa'
            ,'DNI','Nombre','Paterno','Materno','Telefono','Celular','Email'
            ,'Validó Email?','Fecha Inscripción','Donde Estudiará','Empresa'
            ,'Tipo de Formación Continua','Carrera / Módulo','Inicio / Curso','Frecuencia','Horario','Turno','Inicio'
            ,'Nro Pago','Monto Pago','Tipo Pago','Total Pagado'
            ,'Nro Recibo PCC','Monto PPC','Tipo Pago PPC','Nro Pago Inscripción','Monto Pago Inscripción','Tipo Pago Inscripción'
            ,'Sede De Inscripción','Recogo del Certificado','Cajero(a)','Vendedor(a)','Medio Captación','Supervisor(a)'
        );
        $campos=array('');

        $r['data']=$rsql;
        $r['campos']=$campos;
        $r['cabecera']=$cabecera;
        $r['length']=$length;
        $r['cabeceraTit']=$cabeceraTit;
        $r['lengthTit']=$lengthTit;
        $r['colorTit']=$colorTit;
        $r['lengthDet']=$lengthDet;
        $r['max']='AK'; // Max. Celda en LETRA
        return $r;
    }

    public static function runExportSeminarioDetalle($r)
    {
        $rsql= Seminario::runLoadSeminarioDetalle($r);
        $length=array(
            'A'=>28,
        );
        $pos=array(
            5,15,20,20,20,15,15,25,30,
            15,15,15,
            15,15,15,15,15,
            15,15,
            20,20,20,
            15,15,15,20
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabecera=array(
            'N°','IDS','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            'Seminarios','Fecha Seminario','Nro Pago','Monto Pago','Tipo Pago',
            'Sub Total Sem','Total Pagado',
            'Cajero(a)','Teleoperador(a)','Supervisor(a)',
            'Nro Recibo Promoción','Monto Promoción','Empresa','Recogo del Certificado'
        );
        $campos=array(
            '','id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             'seminario','fecha_inicio','nro_pago','monto_pago','tipo_pago',
             'subtotal','total',
             'cajera','marketing','matricula',
             'nro_promocion','monto_promocion','empresa','recogo_certificado'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='Z'; // Max. Celda en LETRA
        return $r;
    }

    public static function runLoadAlumnosInscritos($r)
    {
        DB::statement(DB::raw('SET lc_time_names = \'es_ES\''));
        $sql=DB::table('mat_matriculas AS m')
            ->Join('mat_matriculas_detalles AS md', function($join){
                $join->on('md.matricula_id','=','m.id')
                ->where('md.estado',1);
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','m.sucursal_destino_id');
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','m.persona_id');
            })
            ->Join('mat_programaciones AS pr', function($join){
                $join->on('pr.id','=','md.programacion_id');
            })
            ->Join('mat_cursos AS c', function($join){
                $join->on('c.id','=','pr.curso_id')
                ->where('c.tipo_curso',2)
                ->where('c.empresa_id', Auth::user()->empresa_id);
            })
            ->select(
            DB::raw('UPPER(CONCAT(p.paterno,\' \',p.materno,\' \',p.nombre)) AS persona')
            ,DB::raw('UPPER(c.curso) AS tema'),DB::raw('DATE_FORMAT(pr.fecha_inicio, \'el %d de %M del %Y\') AS fecha_seminario'),
            'm.fecha_matricula AS fecha_inscripcion','s.sucursal AS recogo_certificado')
            ->where('m.estado',1)
            ->where( 
                function($query) use ($r){
                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                        $query->whereBetween('m.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                    }
                }
            );
        $result = $sql->orderBy('persona','asc')->get();

            $sql="  UPDATE mat_matriculas m
                    INNER JOIN mat_matriculas_detalles md ON md.matricula_id= m.id 
                    INNER JOIN personas p ON p.id=m.persona_id
                    INNER JOIN mat_programaciones pr ON pr.id=md.programacion_id
                    INNER JOIN mat_cursos c ON c.id=pr.curso_id
                    SET md.validadescarga= md.validadescarga+1, 
                    md.persona_id_updated_at='".Auth::user()->id."',
                    md.updated_at='".date("Y-m-d H:i:s")."'
                    WHERE m.fecha_matricula BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."'";
            DB::update($sql);
        return $result;
    }

    public static function runExportAlumnosInscritos($r)
    {
        $rsql= Seminario::runLoadAlumnosInscritos($r);
        $min=65;
        $length=array(
            chr($min)=>40,
        );

        $min++; $length[chr($min)]=50;
        $min++; $length[chr($min)]=35;
        $min++; $length[chr($min)]=20;
        $min++; $length[chr($min)]=20;

        $cabecera2=array(
            'Apellidos y Nombre del Pagante','Tema','Fecha del Seminario','Fecha de Inscripción','Recogo del Certificado'
        );

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=chr($min);
        return $r;
    }

    public static function validarDeuda($r)
    {
        $valida = 
        DB::table('mat_matriculas AS mm')
        ->join('mat_matriculas_detalles AS mmd',function($join){
            $join->on('mmd.matricula_id','=','mm.id')
            ->where('mmd.estado',1);
        })
        ->select( 
                DB::raw('  
                IFNULL((SELECT SUM(sal)
                        FROM (
                            SELECT matricula_id, cuota, MIN(saldo) sal
                            FROM mat_matriculas_saldos
                            WHERE estado=1
                            GROUP BY matricula_id,cuota
                            HAVING sal > 0
                        ) cuota_deuda
                        WHERE matricula_id = mm.id),0)
                +
                IFNULL((SELECT SUM(ep.monto_cronograma)
                        FROM mat_especialidades_programaciones_cronogramas ep 
                        INNER JOIN mat_matriculas m ON m.especialidad_programacion_id = ep.especialidad_programacion_id AND m.estado=1
                        LEFT JOIN mat_matriculas_saldos ms ON ms.matricula_id=m.id AND ms.cuota=ep.cuota
                        LEFT JOIN mat_matriculas_cuotas mc ON mc.matricula_id=m.id AND mc.cuota=ep.cuota
                        WHERE ep.estado = 1
                        AND ms.id IS NULL AND mc.id IS NULL 
                        AND ep.fecha_cronograma<= CURDATE()
                        AND m.id = mm.id),0)
                +
                IFNULL((SELECT MIN(saldo) sal
                        FROM mat_matriculas_saldos
                        WHERE estado=1
                        AND matricula_detalle_id= mmd.id
                        GROUP BY matricula_detalle_id
                        HAVING sal > 0 ),0) AS deuda_total ') )
        ->where( 
            function($query) use ($r){
                if( $r->has("matricula_detalle_id") ){
                    $matricula_detalle_id = trim($r->matricula_detalle_id);
                    if( $matricula_detalle_id !='' ){
                        $query->where('mmd.id', '=', $matricula_detalle_id);
                    }
                }
            }
        )
        ->first();
        
        $deuda = 0;
        if( isset($valida->deuda_total) ){
            $deuda = $valida->deuda_total;
        }

        return $deuda;
    }

    public static function runControlPago($r)
    {
        
        $servidor = 'formacioncontinua';
        $aulaservidor = 'aula_formacioncontinua';
        
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $servidor = 'fomacioncontinua_fc';
            $aulaservidor = 'fomacioncontinua_aula';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='inturperufc.com' ){
            $servidor='formacioncontinua_fc_intur';
            $aulaservidor='formacioncontinua_aula_intur';
        }

        $sql= " UPDATE $servidor.mat_matriculas_detalles md
                INNER JOIN $aulaservidor.v_programaciones p ON p.programacion_externo_id=md.id 
                SET md.nota_curso_alum = p.nota_final
                WHERE p.nota_final>0";
        $update = DB::update($sql);


        $id=Auth::user()->id;
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('empresas AS e',function($join){
                $join->on('e.id','=','mc.empresa_id');
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->select('mm.id', 'mmd.id AS matricula_detalle_id', 'p.dni', 'p.nombre', 'p.paterno', 'p.materno'
                    , 'p.celular', 'p.email', 'mmd.archivo_certificado'
                    , 'e.empresa AS empresa_inscripcion', 'mm.fecha_matricula'
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") AS tipo_formacion')
                    , DB::raw( 'IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) AS formacion')
                    ,'mc.curso AS curso', 's.sucursal AS local', 'mp.dia AS frecuencia'
                    , DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) AS horario')
                    , 'mp.turno', DB::raw('DATE(mp.fecha_inicio) AS inicio')
                    , 'mmd.nro_pago_certificado AS nro_pago'
                    , 'mmd.monto_pago_certificado AS monto_pago'
                    , DB::raw(' CASE 
                                    WHEN mmd.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mmd.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mmd.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mmd.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mmd.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago')
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    ,DB::raw('  IFNULL((SELECT GROUP_CONCAT(pago," / ",nro_pago," / ",
                                CASE  WHEN tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END
                                 SEPARATOR "\n") 
                                FROM mat_matriculas_saldos
                                WHERE nro_pago!=""
                                AND estado=1
                                AND matricula_detalle_id= mmd.id),"") AS pagos ')
                    ,DB::raw('  IFNULL((SELECT MIN(saldo) sal
                                FROM mat_matriculas_saldos
                                WHERE estado=1
                                AND matricula_detalle_id= mmd.id
                                GROUP BY matricula_detalle_id
                                HAVING sal > 0 ),0) AS deuda ')
                    ,'mmd.nota_curso_alum AS nota'
                    ,DB::raw('  IFNULL((SELECT GROUP_CONCAT(ep.cuota," / ",ep.fecha_cronograma," / ",ep.monto_cronograma
                                 ORDER BY ep.cuota SEPARATOR "\n") 
                                        FROM mat_especialidades_programaciones_cronogramas ep 
                                        INNER JOIN mat_matriculas m ON m.especialidad_programacion_id = ep.especialidad_programacion_id AND m.estado=1
                                        WHERE ep.estado = 1
                                        AND m.id = mm.id),"") AS programacion_cuota ')
                    ,DB::raw('  IFNULL((SELECT GROUP_CONCAT(mmc.cuota," / ",mmc.monto_cuota," / ",mmc.nro_cuota," / ",
                                b.banco
                                 ORDER BY mmc.cuota SEPARATOR "\n") 
                                FROM mat_matriculas_cuotas mmc 
                                LEFT JOIN mat_matriculas_saldos mms ON mms.matricula_id = mmc.matricula_id AND mms.cuota=mmc.cuota AND mms.estado=1
                                LEFT JOIN bancos b ON b.id_banco = mmc.tipo_pago_cuota 
                                WHERE mmc.estado=1
                                AND mms.id IS NULL
                                AND mmc.matricula_id= mm.id),"") AS pagos2_cuota ')
                    ,DB::raw('  IFNULL((SELECT GROUP_CONCAT(IF(cuota=-1,"Ins.",cuota)," / ",pago," / ",nro_pago," / ",
                                CASE  WHEN tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END
                                 ORDER BY cuota,created_at SEPARATOR "\n") 
                                FROM mat_matriculas_saldos
                                WHERE nro_pago!=""
                                AND estado=1
                                AND matricula_id= mm.id),"") AS pagos_cuota ')
                    ,DB::raw('  IFNULL((SELECT GROUP_CONCAT( IF(cuota=-1,"Ins.", IF(cuota=0,"Mat.",cuota))," / ", sal ORDER BY cuota SEPARATOR "\n" )
                                        FROM (
                                            SELECT matricula_id, cuota, MIN(saldo) sal
                                            FROM mat_matriculas_saldos
                                            WHERE estado=1
                                            GROUP BY matricula_id,cuota
                                            HAVING sal > 0
                                        ) cuota_deuda
                                        WHERE matricula_id = mm.id),0) AS deuda_cuota ')
                    ,DB::raw('  IFNULL((SELECT SUM(sal)
                                        FROM (
                                            SELECT matricula_id, cuota, MIN(saldo) sal
                                            FROM mat_matriculas_saldos
                                            WHERE estado=1
                                            GROUP BY matricula_id,cuota
                                            HAVING sal > 0
                                        ) cuota_deuda
                                        WHERE matricula_id = mm.id),0)
                                +
                                IFNULL((SELECT SUM(ep.monto_cronograma)
                                        FROM mat_especialidades_programaciones_cronogramas ep 
                                        INNER JOIN mat_matriculas m ON m.especialidad_programacion_id = ep.especialidad_programacion_id AND m.estado=1
                                        LEFT JOIN mat_matriculas_saldos ms ON ms.matricula_id=m.id AND ms.cuota=ep.cuota
                                        LEFT JOIN mat_matriculas_cuotas mc ON mc.matricula_id=m.id AND mc.cuota=ep.cuota
                                        WHERE ep.estado = 1
                                        AND ms.id IS NULL AND mc.id IS NULL 
                                        AND ep.fecha_cronograma<= CURDATE()
                                        AND m.id = mm.id),0)
                                +
                                IFNULL((SELECT MIN(saldo) sal
                                FROM mat_matriculas_saldos
                                WHERE estado=1
                                AND matricula_detalle_id= mmd.id
                                GROUP BY matricula_detalle_id
                                HAVING sal > 0 ),0) AS deuda_total ')
                    )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            //$query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("especialidad") OR $r->has('especialidad2') ){
                        if( $r->has('especialidad2') ){
                            $r['especialidad'] = explode(",", trim($r->especialidad2) );
                        }
                        $especialidad = $r->especialidad;
                        if( count($especialidad)>0 AND trim($especialidad[0])!='' ){
                            $query ->whereIn('me.id', $especialidad);
                        }
                    }

                    if( $r->has("curso") OR $r->has('curso2') ){
                        if( $r->has('curso2') ){
                            $r['curso'] = explode(",", trim($r->curso2) );
                        }
                        $curso = $r->curso;
                        if( count($curso)>0 AND trim($curso[0])!='' ){
                            $query ->whereIn('mc.id', $curso);
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $query ->where('p.paterno','like', '%'.$paterno.'%');
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $query ->where('p.materno','like', '%'.$materno.'%');
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $query ->where('p.nombre','like', '%'.$nombre.'%');
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $query ->where('p.dni','like', '%'.$dni.'%');
                        }
                    }

                    if( $r->has('vendedor') AND $r->vendedor==1 ){
                        $persona_id=Auth::user()->id;
                        $query->where('mm.persona_marketing_id',$persona_id);
                    }
                }
            )
            ->where('mm.estado',1);
            
        $result = $sql->orderBy('mm.id','asc')->get();

        return $result;
    }

    public static function runExportControlPago($r)
    {
        $rsql= Seminario::runControlPago($r);
        
        $length=array('A'=>5);
        $pos=array(
            5,15,15,15,15,15,20,
            15,15,15,25,25,
            20,15,15,15,15,
            15,15,15,
            15,15,15,
            15,15,15,
            30,
            15,15,
            30,50,50,30,20
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'DATOS DEL ALUMNO','DATOS DEL CURSO DE FORMACION CONTINUA','PAGO POR CURSO INDEPENDIENTE','PAGO POR CONJUNTO DE CURSOS','PAGO POR INSCRIPCIÓN','PAGO DE SALDOS','DEUDA Y NOTA FINAL DEL CURSO','PROGRAMACION Y PAGO - CUOTAS','PAGO Y DEUDA DE SALDOS - CUOTAS','A LA FECHA'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(5,9,2,2,2,0,1,1,1,0);
        $colorTit=array('#DDEBF7','#E2EFDA','#FCE4D6','#E2EFDA','#FFF2CC','#DDEBF7','#FCE4D6','#DDEBF7','#FCE4D6','#FCE4D6');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°'
            ,'DNI','Nombre','Paterno','Materno','Celular','Email'
            ,'Empresa','Fecha Inscripción','Tipo de Formación Continua','Carrera / Módulo','Inicio / Curso'
            ,'Local','Frecuencia','Horario','Turno','Inicio'
            ,'Nro Pago','Monto Pago','Tipo Pago'
            ,'Nro Recibo PCC','Monto PPC','Tipo Pago'
            ,'Nro Pago Inscripción','Monto Pago Inscripción','Tipo Pago'
            ,'Monto Pago / Nro Pago / Tipo Pago'
            ,'Deuda a la Fecha','Promedio Final del Curso'
            ,'Cuota / Fecha Programada / Monto Programado','Cuota / Monto Pago / Nro Pago / Tipo Pago'
            ,'Cuota / Monto Pago / Nro Pago / Tipo Pago','Cuota / Monto Deuda','Deuda Total'
        );
        $campos=array('');

        $return['data']=$rsql;
        $return['campos']=$campos;
        $return['cabecera']=$cabecera;
        $return['length']=$length;
        $return['cabeceraTit']=$cabeceraTit;
        $return['lengthTit']=$lengthTit;
        $return['colorTit']=$colorTit;
        $return['lengthDet']=$lengthDet;
        $return['max']= 'AG'; //$estatico.chr($min);
        $return['min']=$min; // Max. Celda en LETRA
        
        return $return;
    }

    public static function runEvaluaciones($r)
    {
        
        $servidor = 'formacion_continua';
        $aulaservidor = 'aula_formacion_continua';
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $servidor = 'formacion_continua';
            $aulaservidor = 'aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }

        $sql= " UPDATE $servidor.mat_matriculas_detalles md
                INNER JOIN $aulaservidor.v_programaciones p ON p.programacion_externo_id=md.id 
                SET md.nota_curso_alum = p.nota_final
                WHERE p.nota_final>0";
        $update = DB::update($sql);


        $id=Auth::user()->id;
        $empresa = Auth::user()->empresa_id;

        $sql = "SELECT mm.id,p.dni, p.nombre, p.paterno, p.materno, p.celular, p.email, e.empresa AS empresa_inscripcion, mm.fecha_matricula,
                IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), 'Modular') AS tipo_formacion,
                IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), me.especialidad) AS formacion
                ,mc.curso AS curso, s.sucursal AS local, mp.dia AS frecuencia, mp.turno, mp.fecha_inicio AS inicio
                ,te.tipo_evaluacion, te2.peso_evaluacion, DATE(ev.fecha_examen) AS fecha_evaluacion, ev.nota
                ,mmd.nota_curso_alum AS promedio 

                FROM mat_matriculas AS mm 
                INNER JOIN mat_matriculas_detalles AS mmd ON mmd.matricula_id = mm.id AND mmd.estado = 1 
                INNER JOIN personas AS p ON p.id = mm.persona_id
                INNER JOIN mat_alumnos AS ma ON ma.id = mm.alumno_id 
                INNER JOIN mat_cursos AS mc ON mc.id = mmd.curso_id AND mc.empresa_id = $empresa
                INNER JOIN empresas AS e ON e.id = mc.empresa_id 
                INNER JOIN mat_programaciones AS mp ON mp.id = mmd.programacion_id
                INNER JOIN sucursales AS s ON s.id=mp.sucursal_id 
                LEFT JOIN mat_especialidades AS me ON me.id = mmd.especialidad_id 
                LEFT JOIN (
                    SELECT c.curso_externo_id, te.tipo_evaluacion_externo_id, te.tipo_evaluacion 
                    FROM $aulaservidor.v_unidades_contenido uc 
                    INNER JOIN $aulaservidor.v_cursos c ON c.id=uc.curso_id 
                    INNER JOIN $aulaservidor.v_tipos_evaluaciones te ON FIND_IN_SET(te.id,uc.tipo_evaluacion_id)
                    WHERE uc.estado = 1
                    GROUP BY c.curso_externo_id, te.tipo_evaluacion_externo_id, te.tipo_evaluacion 
                ) te ON te.curso_externo_id= mc.id
                LEFT JOIN empresas_tipos_evaluaciones te2 ON te2.tipo_evaluacion_id = te.tipo_evaluacion_externo_id AND  te2.empresa_id = mc.empresa_id
                LEFT JOIN (
                    SELECT p.programacion_externo_id, e.fecha_examen, e.nota , te.tipo_evaluacion_externo_id
                    FROM $aulaservidor.v_evaluaciones e 
                    INNER JOIN $aulaservidor.v_programaciones p ON p.id=e.programacion_id 
                    INNER JOIN $aulaservidor.v_tipos_evaluaciones te ON te.id = e.tipo_evaluacion_id
                    WHERE e.estado = 1
                    AND e.estado_cambio = 1
                    AND e.fecha_examen IS NOT NULL 
                ) ev ON ev.programacion_externo_id = mmd.id AND ev.tipo_evaluacion_externo_id = te2.tipo_evaluacion_id
                WHERE mm.estado = 1 ";
                    if( $r->has("especialidad") OR $r->has('especialidad2') ){
                        if( $r->has('especialidad2') ){
                            $r['especialidad'] = explode(",", trim($r->especialidad2) );
                        }
                        $especialidad = $r->especialidad;
                        if( count($especialidad)>0 AND trim($especialidad[0])!='' ){
                            $especialidad = implode(",",$especialidad);
                            $sql.=" AND me.id IN ($especialidad)";
                        }
                    }

                    if( $r->has("curso") OR $r->has('curso2') ){
                        if( $r->has('curso2') ){
                            $r['curso'] = explode(",", trim($r->curso2) );
                        }
                        $curso = $r->curso;
                        if( count($curso)>0 AND trim($curso[0])!='' ){
                            $curso = implode(",",$curso);
                            $sql.=" AND mc.id IN ($curso)";
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $sql.=" AND p.paterno LIKE ('%$paterno%')";
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $sql.=" AND p.materno LIKE ('%$materno%')";
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $sql.=" AND p.nombre LIKE ('%$nombre%')";
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $sql.=" AND p.dni LIKE ('%$dni%')";
                        }
                    }

        $result= DB::select($sql);

        return $result;
    }

    public static function runExportEvaluaciones($r)
    {
        $rsql= Seminario::runEvaluaciones($r);
        
        $length=array('A'=>5);
        $pos=array(
            5,15,15,15,15,15,20,
            15,15,15,25,25,
            20,15,15,15,
            15,15,15,15,15
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'DATOS DEL ALUMNO','DATOS DEL CURSO DE FORMACION CONTINUA','NOTA DEL CURSO'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(5,8,4);
        $colorTit=array('#DDEBF7','#E2EFDA','#FFF2CC');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°'
            ,'DNI','Nombre','Paterno','Materno','Celular','Email'
            ,'Empresa','Fecha Inscripción','Tipo de Formación Continua','Nombre del Módulo','Formación Continua'
            ,'Local','Frecuencia','Turno','Inicio'
            ,'Tipo de Evaluación','Peso Evaluación','Fecha de Ejecución','Nota','Promedio Final'
        );
        $campos=array('');

        $return['data']=$rsql;
        $return['campos']=$campos;
        $return['cabecera']=$cabecera;
        $return['length']=$length;
        $return['cabeceraTit']=$cabeceraTit;
        $return['lengthTit']=$lengthTit;
        $return['colorTit']=$colorTit;
        $return['lengthDet']=$lengthDet;
        $return['max']= 'U'; //$estatico.chr($min);
        $return['min']=$min; // Max. Celda en LETRA
        
        return $return;
    }

    public static function runRegistroNota($r)
    {
        
        $servidor = 'formacion_continua';
        $aulaservidor = 'aula_formacion_continua';
        
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $servidor = 'fomacioncontinua_fc';
            $aulaservidor = 'fomacioncontinua_aula';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='inturperufc.com' ){
            $servidor='formacioncontinua_fc_intur';
            $aulaservidor='formacioncontinua_aula_intur';
        }

        $sql= " UPDATE $servidor.mat_matriculas_detalles md
                INNER JOIN $aulaservidor.v_programaciones p ON p.programacion_externo_id=md.id 
                SET md.nota_curso_alum = p.nota_final
                WHERE p.nota_final>0";
        $update = DB::update($sql);


        $id=Auth::user()->id;
        $empresa = Auth::user()->empresa_id;
        $curso_id = $r->curso;
        if( $r->has('curso2') ){
            $curso_id = $r->curso2;
        }

        $sql = "SELECT c.curso_externo_id, te.tipo_evaluacion_externo_id, te.tipo_evaluacion 
                FROM $aulaservidor.v_unidades_contenido uc 
                INNER JOIN $aulaservidor.v_cursos c ON c.id=uc.curso_id 
                INNER JOIN $aulaservidor.v_tipos_evaluaciones te ON FIND_IN_SET(te.id,uc.tipo_evaluacion_id)
                WHERE uc.estado = 1 AND c.curso_externo_id = $curso_id
                GROUP BY c.curso_externo_id, te.tipo_evaluacion_externo_id, te.tipo_evaluacion
                ORDER BY uc.id";
        $cursos = DB::select($sql);

        $notas = "";
        $lefts = "";

        foreach ($cursos as $key => $value) {
            $notas.=",SUM( IF( rn.tipo_evaluacion_externo_id = $value->tipo_evaluacion_externo_id, rn.nota, 0 ) ) n".($key+1);
        }

        $sql = "SELECT mm.id,p.dni, p.nombre, p.paterno, p.materno, p.celular, p.email, e.empresa AS empresa_inscripcion, mm.fecha_matricula,
                IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), 'Modular') AS tipo_formacion,
                IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), me.especialidad) AS formacion
                ,mc.curso AS curso, s.sucursal AS local, mp.dia AS frecuencia
                , CONCAT(TIME(mp.fecha_inicio),' - ',TIME(mp.fecha_final)) horario
                , mp.turno, DATE(mp.fecha_inicio) AS inicio
                $notas
                ,mmd.nota_curso_alum AS promedio 

                FROM mat_matriculas AS mm 
                INNER JOIN mat_matriculas_detalles AS mmd ON mmd.matricula_id = mm.id AND mmd.estado = 1 
                INNER JOIN personas AS p ON p.id = mm.persona_id
                INNER JOIN mat_alumnos AS ma ON ma.id = mm.alumno_id 
                INNER JOIN mat_cursos AS mc ON mc.id = mmd.curso_id AND mc.empresa_id = $empresa
                INNER JOIN empresas AS e ON e.id = mc.empresa_id 
                INNER JOIN mat_programaciones AS mp ON mp.id = mmd.programacion_id
                INNER JOIN sucursales AS s ON s.id = mp.sucursal_id
                LEFT JOIN mat_especialidades AS me ON me.id = mmd.especialidad_id 
                LEFT JOIN (
                    SELECT p.programacion_externo_id, e.fecha_examen, e.nota , te.tipo_evaluacion_externo_id 
                    FROM $aulaservidor.v_evaluaciones e 
                    INNER JOIN $aulaservidor.v_programaciones p ON p.id=e.programacion_id 
                    INNER JOIN $aulaservidor.v_programaciones_unicas pu ON pu.id=p.programacion_unica_id 
                    INNER JOIN $aulaservidor.v_cursos c ON c.id = pu.curso_id AND c.curso_externo_id = $curso_id 
                    INNER JOIN $aulaservidor.v_tipos_evaluaciones te ON te.id = e.tipo_evaluacion_id 
                    WHERE e.estado = 1 AND e.estado_cambio = 1 AND e.fecha_examen IS NOT NULL 
                ) rn ON rn.programacion_externo_id = mmd.id 
                WHERE mm.estado = 1 
                AND mc.id = $curso_id";
                    

                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $sql.=" AND p.paterno LIKE ('%$paterno%')";
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $sql.=" AND p.materno LIKE ('%$materno%')";
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $sql.=" AND p.nombre LIKE ('%$nombre%')";
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $sql.=" AND p.dni LIKE ('%$dni%')";
                        }
                    }

                $sql.=" GROUP BY mm.id,p.dni, p.nombre, p.paterno, p.materno, p.celular, p.email, e.empresa, mm.fecha_matricula, mmd.especialidad_id, mc.tipo_curso, mc.curso, mmd.nota_curso_alum, me.especialidad, mp.dia, mp.turno, mp.fecha_inicio, mp.fecha_final, s.sucursal";

        $result= DB::select($sql);

        return array($result,$cursos);
    }

    public static function runExportRegistroNota($r)
    {
        $rsql= Seminario::runRegistroNota($r);
        $datos = $rsql[0];
        $cursos = $rsql[1];
        
        $length=array('A'=>5);
        $pos=array(
            5,15,15,15,15,15,20,
            15,15,15,25,25,
            20,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'DATOS DEL ALUMNO','DATOS DEL CURSO DE FORMACION CONTINUA','NOTA DEL CURSO'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(5,9,count($cursos));
        $colorTit=array('#DDEBF7','#E2EFDA','#FFF2CC');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°'
            ,'DNI','Nombre','Paterno','Materno','Celular','Email'
            ,'Empresa','Fecha Inscripción','Tipo de Formación Continua','Carrera / Módulo','Inicio / Curso'
            ,'Local','Frecuencia','Horario','Turno','Inicio');

        foreach ($cursos as $key => $value) {
            array_push($cabecera, 'Nota '.($key+1));
        }
        array_push($cabecera, 'Promedio Final');
        
        $campos=array('');

        $return['data']=$datos;
        $return['campos']=$campos;
        $return['cabecera']=$cabecera;
        $return['length']=$length;
        $return['cabeceraTit']=$cabeceraTit;
        $return['lengthTit']=$lengthTit;
        $return['colorTit']=$colorTit;
        $return['lengthDet']=$lengthDet;
        $return['max']= 'X'; //$estatico.chr($min);
        $return['min']=$min; // Max. Celda en LETRA
        
        return $return;
    }

    public static function runAsesoria($r)
    {
        
        $servidor = 'formacion_continua';
        $aulaservidor = 'aula_formacion_continua';
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $servidor = 'formacion_continua';
            $aulaservidor = 'aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }

        $sql= " UPDATE $servidor.mat_matriculas_detalles md
                INNER JOIN $aulaservidor.v_programaciones p ON p.programacion_externo_id=md.id 
                SET md.nota_curso_alum = p.nota_final
                WHERE p.nota_final>0";
        $update = DB::update($sql);

        $id=Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $especialidad_id = $r->especialidad_id;

        $sql = "SELECT ce.curso_id, c.curso_apocope AS codigo, c.curso, c.credito, c.hora
                FROM mat_cursos_especialidades ce
                INNER JOIN mat_cursos c ON c.id=ce.curso_id AND c.estado=1
                WHERE ce.especialidad_id = $especialidad_id
                AND ce.estado = 1";
        $cursos = DB::select($sql);

        $curso = "";
        $left = "";

        foreach ($cursos as $key => $value) {
            $curso.=", COUNT(DISTINCT(ce".($key+1).".id)) c".($key+1);
            $curso.=", IF( MAX(IF(mmd.curso_id = ".$value->curso_id.",mmd.nota_curso_alum,0))>=MAX(e.nota_minima),1,0) nf".($key+1);
            $left.="
            LEFT JOIN mat_cursos_especialidades AS ce".($key+1)." ON ce".($key+1).".curso_id=mp.curso_id AND ce".($key+1).".especialidad_id = $especialidad_id AND ce".($key+1).".curso_id=".$value->curso_id;
        }

        $sql = "SELECT p.id, p.dni, p.nombre, p.paterno, p.materno, p.celular, p.email
                , COUNT(DISTINCT(mc.id)) ncursos, COUNT(DISTINCT(ce.id)) nrelacion
                $curso
                FROM mat_matriculas AS mm 
                INNER JOIN mat_matriculas_detalles AS mmd ON mmd.matricula_id = mm.id AND mmd.estado = 1 
                INNER JOIN personas AS p ON p.id = mm.persona_id
                INNER JOIN mat_cursos AS mc ON mc.id = mmd.curso_id AND mc.empresa_id = $empresa_id
                INNER JOIN empresas AS e ON e.id = mc.empresa_id 
                INNER JOIN mat_programaciones AS mp ON mp.id = mmd.programacion_id 
                INNER JOIN sucursales AS s ON s.id = mp.sucursal_id
                LEFT JOIN mat_especialidades AS me ON me.id = mmd.especialidad_id 
                LEFT JOIN mat_cursos_especialidades AS ce ON ce.curso_id=mp.curso_id AND ce.especialidad_id = $especialidad_id
                $left
                WHERE mm.estado = 1 ";
                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $sql.=" AND p.paterno LIKE ('%$paterno%')";
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $sql.=" AND p.materno LIKE ('%$materno%')";
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $sql.=" AND p.nombre LIKE ('%$nombre%')";
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $sql.=" AND p.dni LIKE ('%$dni%')";
                        }
                    }
                $sql.="
                GROUP BY p.id, p.dni, p.nombre, p.paterno, p.materno, p.celular, p.email
                HAVING nrelacion>0
                ORDER BY nrelacion DESC";

        $result= DB::select($sql);

        return array($result,$cursos);
    }

    public static function runExportAsesoria($r)
    {
        $rsql= Seminario::runAsesoria($r);
        $datos = $rsql[0];
        $cursos = $rsql[1];
        
        $length=array('A'=>5);
        $pos=array(
            5,15,15,15,15,15,20,
            15,15,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'DATOS DEL ALUMNO','CURSOS'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(5,count($cursos)+1);
        $colorTit=array('#DDEBF7','#FFF2CC');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°'
            ,'DNI','Nombre','Paterno','Materno','Celular','Email'
            ,'Total Cursos','Total Relación');

        foreach ($cursos as $key => $value) {
            array_push($cabecera, 'C'.($key+1));
        }
        
        $campos=array('');

        $return['data']=$datos;
        $return['cursos']=$cursos;
        $return['campos']=$campos;
        $return['cabecera']=$cabecera;
        $return['length']=$length;
        $return['cabeceraTit']=$cabeceraTit;
        $return['lengthTit']=$lengthTit;
        $return['colorTit']=$colorTit;
        $return['lengthDet']=$lengthDet;
        $return['max']= 'X'; //$estatico.chr($min);
        $return['min']=$min; // Max. Celda en LETRA
        
        return $return;
    }

    public static function runPagos($r)
    {
        
        $servidor = 'formacion_continua';
        $aulaservidor = 'aula_formacion_continua';
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $servidor = 'formacion_continua';
            $aulaservidor = 'aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }
        elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
            $servidor = 'capa_formacion_continua';
            $aulaservidor = 'capa_aula_formacion_continua';
        }


        $id=Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $sql="SELECT `mm`.`id` matricula_id, mmd.id matricula_detalle_id, IFNULL(cp.cuota,'') cuota, `p`.`dni`, `p`.`nombre`, `p`.`paterno`, `p`.`materno`, `p`.`celular`, `p`.`email`, `e`.`empresa` AS `empresa_inscripcion`, `mm`.`fecha_matricula`, 
            IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), 'Modular') AS tipo_formacion, 
            IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), me.especialidad) AS formacion, 
            `mc`.`curso` AS `curso`, `s`.`sucursal` AS `local`, `mp`.`dia` AS `frecuencia`, `mp`.`turno`, `mp`.`fecha_inicio` AS `inicio`,
            ms.saldo,
            IF(cd.cuota=-1,'Inscripción',CONCAT('Cuota # ',cd.cuota) ) cuotacd, cd.salcd,
            CONCAT('Cuota # ',cp.cuota) cuota_cronograma, cp.fecha_cronograma, cp.monto_cronograma,
            si.salsi, si.salsi_id, cd.salcd_id,
            sm.salsm, sm.salsm_id
            FROM `mat_matriculas` AS `mm` 
            INNER JOIN mat_especialidades_programaciones ep on ep.id = mm.especialidad_programacion_id
            INNER JOIN `mat_matriculas_detalles` AS `mmd` ON `mmd`.`matricula_id` = `mm`.`id` 
            AND (mmd.id in (
                    SELECT MAX(md2.id)
                    FROM mat_matriculas m2 
                    INNER JOIN mat_matriculas_detalles md2 on md2.matricula_id = m2.id 
                    INNER JOIN mat_especialidades_programaciones ep2 on ep2.id = m2.especialidad_programacion_id and ep2.tipo=1
                    GROUP BY m2.id
            ) or ep.tipo = 2)
            INNER JOIN `personas` AS `p` ON `p`.`id` = `mm`.`persona_id` 
            INNER JOIN `mat_alumnos` AS `ma` ON `ma`.`id` = `mm`.`alumno_id` 
            INNER JOIN `mat_cursos` AS `mc` ON `mc`.`id` = `mmd`.`curso_id` AND `mc`.`empresa_id` = $empresa_id 
            INNER JOIN `empresas` AS `e` ON `e`.`id` = `mc`.`empresa_id` 
            LEFT JOIN `mat_programaciones` AS `mp` ON `mp`.`id` = `mmd`.`programacion_id` 
            LEFT JOIN `sucursales` AS `s` ON `s`.`id` = `mp`.`sucursal_id` 
            LEFT JOIN `mat_especialidades` AS `me` ON `me`.`id` = `mmd`.`especialidad_id` 
            LEFT JOIN (
            SELECT matricula_detalle_id, MIN(saldo) saldo
            FROM mat_matriculas_saldos
            WHERE estado=1
            GROUP BY matricula_detalle_id
            HAVING saldo > 0
            ) ms ON ms.matricula_detalle_id = mmd.id
            LEFT JOIN (
            SELECT m.id matricula_id, ep.cuota, ep.fecha_cronograma, ep.monto_cronograma
            FROM mat_especialidades_programaciones_cronogramas ep
            INNER JOIN mat_matriculas m ON m.especialidad_programacion_id = ep.especialidad_programacion_id 
            LEFT JOIN mat_matriculas_cuotas mmc ON mmc.matricula_id=m.id AND mmc.cuota=ep.cuota AND mmc.estado=1
            LEFT JOIN mat_matriculas_saldos mms ON mms.matricula_id=m.id AND mms.cuota=ep.cuota AND mms.estado=1 
            WHERE ep.estado = 1
            GROUP BY m.id, ep.cuota, ep.fecha_cronograma, ep.monto_cronograma
            HAVING IF((SUM(mms.pago)>0 AND MIN(mms.saldo)=0) OR (MIN(mmc.monto_cuota)>=ep.monto_cronograma),FALSE,TRUE)
            ) cp ON cp.matricula_id = mm.id 
            LEFT JOIN (
            SELECT matricula_id, cuota, MIN(saldo) salcd, MAX(id) salcd_id
            FROM mat_matriculas_saldos
            WHERE estado=1
            GROUP BY matricula_id,cuota
            HAVING salcd > 0
            ) cd ON cd.matricula_id = mm.id AND cd.cuota=cp.cuota
            LEFT JOIN (
            SELECT matricula_id, cuota, MIN(saldo) salsi, MAX(id) salsi_id
            FROM mat_matriculas_saldos
            WHERE estado=1
            AND cuota='-1'
            GROUP BY matricula_id,cuota
            HAVING salsi > 0
            ) si ON si.matricula_id = mm.id
            LEFT JOIN (
            SELECT matricula_id, cuota, MIN(saldo) salsm, MAX(id) salsm_id
            FROM mat_matriculas_saldos
            WHERE estado=1
            AND cuota='0'
            GROUP BY matricula_id,cuota
            HAVING salsm > 0
            ) sm ON sm.matricula_id = mm.id
            WHERE e.estado = 1
            ";
                    if( $r->has("matricula_id") AND trim($r->matricula_id) != '' ){
                        $sql.=" AND mm.id = $r->matricula_id";
                    }
                    else{
                        $sql.=" AND mm.estado = 1 AND mmd.estado = 1";
                    }

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $sql.=" AND mm.fecha_matricula BETWEEN '$r->fecha_inicial' AND '$r->fecha_final' ";
                        }
                    }

                    if( $r->has("especialidad") OR $r->has('especialidad2') ){
                        if( $r->has('especialidad2') ){
                            $r['especialidad'] = explode(",", trim($r->especialidad2) );
                        }
                        $especialidad = $r->especialidad;
                        if( count($especialidad)>0 AND trim($especialidad[0])!='' ){
                            $especialidad = implode(',',$r->especialidad);
                            $sql.=" AND me.id IN ($especialidad)";
                        }
                    }

                    if( $r->has("curso") OR $r->has('curso2') ){
                        if( $r->has('curso2') ){
                            $r['curso'] = explode(",", trim($r->curso2) );
                        }
                        $curso = $r->curso;
                        if( count($curso)>0 AND trim($curso[0])!='' ){
                            $curso = implode(',',$r->curso);
                            $sql.=" AND mc.id IN ($curso)";
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $sql.= " AND p.paterno LIKE '%".$paterno."%'";
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $sql.= " AND p.materno LIKE '%".$materno."%'";
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $sql.= " AND p.nombre LIKE '%".$nombre."%'";
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $sql.= " AND p.dni LIKE '%".$dni."%'";
                        }
                    }

        $sql.=" ORDER BY mm.id,cuota ASC";
            
        $result = DB::select($sql);

        return $result;
    }

    public static function runLoadFicha($r)
    {
        $id=Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $sql = "SELECT m.id, p.dni, m.fecha_matricula fecha
                ,IF( md.especialidad_id IS NULL, IF( md.tipo_curso=2, 'Seminario', 'Curso Libre' ), es.especialidad) AS formacion
                ,md.cursos ,'' mod_ingreso
                ,p.paterno, p.materno, p.nombre
                ,CASE  
                    WHEN p.estado_civil='S' THEN 'Soltero'
                    WHEN p.estado_civil='C' THEN 'Casado'
                    WHEN p.estado_civil='V' THEN 'Viudo'
                    WHEN p.estado_civil='D' THEN 'Divorciado'
                END estado_civil, p.dni tipo_doc, p.fecha_nacimiento
                ,YEAR(CURDATE())-YEAR(p.fecha_nacimiento) + 
                IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nacimiento,'%m-%d'), 0 , -1 ) edad
                ,CASE  
                    WHEN p.sexo='M' THEN 'Masculino'
                    WHEN p.sexo='F' THEN 'Femenino'
                END sexo, pa.pais, re.region, pr.provincia, di.distrito
                ,p.email, p.celular, p.telefono, p.direccion_dir, p.referencia_dir
                ,re2.region region_dir, pr2.provincia provincia_dir, di2.distrito distrito_dir
                ,co.colegio, re3.region region_col, pr3.provincia provincia_col, di3.distrito distrito_col
                ,IF(co.tipo=1, 'Nacional', 'Particular') tipo_colegio
                ,IF( md.especialidad_id IS NULL, IF( md.tipo_curso=2, 'Seminario', 'Curso Libre' ), es.especialidad) AS carrera
                ,su3.sucursal local_estudio , DATE(pro.fecha_inicio) fecha_inicio, pro.dia frecuencia
                ,CONCAT(DATE_FORMAT(pro.fecha_inicio,'%H:%i'),' - ', DATE_FORMAT(pro.fecha_final,'%H:%i')) horario
                ,'' semestre, '' cop_dni, '' fotos, '' cert_est, '' ddjj, '' seguro
                ,ep.costo inscripcion, ep.costo_mat matricula, SUBSTRING_INDEX(ep.nro_cuota,'-',1) escala, SUBSTRING_INDEX(ep.nro_cuota,'-',-1) pension 
                ,'INSCRIPCIÓN', b1.banco, m.nro_pago_inscripcion, m.fecha_matricula fecha_pago_inscripcion, m.monto_pago_inscripcion
                ,'MATRÍCULA'
                    ,b5.banco tipo_mat,m.nro_pago nro_mat,m.fecha_matricula fecha_mat,m.monto_pago importe_mat
                ,'PENSIÓN'
                    ,IF(m.monto_promocion>0, b2.banco,
                        IF(SUBSTRING_INDEX(md.id,'|',1)*1>0,b3.banco,b4.banco)
                    ) tipo_pen
                    ,IF(m.monto_promocion>0, m.nro_promocion,
                        IF(SUBSTRING_INDEX(md.id,'|',1)*1>0,md2.nro_pago_certificado,mc.nro_cuota)
                    ) nro_pen
                    ,IF(m.monto_promocion>0, m.fecha_matricula,
                        IF(SUBSTRING_INDEX(md.id,'|',1)*1>0,DATE(md2.created_at),DATE(mc.created_at))
                    ) fecha_pen
                    ,IF(m.monto_promocion>0, m.monto_promocion,
                        IF(SUBSTRING_INDEX(md.id,'|',1)*1>0,md2.monto_pago_certificado,mc.monto_cuota)
                    ) importe_pen
                ,m.observacion promocion, su.sucursal ode_inscripcion, su3.sucursal ode_estudio, su2.sucursal ode_recogo
                ,tp.tipo_participante, pu.medio_publicitario, '' med_cap, ta.tarea tipo_vendedor
                ,CONCAT(p2.paterno,' ',p2.materno,' ',p2.nombre,' (COD:',tra.codigo,')') vendedor
                ,CONCAT(p2.paterno,' ',p2.materno,' ',p2.nombre) res_matricula
                FROM mat_matriculas m 
                INNER JOIN personas p ON p.id=m.persona_id 
                INNER JOIN (
                    SELECT md_aux.matricula_id, MIN(md_aux.especialidad_id) especialidad_id
                    , MIN(c_aux.tipo_curso) tipo_curso, MIN(md_aux.programacion_id) programacion_id
                    , MIN(c_aux.empresa_id) empresa_id, MAX( CONCAT(md_aux.monto_pago_certificado,'|',md_aux.id) ) id
                    , GROUP_CONCAT( c_aux.curso ORDER BY c_aux.curso SEPARATOR ' / ') cursos
                    FROM mat_matriculas_detalles md_aux
                    INNER JOIN mat_cursos c_aux ON c_aux.id=md_aux.curso_id
                    WHERE md_aux.programacion_id IS NOT NULL
                    GROUP BY md_aux.matricula_id
                ) md ON md.matricula_id = m.id AND md.empresa_id = $empresa_id
                INNER JOIN mat_programaciones pro ON pro.id=md.programacion_id
                INNER JOIN sucursales su3 ON su3.id=pro.sucursal_id
                LEFT JOIN mat_matriculas_detalles md2 ON md2.id = SUBSTRING_INDEX(md.id,'|',-1)*1
                LEFT JOIN mat_trabajadores tra ON tra.persona_id=m.persona_marketing_id AND tra.rol_id=1 AND tra.empresa_id=md.empresa_id
                LEFT JOIN mat_especialidades_programaciones ep ON ep.id=m.especialidad_programacion_id
                LEFT JOIN mat_tareas ta ON ta.id=tra.tarea_id
                LEFT JOIN personas p2 ON p2.id=m.persona_marketing_id
                LEFT JOIN personas p3 ON p3.id=m.persona_matricula_id
                LEFT JOIN mat_tipos_participantes tp ON tp.id=m.tipo_participante_id
                LEFT JOIN medios_publicitarios pu ON pu.id=p.medio_publicitario_id
                LEFT JOIN sucursales su ON su.id=m.sucursal_id
                LEFT JOIN sucursales su2 ON su2.id=m.sucursal_destino_id
                LEFT JOIN mat_especialidades es ON es.id=md.especialidad_id
                LEFT JOIN paises pa ON pa.id=p.pais_id 
                LEFT JOIN mat_ubicacion_region re ON re.id=p.region_id
                LEFT JOIN mat_ubicacion_provincia pr ON pr.id=p.provincia_id
                LEFT JOIN mat_ubicacion_distrito di ON di.id=p.distrito_id
                LEFT JOIN mat_ubicacion_region re2 ON re2.id=p.region_id_dir
                LEFT JOIN mat_ubicacion_provincia pr2 ON pr2.id=p.provincia_id_dir
                LEFT JOIN mat_ubicacion_distrito di2 ON di2.id=p.distrito_id_dir
                LEFT JOIN colegios co ON co.id=p.colegio_id
                LEFT JOIN mat_ubicacion_distrito di3 ON di3.id=co.distrito_id
                LEFT JOIN mat_ubicacion_provincia pr3 ON pr3.id=di3.provincia_id
                LEFT JOIN mat_ubicacion_region re3 ON re3.id=pr3.region_id
                LEFT JOIN bancos b1 ON b1.id_banco=m.tipo_pago_inscripcion
                LEFT JOIN bancos b2 ON b2.id_banco=m.tipo_pago
                LEFT JOIN bancos b3 ON b3.id_banco=md2.tipo_pago
                LEFT JOIN mat_matriculas_cuotas mc ON mc.matricula_id=m.id AND mc.cuota=1 AND mc.estado=1
                LEFT JOIN bancos b4 ON b4.id_banco=mc.tipo_pago_cuota
                LEFT JOIN bancos b5 ON b5.id_banco=m.tipo_pago_matricula
                WHERE m.estado=1 ";

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $sql.=" AND m.fecha_matricula BETWEEN '$inicial' AND '$final' ";
                        }
                    }

                    if( $r->has("matricula_id") ){
                        $matricula_id=trim($r->matricula_id);
                        if( $matricula_id !=''){
                            $sql.=" AND m.id = '$matricula_id' ";
                        }
                    }

        $result= DB::select($sql);

        return $result;
    }

    public static function runExportFicha($r)
    {
        $rsql= Seminario::runLoadFicha($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,20,20,20,15,15,25,30,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15,
            15,15,15,15,15,15,
            20,20,20,
            15,15,15,20
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array();

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array();
        $colorTit=array();
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min++;
                $estaticoFin= chr($min);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min++;
                    $estatico= chr($min);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°','IDENTIFICADOR','FECHA DE INSCRIPCIÓN','ESTUDIOS MÓDULOS','ESTUDIOS CURSOS','MOD. DE INGRESO'
            ,'APEL. PATERNO','APEL. MATERNO','NOMBRES','EST.CIVIL','DOCUMENTO TIPO / N°','FECHA DE NACIMIENTO','EDAD','SEXO'
            ,'PAIS','REGION','PROVINCIA','DISTRITO'
            ,'CORREO ELECTRÓNICO','CELULAR','TELF. EMERGENCIA','DIRECCIÓN AV / JR / CALLE / PJE - N° /MZ / LOTE / INT / DPTO','REFERENCIA','REGIÓN','PROVINCIA','DISTRITO'
            ,'NOMBRE DEL COLEGIO','REGIÓN','PROVINCIA','DISTRITO','TIPO DE COLEGIO'
            ,'CARRERA / MODULO','LOC . ESTUDIO','FECHA DE INICIO','FRECUENCIA','HORARIO','SEMESTRE'
            ,'COP. DNI','FOTOS','CERT. ESTUDIOS','DDDJJ DE BUENA CONDUCTA','SEGURO CONTRA ACCIDENTES'
            ,'INSCRIP.','MATRÍCULA','ESCALA','PENSIÓN'
            ,'CONC. DE PAGO I','MEDIO DE PAGO I','DOC. DE PAGO I','FECHA DE PAGO I','IMPORTE I'
            ,'CONC. DE PAGO M','MEDIO DE PAGO M','DOC. DE PAGO M','FECHA DE PAGO M','IMPORTE M'
            ,'CONC. DE PAGO P','MEDIO DE PAGO P','DOC. DE PAGO P','FECHA DE PAGO P','IMPORTE P'
            ,'PROMOCIÓN','ODE DE INSCRIPCIÓN','ODE DONDE ESTUDIARÁ','ODE DONDE RECOJERÁ SUS CERTIFICADOS'
            ,'TIP. DE PARTICIPANTE','SOLIC. INFORM.','MED. CAPTAC.','TIPO DE VEND.','APELLIDOS Y NOMBRES DE PROMOTOR (COD)','RESPONSABLE DE MATRÍCULA'
        );
        $campos=array('');

        $r['data']=$rsql;
        $r['campos']=$campos;
        $r['cabecera']=$cabecera;
        $r['length']=$length;
        $r['cabeceraTit']=$cabeceraTit;
        $r['lengthTit']=$lengthTit;
        $r['colorTit']=$colorTit;
        $r['lengthDet']=$lengthDet;
        $r['max']='BS'; // Max. Celda en LETRA
        return $r;
    }
}
