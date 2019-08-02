<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Reporte extends Model
{
    protected   $table = 'mat_promocion';

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
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');

            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mp.sucursal_id');

            })
            ->join('mat_cursos AS mc',function($join){
                $join->on('mc.id','=','mp.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);

            })
            ->select('mp.id','s2.sucursal as odeclase',DB::raw(" IF(mp.sucursal_id=1,'PAE-VIR','PAE-PRE') instituto "),'mc.curso','mp.dia','mp.fecha_inicio','mp.fecha_final'
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

                    if( $r->has("fecha_ini") AND $r->has("fecha_fin") AND $r->has('tipo_fecha')){
                        if( $r->tipo_fecha==1 ){
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
            ->groupBy('mp.sucursal_id','mp.id','s2.sucursal','mc.curso','mp.dia','mp.fecha_inicio','mp.fecha_final','mp.meta_max','mp.meta_min','mp.fecha_campaña');

        $result = $sql->orderBy('s2.sucursal','asc')
                    ->orderBy('mc.curso','asc')
                    ->get();
        return $result;
    }

    public static function runExportIndiceMat($r)
    {
        $rsql= Reporte::runLoadIndiceMat($r);

        $length=array(
            'A'=>5,'B'=>13,'C'=>14,'D'=>40,'E'=>21.5,'F'=>12.5,
            'G'=>11,'H'=>5.5,'I'=>5.5,'J'=>10.5,
            'K'=>6.5,'L'=>6.5,
            'M'=>11,'N'=>5,
            'O'=>5,'P'=>5,'Q'=>6.5,'R'=>5.5,
            'S'=>7, 'T'=>18
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
            'N°','Local de Estudios','Institución','Curso','FREC','Hora',
            'Fecha Inicio','Inscritos Últimos 2Días','','Total Inscritos.',
            'Meta Max','Meta Min',
            'Inicio Campaña','Días Campaña',
            'Indice Por Día','Días Que Falta','Proy. Días Faltantes','Proy. Final',
            'Falta Lograr Meta','Observación'
        );
        $campos=array(
             ''
        );

        $r['data']=$rsql;
        $r['cabecera1']=$cabecera1;
        $r['cabecantLetra']=$cabecantLetra;
        $r['cabecantNro']=$cabecantNro;
        $r['cabecera2']=$cabecera2;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='T';
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
            'll.fechas','ll.comentario','p2.fuente','p2.tipo','p2.empresa'
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
        $min++; $length[chr($min)]=15;
        $min++; $length[chr($min)]=15;
        $min++; $length[chr($min)]=15;

        $cabecera2=array(
            'Fecha Llamada','Hora Llamada','Teleoperador(a)','Cliente',
            'Tipo Llamada','Sub Tipo Llamada','Detalle Sub Tipo',
            'Fecha Programada','Comentario','Fuente','Tipo','Empresa'
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
}
