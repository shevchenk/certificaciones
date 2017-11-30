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
                $join->on('mc.id','=','mp.curso_id');

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
                 DB::raw('(mm.monto_pago_inscripcion+mm.monto_pago+SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado)+SUM(mm.monto_promocion)) total'),
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
                     DB::raw('GROUP_CONCAT( IF(mp.sucursal_id=1,"OnLine","Presencial") ORDER BY mmd.id SEPARATOR "\n") modalidad'),
                     DB::raw('GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") cursos'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.nro_pago,"") ORDER BY mmd.id SEPARATOR "\n") nro_pago_c'),
                     DB::raw('GROUP_CONCAT( IFNULL(mmd.monto_pago,0) ORDER BY mmd.id SEPARATOR "\n") monto_pago_c'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n") nro_pago_certificado'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n") monto_pago_certificado'),
                     'mm.nro_promocion','mm.monto_promocion',
                     DB::raw('SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado) subtotal'),
                     DB::raw('(mm.monto_pago_inscripcion+mm.monto_pago+SUM(mmd.monto_pago)+SUM(mmd.monto_pago_certificado)+SUM(mm.monto_promocion)) total'),
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

        $cabecantLetra=array(
            'A3:I3','J3:L3','M3:N3','O3:P3',
            'Q3:V3','W3:X3','Y3:Z3','AA3:AD3'
        );

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
        $r['max']='AD';
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
                $join->on('mc.id','=','mp.curso_id');

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
}
