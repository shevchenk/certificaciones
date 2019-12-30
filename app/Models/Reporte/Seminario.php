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
            ->join('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');
            })
            ->join('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');
            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');
            })
            ->leftJoin('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mep.especialidad_id');
            })
            ->leftJoin('sucursales AS s3',function($join){
                $join->on('s3.id','=','mp.sucursal_id');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula',DB::raw('GROUP_CONCAT( DISTINCT(s3.sucursal) ) AS lugar_estudio'),'e.empresa AS empresa_inscripcion'
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NULL, 
                                GROUP_CONCAT( DISTINCT( IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ) )),
                                "Especialidad"
                                ) AS tipo_formacion ')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                GROUP_CONCAT( DISTINCT(me.especialidad) ),
                                GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") 
                                ) AS formacion')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                GROUP_CONCAT( DISTINCT(mep.fecha_inicio) ),
                                GROUP_CONCAT( mp.fecha_inicio ORDER BY mmd.id SEPARATOR "\n")
                                ) AS fecha_inicio')
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
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END ORDER BY mmd.id SEPARATOR "\n")
                                ) AS tipo_pago')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    //,DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total')
                    ,'s.sucursal','s2.sucursal AS recogo_certificado'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre))) as cajera')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre))) as marketing')
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
                    ,'mm.especialidad_programacion_id'
                    ,'s.sucursal','s2.sucursal','mm.nro_promocion','mm.monto_promocion'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion');
            
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
        $rsql= Seminario::runLoadSeminario($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,20,20,20,15,15,25,30,
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

        $cabeceraTit=array(
            'DATOS','DATOS DEL INSCRITO','SOBRE LA FORMACIÓN CONTINUA','PAGO POR CURSO','PAGO POR CONJUNTO DE CURSOS','PAGO POR INSCRIPCIÓN','DATOS DE LA VENTA'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(1,6,6,3,2,2,4);
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
            ,'Validó Email?','Fecha Inscripción','Donde Estudiará','Empresa','Tipo de Formación Continua','Formación Continua','Fecha a Realizarse'
            ,'Nro Pago','Monto Pago','Tipo Pago','Total Pagado'
            ,'Nro Recibo PCC','Monto PPC','Tipo Pago PPC','Nro Pago Inscripción','Monto Pago Inscripción','Tipo Pago Inscripción'
            ,'Sede De Inscripción','Recogo del Certificado','Cajero(a)','Vendedor(a)','Supervisor(a)'
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
        $r['max']=$estatico.chr($min); // Max. Celda en LETRA
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

    public static function runControlPago($r)
    {
        
        $servidor = 'telesup_pae';
        $aulaservidor = 'telesup_aula';
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
            ->leftJoin('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mep.especialidad_id');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email'
                    ,'mm.fecha_matricula','e.empresa AS empresa_inscripcion'
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NULL, 
                                GROUP_CONCAT( DISTINCT( IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ) )),
                                "Especialidad"
                                ) AS tipo_formacion ')
                    ,DB::raw(' IF(mm.especialidad_programacion_id IS NOT NULL, 
                                GROUP_CONCAT( DISTINCT(me.especialidad) ),
                                GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") 
                                ) AS formacion')
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
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END ORDER BY mmd.id SEPARATOR "\n")
                                ) AS tipo_pago')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    ,DB::raw('  GROUP_CONCAT( 
                                IFNULL((SELECT SUM(saldo)
                                FROM mat_matriculas_saldos
                                WHERE nro_pago=""
                                AND saldo>0
                                AND estado=1
                                AND matricula_detalle_id= mmd.id),0)
                                ORDER BY mmd.id SEPARATOR "\n") AS deuda ')
                    ,DB::raw(' GROUP_CONCAT( mmd.nota_curso_alum ORDER BY mmd.id SEPARATOR "\n") AS nota')
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
            ->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email'
                    ,'mm.fecha_matricula','e.empresa'
                    ,'mm.especialidad_programacion_id'
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion');
            
        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }
}
