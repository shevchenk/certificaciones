<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ReporteAvanzado extends Model
{
    protected   $table = 'empresas';

    public static function runIndiceMatriculacion($r)
    {
        $id=Auth::user()->id;
        $where='';
        if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
            $inicial=trim($r->fecha_inicial);
            $final=trim($r->fecha_final);
            if( $inicial !=''AND $final!=''){
                $where.=" AND DATE(mp.fecha_inicio) BETWEEN '$inicial' AND '$final' ";
            }
        }

        if( $r->has("sucursal") OR $r->has('sucursal2') ){
            $sucursal = '';
            if( $r->has('sucursal2') AND trim($r->sucursal2)!='' ){
                $sucursal = $r->sucursal2;
            }
            else{
                $sucursal= implode(",",$r->sucursal);
            }

            if( $sucursal !=''){
                $where.=" AND s.id IN ($sucursal) ";
            }
        }

        if( $r->has("empresa") OR $r->has('empresa2') ){
            $empresa = '';
            if( $r->has('empresa2') AND trim($r->empresa2)!='' ){
                $empresa = $r->empresa2;
            }
            else{
                $empresa= implode(",",$r->empresa);
            }
            
            if( $empresa !=''){
                $where.=" AND e.id IN ($empresa) ";
            }
        }
        $sql = "
        SELECT '' AS id,s.sucursal AS ode, e.empresa AS empresa, 
        IF( mmd.especialidad_id IS NULL, IF( mc.tipo_curso=2, 'Seminario', 'Curso Libre' ), me.especialidad) AS formacion
        , mc.curso AS curso, mp.dia AS frecuencia, CONCAT( TIME(mp.fecha_inicio),' - ',TIME(mp.fecha_final)) horario
        , DATE(mp.fecha_inicio) AS inicio
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-13,mm.id,NULL )) f14
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-12,mm.id,NULL )) f13
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-11,mm.id,NULL )) f12
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-10,mm.id,NULL )) f11
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-9,mm.id,NULL )) f10
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-8,mm.id,NULL )) f9
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-7,mm.id,NULL )) f8
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-6,mm.id,NULL )) f7
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-5,mm.id,NULL )) f6
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-4,mm.id,NULL )) f5
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-3,mm.id,NULL )) f4
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-2,mm.id,NULL )) f3
        ,COUNT(IF( mm.fecha_matricula=CURDATE()-1,mm.id,NULL )) f2
        ,COUNT(IF( mm.fecha_matricula=CURDATE(),mm.id,NULL )) f1
        ,COUNT(IF( mm.fecha_matricula BETWEEN CURDATE()-13 AND CURDATE()-7,mm.id,NULL )) sa
        ,COUNT(IF( mm.fecha_matricula BETWEEN CURDATE()-6 AND CURDATE(),mm.id,NULL )) ud
        ,COUNT( mm.id ) total, mp.meta_max, mp.meta_min, mp.fecha_campaña
        ,IF(DATEDIFF(CURDATE(),mp.fecha_campaña) >=0,DATEDIFF(CURDATE(),mp.fecha_campaña),0) AS dias_campaña
        ,IF(DATEDIFF(CURDATE(),DATE(mp.fecha_inicio)) >=0,0,(DATEDIFF(DATE(mp.fecha_inicio),CURDATE()) )) AS dias_falta
        ,'' ind_sa, '' ind_ud, '' pro_df, '' pro_fin, '' falta_meta, '' obs
        FROM mat_matriculas AS mm 
        INNER JOIN mat_matriculas_detalles AS mmd ON mmd.matricula_id = mm.id AND mmd.estado = 1 
        INNER JOIN personas AS p ON p.id = mm.persona_id
        INNER JOIN mat_cursos AS mc ON mc.id = mmd.curso_id 
        INNER JOIN empresas AS e ON e.id = mc.empresa_id 
        INNER JOIN mat_programaciones AS mp ON mp.id = mmd.programacion_id
        INNER JOIN sucursales AS s ON s.id=mp.sucursal_id 
        LEFT JOIN mat_especialidades AS me ON me.id = mmd.especialidad_id 
        WHERE mm.estado=1 
        $where
        GROUP BY s.sucursal, e.empresa, mmd.especialidad_id, mc.tipo_curso, me.especialidad, mc.curso, mp.dia
        , mp.fecha_inicio, mp.fecha_final, mp.meta_max, mp.meta_min, mp.fecha_campaña";

        $result= DB::select($sql);

        return $result;
    }

    public static function runExportIndiceMatriculacion($r)
    {
        $rsql= ReporteAvanzado::runIndiceMatriculacion($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,30,30,15,17,10
            ,7,7,7,7,7,7,7,7,7,7,7,7,7,7,9.5,9.5,9.5
            ,9.5,9.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,15
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
            'DATOS DE LA FORMACIÓN CONTINUA','INSCRIPCIÓN DE LA SEMANA ANTERIOR'
            ,'INSCRIPCIÓN ÚLTIMOS 7 DÍAS','INSCRIPCIONES','CALCULO DE ÍNDICE DE INSCRIPCIÓN');

        $valIni=66;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(6,6,6,2,10);
        $colorTit=array('#DDEBF7','#E2EFDA','#E2EFDA','#E2EFDA','#FFF2CC');
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
            'N°','Ode','Empresa','Carrera / Módulo','Inicio / Curso','Frecuencia','Horario','Fecha de Inicio'
            
            ,date('Y-m-d',strtotime('-13 day')),date('Y-m-d',strtotime('-12 day')),date('Y-m-d',strtotime('-11 day'))
            ,date('Y-m-d',strtotime('-10 day')),date('Y-m-d',strtotime('-9 day')),date('Y-m-d',strtotime('-8 day'))
            ,date('Y-m-d',strtotime('-7 day')),date('Y-m-d',strtotime('-6 day')),date('Y-m-d',strtotime('-5 day'))
            ,date('Y-m-d',strtotime('-4 day')),date('Y-m-d',strtotime('-3 day')),date('Y-m-d',strtotime('-2 day'))
            ,date('Y-m-d',strtotime('-1 day')),date('Y-m-d'),'Semana Anterior','Últimos 7 días','Total Inscrito'
            
            ,'Meta Máxima','Meta Mínima','Inicio Campaña','Días Campaña','Días que falta','Índice Semana Anterior'
            ,'Índice Últimos 7 días','Proy. días Faltantes','Proy. Final','Falta para lograr meta','Observación'
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
        $r['max']='AJ'; // Max. Celda en LETRA
        return $r;
    }

    public static function runMedioCaptacion($r)
    {
        $id=Auth::user()->id;
        $where='';
        if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
            $inicial=trim($r->fecha_inicial);
            $final=trim($r->fecha_final);
            if( $inicial !=''AND $final!=''){
                $where.=" AND DATE(mp.fecha_inicio) BETWEEN '$inicial' AND '$final' ";
            }
        }

        if( $r->has("fecha_inscripcion_inicial") AND $r->has("fecha_inscripcion_final")){
            $inicial=trim($r->fecha_inscripcion_inicial);
            $final=trim($r->fecha_inscripcion_final);
            if( $inicial !=''AND $final!=''){
                $where.=" AND DATE(mm.fecha_matricula) BETWEEN '$inicial' AND '$final' ";
            }
        }

        if( $r->has("sucursal") OR $r->has('sucursal2') ){
            $sucursal = '';
            if( $r->has('sucursal2') AND trim($r->sucursal2)!='' ){
                $sucursal = $r->sucursal2;
            }
            else{
                $sucursal= implode(",",$r->sucursal);
            }

            if( $sucursal !=''){
                $where.=" AND s.id IN ($sucursal) ";
            }
        }

        if( $r->has("empresa") OR $r->has('empresa2') ){
            $empresa = '';
            if( $r->has('empresa2') AND trim($r->empresa2)!='' ){
                $empresa = $r->empresa2;
            }
            else{
                $empresa= implode(",",$r->empresa);
            }
            
            if( $empresa !=''){
                $where.=" AND e.id IN ($empresa) ";
            }
        }
        $empresas = explode(",",$empresa);
        $detemp = '';
        for ($i=0; $i < count($empresas); $i++) { 
            $detemp .= ", COUNT(IF(e.id=".$empresas[$i].", mm.id, NULL)) e".($i+1);
        }
        $sql = "
        SELECT s.sucursal AS ode,'' nro, meca.medio_captacion
        $detemp
        , COUNT(mm.id) total
        FROM mat_matriculas AS mm 
        INNER JOIN mat_matriculas_detalles AS mmd ON mmd.matricula_id = mm.id AND mmd.estado = 1 
        INNER JOIN mat_cursos AS mc ON mc.id = mmd.curso_id 
        INNER JOIN empresas AS e ON e.id = mc.empresa_id 
        INNER JOIN mat_programaciones AS mp ON mp.id = mmd.programacion_id
        INNER JOIN sucursales AS s ON s.id=mp.sucursal_id 
        INNER JOIN mat_medios_captaciones meca ON meca.id=mm.medio_captacion_id
        WHERE mm.estado = 1
        $where
        GROUP BY s.sucursal, meca.medio_captacion
        ORDER BY s.sucursal, meca.medio_captacion";

        $result= DB::select($sql);

        return $result;
    }

    public static function runExportMedioCaptacion($r)
    {
        $rsql= ReporteAvanzado::runMedioCaptacion($r);

        if( $r->has("empresa") OR $r->has('empresa2') ){
            $empresa = '';
            if( $r->has('empresa2') AND trim($r->empresa2)!='' ){
                $empresa = $r->empresa2;
            }
            else{
                $empresa= implode(",",$r->empresa);
            }
        }
        $empresas = explode(",",$empresa);

        $length=array('A'=>5);
        $pos=array(
            20,8,30
            ,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5
            ,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5,9.5
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

        $cabeceraTit=array('MEDIOS DE CAPTACIÓN');

        $valIni=65;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(count($empresas)+3);
        $colorTit=array('#E2EFDA');
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
            'Ode','N° Orden','Medio de Captación'
        );
        $cantempresas= array('','','Totales:');
        
        $valauxIni=68;
        $estaticoaux='';
        for ($i=0; $i < count($empresas); $i++) { 
            $empresa = ReporteAvanzado::find($empresas[$i]);
            array_push($cabecera, $empresa->empresa);
            array_push($cantempresas, '=SUM('.$estaticoaux.chr($valauxIni).'4:'.$estaticoaux.chr($valauxIni).(count($rsql)+3).')');
            $valauxIni++;
            if( $valauxIni>90 ){
                $valauxIni=65;
                $estaticoaux='A';
            }
        }
        array_push($cantempresas, '=SUM('.$estaticoaux.chr($valauxIni).'4:'.$estaticoaux.chr($valauxIni).(count($rsql)+3).')');
        array_push($cabecera, 'Total');
        $campos=array('');

        $r['data']=$rsql;
        $r['campos']=$campos;
        $r['cabecera']=$cabecera;
        $r['length']=$length;
        $r['cabeceraTit']=$cabeceraTit;
        $r['lengthTit']=$lengthTit;
        $r['colorTit']=$colorTit;
        $r['lengthDet']=$lengthDet;
        $r['cantempresas']= $cantempresas;
        $r['max']=$estaticoFin.chr($valFin); // Max. Celda en LETRA
        return $r;
    }

    public static function runVendedorComision($r)
    {
        $id=Auth::user()->id;
        $where='';$where2='';
        if( $r->has("fecha_matricula") ){
            $fecha_matricula=trim($r->fecha_matricula);
            if( $fecha_matricula!=''){
                $where2.=" AND DATE_FORMAT(mm.fecha_matricula, '%Y-%m') = '$fecha_matricula' ";
            }
        }

        if( $r->has("vendedor") OR $r->has('vendedor2') ){
            $vendedor = '';
            if( $r->has('vendedor2') AND trim($r->vendedor2)!='' ){
                $vendedor = $r->vendedor2;
            }
            else{
                $vendedor= implode(",",$r->vendedor);
            }

            if( $vendedor !=''){
                $where.=" AND meca.id IN ($vendedor) ";
                $where2.=" AND mm.medio_captacion_id IN ($vendedor) ";
            }
        }

        if( $r->has("empresa") OR $r->has('empresa2') ){
            $empresa = '';
            if( $r->has('empresa2') AND trim($r->empresa2)!='' ){
                $empresa = $r->empresa2;
            }
            else{
                $empresa= implode(",",$r->empresa);
            }
            
            if( $empresa !=''){
                $where.=" AND e.id IN ($empresa) ";
                $where2.=" AND mc.empresa_id IN ($empresa)";
            }
        }
        $empresas = explode(",",$empresa);
        $detemp = '';
        for ($i=0; $i < count($empresas); $i++) { 
            $detemp .= ", COUNT(DISTINCT(IF(m.empresa_id=".$empresas[$i].", m.id, NULL))) e".($i+1);
        }
        $sql = "
        SELECT '' id,GROUP_CONCAT(DISTINCT(t.codigo) ORDER BY t.id) codigo
        , p.paterno, p.materno, p.nombre, p.dni, GROUP_CONCAT(DISTINCT(meca.medio_captacion) ORDER BY t.id) cargo
        , COUNT( DISTINCT(m.id)) total
        $detemp
        FROM personas p
        INNER JOIN mat_trabajadores t ON t.persona_id = p.id AND t.estado=1 AND t.rol_id = 1
        INNER JOIN empresas AS e ON e.id = t.empresa_id 
        INNER JOIN mat_medios_captaciones meca ON meca.id = t.medio_captacion_id 
        LEFT JOIN 
        (SELECT mm.id, mc.empresa_id, mm.persona_marketing_id
        FROM mat_matriculas AS mm 
        INNER JOIN mat_matriculas_detalles AS md ON md.matricula_id = mm.id AND md.estado=1 
        INNER JOIN mat_cursos AS mc ON mc.id = md.curso_id 
        WHERE mm.estado = 1
        $where2
        ) m ON m.persona_marketing_id = p.id 
        WHERE p.estado = 1
        $where
        GROUP BY p.paterno, p.materno, p.nombre, p.dni
        ";

        $result= DB::select($sql);

        return $result;
    }

    public static function runExportVendedorComision($r)
    {
        $rsql= ReporteAvanzado::runVendedorComision($r);
        $mes = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SETIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
        $messeleccionado = '';
        if( $r->has("fecha_matricula") ){
            $fecha_matricula=trim($r->fecha_matricula);
            if( $fecha_matricula!=''){
                $messeleccionado = $mes[substr($fecha_matricula, 5)*1];
            }
        }

        if( $r->has("empresa") OR $r->has('empresa2') ){
            $empresa = '';
            if( $r->has('empresa2') AND trim($r->empresa2)!='' ){
                $empresa = $r->empresa2;
            }
            else{
                $empresa= implode(",",$r->empresa);
            }
        }
        $empresas = explode(",",$empresa);

        $length=array('A'=>5);
        $pos=array(
            5,15,15,15,15,10,20
            ,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5
            ,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5,10.5
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

        $cabeceraTit=array('PERSONA MARKETING','RESUMEN');

        $valIni=65;
        $min=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(6,count($empresas)+3);
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
            'N°','Código','Paterno','Materno','Nombre','DNI','Cargo','Totales'
        );
        
        $valauxIni=68;
        $estaticoaux='';
        for ($i=0; $i < count($empresas); $i++) { 
            $empresa = ReporteAvanzado::find($empresas[$i]);
            array_push($cabecera, $empresa->empresa);
        }
        array_push($cabecera, 'Total Comisión');
        array_push($cabecera, 'Total a Cobrar');
        array_push($cabecera, 'Firma');
        $campos=array('');

        $r['data']=$rsql;
        $r['campos']=$campos;
        $r['cabecera']=$cabecera;
        $r['length']=$length;
        $r['cabeceraTit']=$cabeceraTit;
        $r['lengthTit']=$lengthTit;
        $r['colorTit']=$colorTit;
        $r['lengthDet']=$lengthDet;
        $r['mes']=$messeleccionado;
        $r['max']=$estaticoFin.chr($valFin); // Max. Celda en LETRA
        return $r;
    }
    
}
