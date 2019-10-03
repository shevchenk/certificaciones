<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Reporte extends Model
{
    protected   $table = 'mat_promocion';

    public static function CalcularAsignados( $r )
    {
        $fecha_ini= $r->fecha_ini;
        $fecha_fin= $r->fecha_fin;
        $empresa_id= $r->empresas;

        $sql="  
        SELECT e.id, 
        e.empresa, DATE(pc.created_at) fecha_carga, MIN(pc.fecha_registro) fmin, MAX(pc.fecha_registro) fmax,  
        pc.ad_name, pc.interesado AS interes, COUNT(pc.id) cantidad, MIN(pc.costo) costo_min , SUM(pc.costo) total,
        COUNT(IF(d.persona_id IS NOT NULL, 1, NULL)) si_asignado, COUNT(IF(d.persona_id IS NULL, 1, NULL)) no_asignado, 
        COUNT(IF(d.persona_id IS NOT NULL AND l.persona_id IS NULL, 1, NULL)) no_llamada, 
        COUNT(IF(d.persona_id IS NOT NULL AND l.persona_id IS NOT NULL, 1, NULL)) si_llamada, 
        COUNT(IF(m.persona_id IS NULL, NULL, 1)) convertido,
        COUNT(IF(d.persona_id IS NOT NULL AND l.obs=1, 1, NULL)) interesado, 
        COUNT(IF(d.persona_id IS NOT NULL AND l.obs=2, 1, NULL)) pendiente,
        COUNT(IF(d.persona_id IS NOT NULL AND l.obs=3, 1, NULL)) nointeresado, 
        COUNT(IF(d.persona_id IS NOT NULL AND l.obs=0, 1, NULL)) otros
        FROM personas_captadas pc 
        INNER JOIN empresas e ON e.id=pc.empresa_id AND e.id = $empresa_id
        LEFT JOIN (
            SELECT ll.persona_id, t.empresa_id, MIN(tll.obs) obs
            FROM llamadas ll
            INNER JOIN tipo_llamadas tll ON tll.id=ll.tipo_llamada_id
            INNER JOIN mat_trabajadores t ON t.id=ll.trabajador_id
            WHERE DATE(ll.fecha_llamada)>='$fecha_ini'
            AND ll.ultimo_registro=1
            AND ll.estado=1
            GROUP BY ll.persona_id, t.empresa_id
        ) l ON l.persona_id=pc.persona_id AND l.empresa_id=pc.empresa_id 
        LEFT JOIN (
            SELECT mm.persona_id, mc.empresa_id 
            FROM mat_matriculas mm
            INNER JOIN mat_matriculas_detalles mmd ON mmd.matricula_id=mm.id 
            INNER JOIN mat_cursos mc ON mc.id=mmd.curso_id
            WHERE mm.fecha_matricula>='$fecha_ini'
            AND mm.estado=1
            GROUP BY mm.persona_id, mc.empresa_id
        ) m ON m.persona_id=pc.persona_id AND m.empresa_id=pc.empresa_id 
        LEFT JOIN (
            SELECT pd.persona_id, t.empresa_id
            FROM personas_distribuciones pd
            INNER JOIN mat_trabajadores t ON t.id=pd.trabajador_id
            WHERE pd.estado=1
            GROUP BY pd.persona_id, t.empresa_id
        ) d ON d.persona_id=pc.persona_id AND d.empresa_id=pc.empresa_id 
        WHERE pc.estado = 1
        AND DATE(pc.created_at) BETWEEN '$fecha_ini' AND '$fecha_fin'
        GROUP BY DATE(pc.created_at),e.id, e.empresa, pc.ad_name, pc.interesado
        ORDER BY e.empresa, fecha_carga DESC, pc.ad_name, pc.interesado";
                
        $r= DB::select($sql);
        return $r;
    }

    public static function runLoadTotalPAE( $r ){
        $id=Auth::user()->id;
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');

            })
            ->join('mat_cursos AS mc',function($join){
                $join->on('mc.id','=','mp.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
            })
            ->select( 'mm.id',DB::raw('COUNT(mmd.id) ndet') )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin")){
                        $inicial=trim($r->fecha_ini);
                        $final=trim($r->fecha_fin);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                        }
                    }
                }
            )
            ->where('mm.estado',1)
            ->where('mc.tipo_curso',1)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id');
            
            $result = $sql->orderBy('ndet','desc')->first();

        return $result;
    }

    public static function runLoadPAECab($r,$total)
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
            ->select('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago');

                $sql->addSelect(
                     DB::raw('GROUP_CONCAT( IF(mp.sucursal_id=1,"OnLine","Presencial") ORDER BY mmd.id SEPARATOR "\n") modalidad1'),
                     DB::raw('GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") curso1'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.nro_pago,"") ORDER BY mmd.id SEPARATOR "\n") nro_pago_c1'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.monto_pago,0) ORDER BY mmd.id SEPARATOR "\n") monto_pago_c1'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n") nro_pago_certificado1'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n") monto_pago_certificado1')
                );

            for ($i=2; $i <= $total; $i++) { 
                $sql->addSelect(
                     DB::raw('COUNT(mmd.id) modalidad'.$i),
                     DB::raw('COUNT(mmd.id) curso'.$i),
                     DB::raw('COUNT(mmd.id) nro_pago_c'.$i),
                     DB::raw('COUNT(mmd.id) monto_pago_c'.$i),
                     DB::raw('COUNT(mmd.id) nro_pago_certificado'.$i),
                     DB::raw('COUNT(mmd.id) monto_pago_certificado'.$i)
                );
            }

            $sql->addSelect(
                 'mm.nro_promocion','mm.monto_promocion',
                 DB::raw('SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado) subtotal'),
                 DB::raw('(mm.monto_pago_inscripcion+mm.monto_pago+SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total'),
                 DB::raw('CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre) as cajera'),
                 DB::raw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) as marketing'),
                 DB::raw('CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre) as matricula'),
                'mm.observacion',DB::raw('COUNT(mmd.id) ndet')
            )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin")){
                        $inicial=trim($r->fecha_ini);
                        $final=trim($r->fecha_fin);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                        }
                    }
                }
            )
            ->where('mm.estado',1)
            ->where('mc.tipo_curso',1)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago','mm.nro_promocion','mm.monto_promocion',
                     'pcaj.paterno','pcaj.materno','pcaj.nombre',
                     'pmar.paterno','pmar.materno','pmar.nombre',
                     'pmat.paterno','pmat.materno','pmat.nombre','mm.observacion');

        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runLoadPAE($r)
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
            ->select('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago',
                     DB::raw('GROUP_CONCAT( IF(mp.sucursal_id=1,"OnLine","Presencial") ORDER BY mmd.id SEPARATOR "\n") modalidad'),
                     DB::raw('GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") cursos'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.nro_pago,"") ORDER BY mmd.id SEPARATOR "\n") nro_pago_c'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.monto_pago,0) ORDER BY mmd.id SEPARATOR "\n") monto_pago_c'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n") nro_pago_certificado'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n") monto_pago_certificado'),
                     'mm.nro_promocion','mm.monto_promocion',
                     DB::raw('SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado) subtotal'),
                     DB::raw('(mm.monto_pago_inscripcion+mm.monto_pago+SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total'),
                     DB::raw('CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre) as cajera'),
                     DB::raw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) as marketing'),
                     DB::raw('CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre) as matricula'),
                    'mm.observacion',DB::raw('COUNT(mmd.id) ndet'))
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin")){
                        $inicial=trim($r->fecha_ini);
                        $final=trim($r->fecha_fin);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                        }
                    }
                }
            )
            ->where('mm.estado',1)
            ->where('mc.tipo_curso',1)
            ->whereRaw('mm.sucursal_id IN (SELECT DISTINCT(ppv.sucursal_id)
                            FROM personas_privilegios_sucursales ppv
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.nro_pago','mm.monto_pago','mm.nro_promocion','mm.monto_promocion',
                     'pcaj.paterno','pcaj.materno','pcaj.nombre',
                     'pmar.paterno','pmar.materno','pmar.nombre',
                     'pmat.paterno','pmat.materno','pmat.nombre','mm.observacion');

        $result = $sql->orderBy('mm.id','asc')->get();
        return $result;
    }

    public static function runLoadVisita($r)
    {
        $id=Auth::user()->id;
        $sql=DB::table('personas AS p')
            ->leftJoin('personas AS p2','p2.id','=','p.persona_id_created_at')
            ->leftJoin('mat_ubicacion_region AS r','r.id','=','p.region_id_dir')
            ->leftJoin('mat_ubicacion_provincia AS pr','pr.id','=','p.provincia_id_dir')
            ->leftJoin('mat_ubicacion_distrito AS d','d.id','=','p.distrito_id_dir')
            ->leftJoin('visitas AS v', function($join){
                $join->on('v.persona_id','=','p.id')
                ->where('v.ultimo_registro',1);
            })
            ->leftJoin('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','v.tipo_llamada_id');
            })
            ->leftJoin('tipo_llamadas_sub AS tls', function($join){
                $join->on('tls.id','=','v.tipo_llamada_sub_id');
            })
            ->leftJoin('tipo_llamadas_sub_detalle AS tlsd', function($join){
                $join->on('tlsd.id','=','v.tipo_llamada_sub_detalle_id');
            })
            ->Join('medios_publicitarios AS mp', function($join){
                $join->on('mp.id','=','p.medio_publicitario_id');
            })
            ->leftJoin('sucursales AS s', function($join){
                $join->on('s.id','=','p.sucursal_id');
            })
            ->select('p.id','p.created_at','p.paterno','p.materno','p.nombre','p.dni','p.celular','p.email'
            ,'d.distrito','pr.provincia','r.region','p.referencia_dir AS referencia'
            ,'s.sucursal','mp.medio_publicitario','p.carrera','p.frecuencia','p.hora_inicio','p.hora_final'
            ,'tl.tipo_llamada','tls.tipo_llamada_sub','tlsd.tipo_llamada_sub_detalle'
            ,'v.fechas',DB::raw('CONCAT(p2.paterno," ",p2.materno,", ",p2.nombre) AS registro'))
            ->where( 
                function($query) use ($r){
                    if( Auth::user()->id!=1 ){
                        $query->where('p.id','!=',1);
                    }
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("email") ){
                        $email=trim($r->email);
                        if( $email !='' ){
                            $query->where('p.email','like','%'.$email.'%');
                        }
                    }
                    if( $r->has("celular") ){
                        $celular=trim($r->celular);
                        if( $celular !='' ){
                            $query->where('p.celular','like','%'.$celular.'%');
                        }
                    }
                    if( $r->has("carrera") ){
                        $carrera=trim($r->carrera);
                        if( $carrera !='' ){
                            $query->where('p.carrera','like','%'.$carrera.'%');
                        }
                    }
                    if( $r->has("created_at") ){
                        $created_at=trim($r->created_at);
                        if( $created_at !='' ){
                            $query->whereRaw('DATE(p.created_at)=?',$created_at);
                        }
                    }
                    if( $r->has("fecha_ini") AND $r->has('fecha_fin') ){
                        $query ->whereBetween(DB::raw('DATE(p.created_at)'), array($r->fecha_ini,$r->fecha_fin));
                    }
                }
            );
        $result = $sql->orderBy('p.paterno','asc')->get();
        return $result;
    }

    public static function runExportPAECab($r)
    {
        $total=Reporte::runLoadTotalPAE($r);
        $rsql= Reporte::runLoadPAECab($r,$total->ndet);

        $az=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ','DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ');

        $pos=0;

        $length=array(
            $az[$pos++]=>5,$az[$pos++]=>15,$az[$pos++]=>20,$az[$pos++]=>20,$az[$pos++]=>20,$az[$pos++]=>15,$az[$pos++]=>15,$az[$pos++]=>25,$az[$pos++]=>30,
            $az[$pos++]=>15,$az[$pos++]=>15,$az[$pos++]=>15,
            $az[$pos++]=>15,$az[$pos++]=>15,
            $az[$pos++]=>15,$az[$pos++]=>15
        );

            for ($i=1; $i <= $total->ndet; $i++) { 
                $length[$az[$pos++]]=15;
                $length[$az[$pos++]]=15;
                $length[$az[$pos++]]=15;
                $length[$az[$pos++]]=15;
                $length[$az[$pos++]]=15;
                $length[$az[$pos++]]=15;
            }
            
                $length[$az[$pos++]]=20; $length[$az[$pos++]]=20;
                $length[$az[$pos++]]=20; $length[$az[$pos++]]=20;
                $length[$az[$pos++]]=20; $length[$az[$pos++]]=20; $length[$az[$pos++]]=20; $length[$az[$pos]]=30;

        $cabecera1=array('Alumnos','Matrícula','Inscripción','Matrícula');

        for ($i=1; $i <= $total->ndet; $i++) { 
            array_push($cabecera1,'Curso '.$i);
        }
            array_push($cabecera1, 'Promociones','Pagos','Responsable');
            
        $cabecantNro=array(9,3,2,2);

        for ($i=1; $i <= $total->ndet; $i++) { 
            array_push($cabecantNro, 5);
        }
            array_push($cabecantNro, 2,2,4);

        $pos2=0;
        $cabecantLetra=array( $az[$pos2].'3:'.$az[$pos2+8].'3' ); $pos2+=9;
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+2].'3' ); $pos2+=3;
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+1].'3' ); $pos2+=2;
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+1].'3' ); $pos2+=2;

        for ($i=1; $i <= $total->ndet; $i++) { 
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+5].'3' ); $pos2+=6;
        }

            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+1].'3' ); $pos2+=2;
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+1].'3' ); $pos2+=2;
            array_push($cabecantLetra, $az[$pos2].'3:'.$az[$pos2+3].'3' ); $pos2+=4;

        $cabecera2=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            'Nro Pago Ins','Monto Pago Ins',
            'Nro Pago Mat','Monto Pago Mat'
        );

        for ($i=1; $i <= $total->ndet; $i++) { 
         array_push($cabecera2, 'Modalidad'.$i,'Cursos'.$i,'Nro Pago'.$i,'Monto Pago'.$i,'Nro Pago Certificado'.$i,'Monto Pago Certificado'.$i);
        }
            
            array_push($cabecera2,'Nro Pago Promoción','Monto Pago Promoción');
            array_push($cabecera2,'Sub Total Curso','Total Pagado');
            array_push($cabecera2,'Cajera','Marketing','Matrícula');
            array_push($cabecera2,'Observacion');
        
        $campos=array(
             'id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             'nro_pago_inscripcion','monto_pago_inscripcion',
             'nro_pago','monto_pago',
             'modalidad','cursos','nro_pago_c','monto_pago_c','nro_pago_certificado','monto_pago_certificado',
             'nro_promocion','monto_promocion',
             'subtotal','total',
             'cajera','marketing','matricula',
             'observacion'
        );

        $r['data']=$rsql;
        $r['cabecera1']=$cabecera1;
        $r['cabecantLetra']=$cabecantLetra;
        $r['cabecantNro']=$cabecantNro;
        $r['cabecera2']=$cabecera2;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']=$az[$pos];
        $r['total']=$total->ndet;
        return $r;
    }

    public static function runExportPAE($r)
    {
        $rsql= Reporte::runLoadPAE($r);

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,'I'=>30,
            'J'=>15,'K'=>15,'L'=>15,
            'M'=>15,'N'=>15,
            'O'=>15,'P'=>15,
            'Q'=>15,'R'=>15,'S'=>15, 'T'=>15, 'U'=>15,'V'=>15,
            'W'=>20,'X'=>20,
            'Y'=>20,'Z'=>20,
            'AA'=>20,'AB'=>20,'AC'=>20,'AD'=>30
        );

        $cabecera1=array(
            'Alumnos','Matrícula','Inscripción','Matrícula',
            'Cursos','Promociones','Pagos','Responsable'
        );

        $cabecantNro=array(
            9,3,2,2,
            5,2,2,4
        );

        $cabecantLetra=array(
            'A3:I3','J3:L3','M3:N3','O3:P3',
            'Q3:V3','W3:X3','Y3:Z3','AA3:AD3'
        );

        $cabecera2=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            'Nro Pago Ins','Monto Pago Ins',
            'Nro Pago Mat','Monto Pago Mat',
            'Modalidad','Cursos','Nro Pago','Monto Pago','Nro Pago Certificado','Monto Pago Certificado',
            'Nro Pago Promoción','Monto Pago Promoción',
            'Sub Total Curso','Total Pagado',
            'Cajera','Marketing','Matrícula',
            'Observacion'
        );
        $campos=array(
             'id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             'nro_pago_inscripcion','monto_pago_inscripcion',
             'nro_pago','monto_pago',
             'modalidad','cursos','nro_pago_c','monto_pago_c','nro_pago_certificado','monto_pago_certificado',
             'nro_promocion','monto_promocion',
             'subtotal','total',
             'cajera','marketing','matricula',
             'observacion'
        );

        $r['data']=$rsql;
        $r['cabecera1']=$cabecera1;
        $r['cabecantLetra']=$cabecantLetra;
        $r['cabecantNro']=$cabecantNro;
        $r['cabecera2']=$cabecera2;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='AD';
        return $r;
    }

    public static function runLoadIndiceMat($r)
    {
        $id=Auth::user()->id;
        $sql1=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1)
                ->whereNull('mmd.especialidad_id');
            })
            ->join('mat_cursos AS mc',function($join){
                $join->on('mc.id','=','mmd.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->join('empresas AS e','e.id','=','mc.empresa_id')
            ->select(DB::raw('"0" AS id'),'e.empresa' 
                    ,DB::raw(' GROUP_CONCAT(DISTINCT(IF( mm.especialidad_programacion_id IS NULL, 
                        IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ),
                        "Especialidad"
                    ))) AS tipo_formacion ')
                    ,'mc.curso AS formacion' ,'mp.dia','mp.fecha_inicio','mp.fecha_final'
                ,DB::raw("COUNT(IF( mm.fecha_matricula='".$r->ult_dia."',mmd.id,NULL )) ult_dia")
                ,DB::raw("COUNT(IF( mm.fecha_matricula='".$r->penult_dia."',mmd.id,NULL )) penult_dia") 
                ,DB::raw('COUNT(mmd.id) mat'),'mp.meta_max','mp.meta_min','mp.fecha_campaña'
                ,DB::raw('DATEDIFF(CURDATE(),mp.fecha_campaña) AS ndias')
                ,DB::raw('IF(DATEDIFF( CURDATE(),DATE(mp.fecha_inicio) ) >=0,0,(DATEDIFF(mp.fecha_inicio,CURDATE()) )) AS dias_falta')
            )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                        if( $r->has('tipo_fecha') AND $r->tipo_fecha==1 ){
                            $query ->whereRaw("DATE(mp.fecha_inicio) BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."' ");
                        }
                        else{
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                        }
                    }

                    if( $r->has('tipo_curso') ){
                        $query->where('mc.tipo_curso','=',$r->tipo_curso);
                    }
                }
            )
            ->where('mm.estado',1)
            ->groupBy('e.empresa','mc.curso','mp.dia','mp.fecha_inicio','mp.fecha_final','mp.meta_max','mp.meta_min','mp.fecha_campaña')
            ->orderBy('mc.curso','asc')
            ;

        $sql2=DB::table('mat_matriculas AS mm')
            ->join('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->join('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mep.especialidad_id')
                ->where('me.empresa_id', Auth::user()->empresa_id);
            })
            ->join('empresas AS e','e.id','=','me.empresa_id')
            ->select(DB::raw('"0" AS id'),'e.empresa' 
                    ,DB::raw(' "Especialidad" AS tipo_formacion ')
                    ,'me.especialidad AS formacion' ,DB::raw("'' AS dia"),'mep.fecha_inicio',DB::raw("'' AS fecha_final")
                ,DB::raw("COUNT(IF( mm.fecha_matricula='".$r->ult_dia."',mm.id,NULL )) ult_dia")
                ,DB::raw("COUNT(IF( mm.fecha_matricula='".$r->penult_dia."',mm.id,NULL )) penult_dia") 
                ,DB::raw('COUNT(mm.id) mat'),'mep.meta_max','mep.meta_min','mep.fecha_campaña'
                ,DB::raw('DATEDIFF(CURDATE(),mep.fecha_campaña) AS ndias')
                ,DB::raw('IF(DATEDIFF( CURDATE(),DATE(mep.fecha_inicio) ) >=0,0,(DATEDIFF(mep.fecha_inicio,CURDATE()) )) AS dias_falta')
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                        if( $r->has('tipo_fecha') AND $r->tipo_fecha==1 ){
                            $query ->whereRaw("DATE(mep.fecha_inicio) BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."' ");
                        }
                        else{
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_ini,$r->fecha_fin));
                        }
                    }
                }
            )
            ->where('mm.estado',1)
            ->groupBy('e.empresa','me.especialidad','mep.fecha_inicio','mep.fecha_campaña','mep.meta_max','mep.meta_min')
            ->orderBy('me.especialidad','asc')
            ->union($sql1)
            ->get();

        //$result=array($sql1,$sql2);
            $result=$sql2;
        return $result;
    }

    public static function runExportIndiceMat($r)
    {
        $rsql= Reporte::runLoadIndiceMat($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,20,15
            ,15,10,10,10
            ,10,10,10,10
            ,15,15,15,15
            ,15,15
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

        /*$cabeceraTit=array(
            'DATOS','DATOS DEL INSCRITO','SOBRE LA FORMACIÓN CONTINUA','PAGO POR CURSO','PAGO POR CONJUNTO DE CURSOS / PAGO POR INS. ESPECIALIDAD','DATOS DE LA VENTA'
        );

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(1,6,5,3,1,4);
        $colorTit=array('#FDE9D9','#F2DCDB','#C5D9F1','#FFFF00','#8DB4E2','#FCD5B4');
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
        }*/

        $cabecera=array(
            'N°','Empresa','Tipo de Formación','Formación','FREC',
            'Fecha Inicio','Inscritos Últimos 2Días','','Total Inscritos.',
            'Meta Max','Meta Min','Inicio Campaña','Días Campaña',
            'Indice Por Día','Días Que Falta','Proy. Días Faltantes','Proy. Final',
            'Falta Lograr Meta','Observación'
        );
        $campos=array(
             ''
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']=$estatico.chr($min);;
        return $r;
    }

    public static function runLoadLlamadas($r)
    {
        $id=Auth::user()->id;
        $fechaaux=$r->fecha_ini;

        $sql=DB::table('tipo_llamadas AS tl')
            ->Leftjoin('llamadas AS ll',function($join) use( $r ){
                $join->on('ll.tipo_llamada_id','=','tl.id')
                ->where('ll.estado',1);
                if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                    $join->whereBetween(DB::raw('DATE(ll.fecha_llamada)'), array($r->fecha_ini,$r->fecha_fin));
                }
                if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
                    $vendedor=explode(',',$r->vendedor);
                    $join->whereIn('ll.trabajador_id',$vendedor);
                }
                if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
                    $join->where('ll.ultimo_registro','1');
                }
            })
            ->select('tl.tipo_llamada', DB::raw('COUNT(ll.id) AS total'))
            ->where('tl.estado',1)
            ->groupBy(DB::raw('tl.tipo_llamada WITH ROLLUP'));

        $cont=0;
        while($fechaaux<=$r->fecha_fin){
            $cont++;
            $sql->addSelect( DB::raw('COUNT(f'.$cont.'.id) AS \''.$fechaaux.'\'') );
            $sql->Leftjoin('llamadas AS f'.$cont,function($join) use ( $fechaaux,$cont ){
                $join->on('f'.$cont.'.id','=','ll.id')
                ->where('ll.estado','=','1')
                ->whereRaw('DATE(f'.$cont.'.fecha_llamada)=\''.$fechaaux.'\'');
            });
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        if( ($r->has("fuente") AND trim($r->fuente)!='') OR 
            ($r->has("tipo") AND trim($r->tipo)!='') OR 
            ($r->has("empresa") AND trim($r->empresa)!='') OR 
            ( $r->has("fecha_ini_dis") AND $r->has("fecha_fin_dis") AND trim($r->fecha_ini_dis)!='' AND trim($r->fecha_fin_dis)!='' )
        ){
            $sql->join('personas AS p',function($join) use ( $r ){
                $join->on('p.id','=','ll.persona_id');
                if( $r->has("fuente") AND trim($r->fuente)!='' ){
                    $fuente=explode(',',$r->fuente);
                    $join->whereIn('p.fuente',$fuente);
                }
                if( $r->has("tipo") AND trim($r->tipo)!='' ){
                    $tipo=explode(',',$r->tipo);
                    $join->whereIn('p.tipo',$tipo);
                }
                if( $r->has("empresa") AND trim($r->empresa)!='' ){
                    $empresa=explode(',',$r->empresa);
                    $join->whereIn('p.empresa',$empresa);
                }
            });
        }

        $result = $sql->get();
        return $result;
    }

    public static function runExportLlamadas($r)
    {
        $rsql= Reporte::runLoadLlamadas($r);

        $length=array(
            'A'=>28,'B'=>7.3
        );

        $cabecera2=array(
            'Motivo','Total'
        );

        $fechaaux=$r->fecha_ini;
        $cont=0;
        $min=66;//65
        $max=90;
        $estatico='';
        //char(65) = A al char(90)=Z
        while($fechaaux<=$r->fecha_fin){
            if($min==$max){
                $min=64;
                $estatico='A';
            }
            $cont++;
            $min++;
            $length[$estatico.chr($min)]=5;
            array_push($cabecera2, $fechaaux);
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=$estatico.chr($min);
        return $r;
    }

    public static function runLoadNoInteresados($r)
    {
        $id=Auth::user()->id;
        $fechaaux=$r->fecha_ini;

        $sql=DB::table('tipo_llamadas_sub AS tl')
            ->Leftjoin('llamadas AS ll',function($join) use( $r ){
                $join->on('ll.tipo_llamada_sub_id','=','tl.id')
                ->where('ll.estado',1);
                if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                    $join->whereBetween(DB::raw('DATE(ll.fecha_llamada)'), array($r->fecha_ini,$r->fecha_fin));
                }
                if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
                    $vendedor=explode(',',$r->vendedor);
                    $join->whereIn('ll.trabajador_id',$vendedor);
                }
                if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
                    $join->where('ll.ultimo_registro','1');
                }
            })
            ->select('tl.tipo_llamada_sub', DB::raw('COUNT(ll.id) AS total'))
            ->where('tl.estado',1)
            ->where('tl.tipo_llamada_id',8)
            ->groupBy(DB::raw('tl.tipo_llamada_sub WITH ROLLUP'));

        $cont=0;
        while($fechaaux<=$r->fecha_fin){
            $cont++;
            $sql->addSelect( DB::raw('COUNT(f'.$cont.'.id) AS \''.$fechaaux.'\'') );
            $sql->Leftjoin('llamadas AS f'.$cont,function($join) use ( $fechaaux,$cont ){
                $join->on('f'.$cont.'.id','=','ll.id')
                ->where('ll.estado','=','1')
                ->whereRaw('DATE(f'.$cont.'.fecha_llamada)=\''.$fechaaux.'\'');
            });
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        if( ($r->has("fuente") AND trim($r->fuente)!='') OR 
            ($r->has("tipo") AND trim($r->tipo)!='') OR 
            ($r->has("empresa") AND trim($r->empresa)!='') OR 
            ( $r->has("fecha_ini_dis") AND $r->has("fecha_fin_dis") AND trim($r->fecha_ini_dis)!='' AND trim($r->fecha_fin_dis)!='' )
        ){
            $sql->join('personas AS p',function($join) use ( $r ){
                $join->on('p.id','=','ll.persona_id');
                if( $r->has("fuente") AND trim($r->fuente)!='' ){
                    $fuente=explode(',',$r->fuente);
                    $join->whereIn('p.fuente',$fuente);
                }
                if( $r->has("tipo") AND trim($r->tipo)!='' ){
                    $tipo=explode(',',$r->tipo);
                    $join->whereIn('p.tipo',$tipo);
                }
                if( $r->has("empresa") AND trim($r->empresa)!='' ){
                    $empresa=explode(',',$r->empresa);
                    $join->whereIn('p.empresa',$empresa);
                }
            });
        }

        $result = $sql->get();
        return $result;
    }

    public static function runExportNoInteresados($r)
    {
        $rsql= Reporte::runLoadNoInteresados($r);

        $length=array(
            'A'=>28,'B'=>7.3
        );

        $cabecera2=array(
            'Motivo','Total'
        );

        $fechaaux=$r->fecha_ini;
        $cont=0;
        $min=66;//65
        $max=90;
        $estatico='';
        //char(65) = A al char(90)=Z
        while($fechaaux<=$r->fecha_fin){
            if($min==$max){
                $min=64;
                $estatico='A';
            }
            $cont++;
            $min++;
            $length[$estatico.chr($min)]=5;
            array_push($cabecera2, $fechaaux);
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=$estatico.chr($min);
        return $r;
    }

    public static function runLoadNoInteresadosDetalle($r)
    {
        $id=Auth::user()->id;
        $fechaaux=$r->fecha_ini;

        $sql=DB::table('tipo_llamadas_sub_detalle AS tl')
            ->join('tipo_llamadas_sub AS tls',function($join){
                $join->on('tls.id','=','tl.tipo_llamada_sub_id')
                ->where('tls.estado',1)
                ->where('tls.tipo_llamada_id',8);
                
            })
            ->Leftjoin('llamadas AS ll',function($join) use( $r ){
                $join->on('ll.tipo_llamada_sub_detalle_id','=','tl.id')
                ->where('ll.estado',1);
                if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                    $join->whereBetween(DB::raw('DATE(ll.fecha_llamada)'), array($r->fecha_ini,$r->fecha_fin));
                }
                if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
                    $vendedor=explode(',',$r->vendedor);
                    $join->whereIn('ll.trabajador_id',$vendedor);
                }
                if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
                    $join->where('ll.ultimo_registro','1');
                }
            })
            ->select('tls.tipo_llamada_sub','tl.tipo_llamada_sub_detalle', DB::raw('COUNT(ll.id) AS total'))
            ->where('tl.estado',1)
            ->groupBy(DB::raw('tls.tipo_llamada_sub,tl.tipo_llamada_sub_detalle WITH ROLLUP'));

        $cont=0;
        while($fechaaux<=$r->fecha_fin){
            $cont++;
            $sql->addSelect( DB::raw('COUNT(f'.$cont.'.id) AS \''.$fechaaux.'\'') );
            $sql->Leftjoin('llamadas AS f'.$cont,function($join) use ( $fechaaux,$cont ){
                $join->on('f'.$cont.'.id','=','ll.id')
                ->where('ll.estado','=','1')
                ->whereRaw('DATE(f'.$cont.'.fecha_llamada)=\''.$fechaaux.'\'');
            });
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        if( ($r->has("fuente") AND trim($r->fuente)!='') OR 
            ($r->has("tipo") AND trim($r->tipo)!='') OR 
            ($r->has("empresa") AND trim($r->empresa)!='') OR 
            ( $r->has("fecha_ini_dis") AND $r->has("fecha_fin_dis") AND trim($r->fecha_ini_dis)!='' AND trim($r->fecha_fin_dis)!='' )
        ){
            $sql->join('personas AS p',function($join) use ( $r ){
                $join->on('p.id','=','ll.persona_id');
                if( $r->has("fuente") AND trim($r->fuente)!='' ){
                    $fuente=explode(',',$r->fuente);
                    $join->whereIn('p.fuente',$fuente);
                }
                if( $r->has("tipo") AND trim($r->tipo)!='' ){
                    $tipo=explode(',',$r->tipo);
                    $join->whereIn('p.tipo',$tipo);
                }
                if( $r->has("empresa") AND trim($r->empresa)!='' ){
                    $empresa=explode(',',$r->empresa);
                    $join->whereIn('p.empresa',$empresa);
                }
            });
        }

        $result = $sql->get();
        return $result;
    }

    public static function runExportNoInteresadosDetalle($r)
    {
        $rsql= Reporte::runLoadNoInteresadosDetalle($r);

        $length=array(
            'A'=>7,'B'=>28,'C'=>7.3
        );

        $cabecera2=array(
            '','Detalle','Total'
        );

        $fechaaux=$r->fecha_ini;
        $cont=0;
        $min=67;//65
        $max=90;
        $estatico='';
        //char(65) = A al char(90)=Z
        while($fechaaux<=$r->fecha_fin){
            if($min==$max){
                $min=64;
                $estatico='A';
            }
            $cont++;
            $min++;
            $length[$estatico.chr($min)]=5;
            array_push($cabecera2, $fechaaux);
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=$estatico.chr($min);
        return $r;
    }

    public static function runLoadInteresados($r)
    {
        $id=Auth::user()->id;
        $fechaaux=$r->fecha_ini;

        $sql=DB::table('llamadas AS ll')
            ->select('ll.fechas', DB::raw('COUNT(ll.id) AS total'))
            ->where( 
                function($query) use ($r){
                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                        $query->whereBetween(DB::raw('DATE(ll.fecha_llamada)'), array($r->fecha_ini,$r->fecha_fin));
                    }
                    if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
                        $vendedor=explode(',',$r->vendedor);
                        $query->whereIn('ll.trabajador_id',$vendedor);
                    }
                    if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
                        $query->where('ll.ultimo_registro','1');
                    }
                }
            )
            ->where('ll.estado',1)
            ->where('ll.tipo_llamada_id',1)
            ->groupBy(DB::raw('ll.fechas WITH ROLLUP'));

        $cont=0;
        while($fechaaux<=$r->fecha_fin){
            $cont++;
            $sql->addSelect( DB::raw('COUNT(f'.$cont.'.id) AS \''.$fechaaux.'\'') );
            $sql->Leftjoin('llamadas AS f'.$cont,function($join) use ( $fechaaux,$cont ){
                $join->on('f'.$cont.'.id','=','ll.id')
                ->whereRaw('DATE(f'.$cont.'.fecha_llamada)=\''.$fechaaux.'\'');
            });
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        if( ($r->has("fuente") AND trim($r->fuente)!='') OR 
            ($r->has("tipo") AND trim($r->tipo)!='') OR 
            ($r->has("empresa") AND trim($r->empresa)!='') OR 
            ( $r->has("fecha_ini_dis") AND $r->has("fecha_fin_dis") AND trim($r->fecha_ini_dis)!='' AND trim($r->fecha_fin_dis)!='' )
        ){
            $sql->join('personas AS p',function($join) use ( $r ){
                $join->on('p.id','=','ll.persona_id');
                if( $r->has("fuente") AND trim($r->fuente)!='' ){
                    $fuente=explode(',',$r->fuente);
                    $join->whereIn('p.fuente',$fuente);
                }
                if( $r->has("tipo") AND trim($r->tipo)!='' ){
                    $tipo=explode(',',$r->tipo);
                    $join->whereIn('p.tipo',$tipo);
                }
                if( $r->has("empresa") AND trim($r->empresa)!='' ){
                    $empresa=explode(',',$r->empresa);
                    $join->whereIn('p.empresa',$empresa);
                }
            });
        }

        $result = $sql->get();
        return $result;
    }

    public static function runExportInteresados($r)
    {
        $rsql= Reporte::runLoadInteresados($r);

        $length=array(
            'A'=>28,'B'=>7.3
        );

        $cabecera2=array(
            'Motivo','Total'
        );

        $fechaaux=$r->fecha_ini;
        $cont=0;
        $min=66;//65
        $max=90;
        $estatico='';
        //char(65) = A al char(90)=Z
        while($fechaaux<=$r->fecha_fin){
            if($min==$max){
                $min=64;
                $estatico='A';
            }
            $cont++;
            $min++;
            $length[$estatico.chr($min)]=5;
            array_push($cabecera2, $fechaaux);
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=$estatico.chr($min);
        return $r;
    }

    public static function runLoadLlamadasDetalle($r)
    {
        $sql=DB::table('llamadas AS ll')
            ->Join('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','ll.tipo_llamada_id');
            })
            ->Join('mat_trabajadores AS tr', function($join){
                $join->on('tr.id','=','ll.trabajador_id')
                ->where('tr.empresa_id', Auth::user()->empresa_id);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','tr.persona_id');
            })
            ->Join('personas AS p2', function($join){
                $join->on('p2.id','=','ll.persona_id');
            })
            ->leftJoin('tipo_llamadas_sub AS tls', function($join){
                $join->on('tls.id','=','ll.tipo_llamada_sub_id');
            })
            ->leftJoin('tipo_llamadas_sub_detalle AS tlsd', function($join){
                $join->on('tlsd.id','=','ll.tipo_llamada_sub_detalle_id');
            })
            ->select(
            DB::raw('DATE(ll.fecha_llamada) AS fecha_llamada'),DB::raw('TIME(ll.fecha_llamada) AS hora_llamada')
            ,DB::raw('CONCAT(p.paterno,\' \',p.materno,\', \',p.nombre) AS teleoperador'),
            DB::raw('CONCAT(p2.paterno,\' \',p2.materno,\', \',p2.nombre) AS persona'),
            'tl.tipo_llamada','tls.tipo_llamada_sub','tlsd.tipo_llamada_sub_detalle',
            'll.fechas','ll.comentario','ll.objecion','ll.pregunta','p2.fuente','p2.carrera','p2.celular'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") ){
                        $query->whereBetween(DB::raw('DATE(ll.fecha_llamada)'), array($r->fecha_ini,$r->fecha_fin));
                    }
                    if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
                        $vendedor=explode(',',$r->vendedor);
                        $query->whereIn('ll.trabajador_id',$vendedor);
                    }
                    if( $r->has("fuente") AND trim($r->fuente)!='' ){
                        $fuente=explode(',',$r->fuente);
                        $query->whereIn('p.fuente',$fuente);
                    }
                    if( $r->has("tipo") AND trim($r->tipo)!='' ){
                        $tipo=explode(',',$r->tipo);
                        $query->whereIn('p.tipo',$tipo);
                    }
                    if( $r->has("empresa") AND trim($r->empresa)!='' ){
                        $empresa=explode(',',$r->empresa);
                        $query->whereIn('p.empresa',$empresa);
                    }
                    if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
                        $query->where('ll.ultimo_registro','1');
                    }
                }
            );


        $result = $sql->orderBy('ll.fecha_llamada','desc')->get();
        return $result;
    }

    public static function runExportLlamadasDetalle($r)
    {
        $rsql= Reporte::runLoadLlamadasDetalle($r);
        $min=65;
        $length=array(
            chr($min)=>12,
        );

        $min++; $length[chr($min)]=12;
        $min++; $length[chr($min)]=30;
        $min++; $length[chr($min)]=30;
        $min++; $length[chr($min)]=16;
        $min++; $length[chr($min)]=20;
        $min++; $length[chr($min)]=20;
        $min++; $length[chr($min)]=16;
        $min++; $length[chr($min)]=25;
        $min++; $length[chr($min)]=25;
        $min++; $length[chr($min)]=25;
        $min++; $length[chr($min)]=15;
        $min++; $length[chr($min)]=25;
        $min++; $length[chr($min)]=15;

        $cabecera2=array(
            'Fecha Llamada','Hora Llamada','Teleoperador(a)','Cliente',
            'Tipo Llamada','Sub Tipo Llamada','Detalle Sub Tipo',
            'Fecha Programada','Comentario','Objeción','Pregunta','Fuente','Carrera del Interesado','Celular'
        );

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=chr($min);
        return $r;
    }

    public static function runLoadClientePotencial($r)
    {
        $sql=DB::table('tipo_llamadas')->where('estado','1')->get();
        $whereaux="";$ultimoRegistro="";
        if( $r->has("vendedor") AND trim($r->vendedor)!='' ){
            $vendedor= str_replace(',', "','", $r->vendedor);
            $whereaux.='
            AND t.id IN (\''.$vendedor.'\')';
        }
        if( $r->has("fuente") AND trim($r->fuente)!='' ){
            $fuente= str_replace(',', "','", $r->fuente);
            $whereaux.='
            AND p2.fuente IN (\''.$fuente.'\')';
        }
        if( $r->has("tipo") AND trim($r->tipo)!='' ){
            $tipo= str_replace(',', "','", $r->tipo);
            $whereaux.='
            AND p2.tipo IN (\''.$tipo.'\')';
        }
        if( $r->has("empresa") AND trim($r->empresa)!='' ){
            $empresa= str_replace(',', "','", $r->empresa);
            $whereaux.='
            AND p2.empresa IN (\''.$empresa.'\')';
        }
        if( $r->has("ultimo_registro") AND trim($r->ultimo_registro)=='1' ){
            $ultimoRegistro=" AND ll.ultimo_registro=1 ";
        }

        $selectaux1="";$selectaux2="";$fromaux="";$tipo_llamada=array();
        $contador=0;
        foreach ($sql as $key => $value) {
            $contador++;
            $selectaux1.="
            , count( DISTINCT( if(ll.tipo_llamada_id=".$contador.",ll.id,NULL) ) ) AS tp".$contador;
            array_push($tipo_llamada, $value->tipo_llamada);
        }

        $fechaaux=$r->fecha_ini;
        $cont=0;
        while($fechaaux<=$r->fecha_fin){
            $cont++;
            $selectaux2.="
            , count(DISTINCT(ll".$cont.".persona_id)) data_llamada".$cont."
            , (IF(count(DISTINCT(IF(pd.fecha_distribucion='".$fechaaux."',pd.persona_id,NULL)))<=count(DISTINCT(ll".$cont.".persona_id)), count(DISTINCT(ll".$cont.".persona_id)), count(DISTINCT(IF(pd.fecha_distribucion='".$fechaaux."',pd.persona_id,NULL)))) - count(DISTINCT(ll".$cont.".persona_id))) falta_llamada".$cont;
            $fromaux.="
            LEFT JOIN llamadas ll".$cont." ON ll".$cont.".id=ll.id AND DATE(ll".$cont.".fecha_llamada)='".$fechaaux."'";
            $contador=0;
            foreach ($sql as $key => $value) {
                $contador++;
                $selectaux2.="
                , count( DISTINCT( if(ll".$cont.".tipo_llamada_id=".$contador.",ll".$cont.".id,NULL) ) ) AS ".$cont."tp".$contador;
            }
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $select="
        SELECT @numero:=@numero+1 nro,CONCAT(p.paterno,' ', p.materno,', ', p.nombre) trabajador, t.codigo, p2.empresa
        , IFNULL(count(DISTINCT(m.id))/count(DISTINCT(ta.id)),0) pro_dia, count(DISTINCT(ta.id)) total_dias 
        , IFNULL(count(DISTINCT(m.id))/count(DISTINCT(pd.id)),0) pro_data, count(DISTINCT(pd.persona_id)) total_data
        , count(DISTINCT(m.id)) total_insc
        , count(DISTINCT(ll.persona_id)) data_llamada, (IF(count(DISTINCT(pd.persona_id))<=count(DISTINCT(ll.persona_id)), count(DISTINCT(ll.persona_id)), count(DISTINCT(pd.persona_id))) - count(DISTINCT(ll.persona_id))) falta_llamada 
        ";
        $select.=$selectaux1.$selectaux2;

        $from="
        FROM mat_trabajadores t 
        INNER JOIN personas p ON p.id=t.persona_id
        INNER JOIN llamadas ll ON ll.trabajador_id=t.id ".$ultimoRegistro."
        INNER JOIN personas p2 ON p2.id=ll.persona_id
        INNER JOIN personas_distribuciones pd ON pd.trabajador_id=t.id AND pd.persona_id=ll.persona_id AND pd.fecha_distribucion BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."'
        LEFT JOIN mat_trabajadores_asistencias ta ON ta.trabajador_id=t.id AND ta.fecha_asistencia BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."'
        LEFT JOIN mat_matriculas m ON m.persona_marketing_id=t.id AND m.fecha_matricula BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."'
        ";
        $from.=$fromaux;

        $where="
        WHERE t.rol_id=1
        AND t.empresa_id='".Auth::user()->empresa_id."'
        AND DATE(ll.fecha_llamada) BETWEEN '".$r->fecha_ini."' AND '".$r->fecha_fin."'
        ";
        $where.=$whereaux;

        $group="
        GROUP BY t.id,p2.empresa,p.paterno, p.materno, p.nombre, t.codigo";
        //dd($select.$from.$where.$group);
        DB::statement(DB::raw('SET @numero=0'));
        $result['data']=DB::select($select.$from.$where.$group);
        $result['cont']=$cont;
        $result['contador']=$contador;
        $result['tipo_llamada']=$tipo_llamada;
        return $result;
    }

    public static function runExportClientePotencial($r)
    {
        $rsql= Reporte::runLoadClientePotencial($r);
        $min=65;
        $max=90;
        $estatico='';
        $minestatico=64;
        
        $length=array(
            chr($min)=>3,
        );

        $min++; $length[chr($min)]=35;
        $min++; $length[chr($min)]=11;
        $min++; $length[chr($min)]=11;
        $min++; $length[chr($min)]=14;
        $min++; $length[chr($min)]=11;
        $min++; $length[chr($min)]=14;
        $min++; $length[chr($min)]=11;
        $min++; $length[chr($min)]=11;

        $cabecera2=array(
            'N°','Nombre','Código','Empresa'
            ,'Promedio','Dias Trab.','Promedio', 'Total Data'
            ,'Total Inscritos'
        );

        $cabecera1=array(
            'Promedio por Días','Promedio por Data','Consolidado'
        );

        $inicantLetra=64+10;
        $fincantLetra=64;
        $cabecantLetra=array( 'E3:F3' ,'G3:H3',chr($inicantLetra).'3:'.chr($inicantLetra+1+$rsql['contador']).'3');
        array_push($cabecera2, 'Data Llamda');
        array_push($cabecera2, 'Falta Llamar');
        $min++; $length[$estatico.chr($min)]=4.5;
        $min++; $length[$estatico.chr($min)]=4.5;
        for( $i=1; $i<=$rsql['contador']; $i++ ){
            $min++; $length[chr($min)]=4.5;
            array_push($cabecera2, $rsql['tipo_llamada'][($i-1)]);
        }

        $fechaaux=$r->fecha_ini;
        $estatico='';$estaticofin='';
        $iniestatico=64;$finestatico=64;
        $max=90;
        while($fechaaux<=$r->fecha_fin){
            if( $inicantLetra+1+$rsql['contador']+1>90 ){
                $inicantLetra=$inicantLetra+1+$rsql['contador']+1-90+64;
                $iniestatico++;
                $estatico=chr($iniestatico);
                if($iniestatico>$finestatico){
                    $finestatico++;
                    $estaticofin=chr($finestatico);
                }
            }
            else{
                $inicantLetra=$inicantLetra+1+$rsql['contador']+1;
            }

            if( $inicantLetra+1+$rsql['contador']>90 ){
                $fincantLetra=$inicantLetra+1+$rsql['contador']-90+64;
                $finestatico++;
                $estaticofin=chr($finestatico);
            }
            else{
                $fincantLetra=$inicantLetra+1+$rsql['contador'];
            }

            array_push($cabecantLetra, $estatico.chr($inicantLetra).'3:'.$estaticofin.chr($fincantLetra).'3');
            array_push($cabecera1, $fechaaux);
            array_push($cabecera2, 'Data Llamda');
            array_push($cabecera2, 'Falta Llamar');
            $min++; $length[$estatico.chr($min)]=4.5;
            $min++; $length[$estatico.chr($min)]=4.5;
            for( $j=1; $j<=$rsql['contador']; $j++ ){
                $min++; $length[$estatico.chr($min)]=4.5;
                if($min==$max){
                    $min=64;
                    $minestatico++;
                    $estatico=chr($minestatico);
                }
                array_push($cabecera2, $rsql['tipo_llamada'][($j-1)]);
            }
            $fechaaux=date("Y-m-d",strtotime($fechaaux."+ 1 days"));
        }

        $r['data']=$rsql['data'];
        $r['cabecera1']=$cabecera1;
        $r['cabecantLetra']=$cabecantLetra;
        $r['cabecera2']=$cabecera2; 
        $r['length']=$length;
        $r['max']=$estatico.chr($min);

        //dd($cabecantLetra);
        return $r;
    }

    public static function runExportVisita($r)
    {
        $rsql= Reporte::runLoadVisita($r);

        $length=array('A'=>5);
        $pos=array(
            5,20,15,15,15,15,15,20,
            15,15,15,20,
            15,15,25,20,10,10,
            20,20,20,15,25
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
            'DATOS DEL VISITANTE','PREFERENCIA DEL VISITANTE','ESTADO DEL VISITANTE'
        );

        $valIni=66;
        $min2=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(10,5,4);
        $colorTit=array('#FDE9D9','#F2DCDB','#C5D9F1');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min2++;
                $estaticoFin= chr($min2);
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
                    $min2++;
                    $estatico= chr($min2);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°','Fecha de Registro','Paterno','Materno','Nombre','DNI','Celular','Email',
            'Distrito','Provincia','Región','Referencia',
            'Sede de Registro','Medio Publicitario','Interesado en','Frecuencia','Hora Inicio','Hora Final',
            'Estado','Sub Estado','Detalle Sub Estado','Fecha Programada','Persona Registro');
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
}
