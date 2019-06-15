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

                     DB::raw('GROUP_CONCAT( mc.curso ORDER BY mmd.id SEPARATOR "\n") seminario'),
                     DB::raw('GROUP_CONCAT( mp.fecha_inicio ORDER BY mmd.id SEPARATOR "\n") fecha_inicio'),
                     DB::raw('GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id SEPARATOR "\n") nro_pago'),
                     DB::raw('GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id SEPARATOR "\n") monto_pago'),
                     DB::raw('GROUP_CONCAT( mmd.tipo_pago ORDER BY mmd.id SEPARATOR "\n") tipo_pago'),
                     DB::raw('SUM(mmd.monto_pago_certificado) subtotal'),
                     DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total'),

                     DB::raw('CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre) as cajera'),
                     DB::raw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) as marketing'),
                     DB::raw('CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre) as matricula'),
                    'mm.nro_promocion','mm.monto_promocion','p.empresa')
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
                            WHERE ppv.persona_id='.$id.')')
            ->groupBy('mm.id','p.dni','p.nombre','p.paterno','p.materno','p.telefono','p.celular','p.email','ma.direccion',
                     'mm.fecha_matricula','s.sucursal','mtp.tipo_participante','mm.nro_promocion','mm.monto_promocion','p.empresa',
                     'pcaj.paterno','pcaj.materno','pcaj.nombre',
                     'pmar.paterno','pmar.materno','pmar.nombre',
                     'pmat.paterno','pmat.materno','pmat.nombre');
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
                    'mm.nro_promocion','mm.monto_promocion','p.empresa')
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

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,'I'=>30,
            'J'=>15,'K'=>15,'L'=>15,
            //'M'=>15,'N'=>15,'O'=>15,'P'=>15,
            'M'=>15,'N'=>15,'O'=>15,'P'=>15,'Q'=>15,//''=>15,''=>15,''=>15,''=>15,
            'R'=>15,'S'=>15,
            'T'=>20,'U'=>20,'V'=>20,
            'W'=>15,'X'=>15,'Y'=>15
        );
        $cabecera=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            //'Nro Pago','Monto Pago','Nro Pago','Monto Pago',
            'Seminarios','Fecha Seminario','Nro Pago','Monto Pago','Tipo Pago',//'Curso 2','Nro Pago','Monto Pago','Curso 3','Nro Pago','Monto Pago',
            'Sub Total Sem','Total Pagado',
            'Cajero(a)','Teleoperador(a)','Supervisor(a)',
            'Nro Recibo Promoción','Monto Promoción','Empresa'
        );
        $campos=array(
            '','id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             //'nro_pago_inscripcion','monto_pago_inscripcion',
             //'nro_pago','monto_pago',
             'seminario','fecha_inicio','nro_pago','monto_pago','tipo_pago',
             'subtotal','total',
             'cajera','marketing','matricula',
             'nro_promocion','monto_promocion','empresa'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='Y'; // Max. Celda en LETRA
        return $r;
    }

    public static function runExportSeminarioDetalle($r)
    {
        $rsql= Seminario::runLoadSeminarioDetalle($r);

        $length=array(
            'A'=>5,'B'=>15,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>25,'I'=>30,
            'J'=>15,'K'=>15,'L'=>15,
            //'M'=>15,'N'=>15,'O'=>15,'P'=>15,
            'M'=>15,'N'=>15,'O'=>15,'P'=>15,'Q'=>15,//''=>15,''=>15,''=>15,''=>15,
            'R'=>15,'S'=>15,
            'T'=>20,'U'=>20,'V'=>20,
            'W'=>15,'X'=>15,'Y'=>15
        );
        $cabecera=array(
            'N°','DNI','Nombre','Paterno','Materno','Telefono','Celular','Email','Dirección',
            'Fecha Matrícula','Sucursal','Tipo Participante',
            //'Nro Pago','Monto Pago','Nro Pago','Monto Pago',
            'Seminarios','Fecha Seminario','Nro Pago','Monto Pago','Tipo Pago',//'Curso 2','Nro Pago','Monto Pago','Curso 3','Nro Pago','Monto Pago',
            'Sub Total Sem','Total Pagado',
            'Cajero(a)','Teleoperador(a)','Supervisor(a)',
            'Nro Recibo Promoción','Monto Promoción','Empresa'
        );
        $campos=array(
            '','id','dni','nombre','paterno','materno','telefono','celular','email','direccion',
             'fecha_matricula','sucursal','tipo_participante',
             //'nro_pago_inscripcion','monto_pago_inscripcion',
             //'nro_pago','monto_pago',
             'seminario','fecha_inicio','nro_pago','monto_pago','tipo_pago',
             'subtotal','total',
             'cajera','marketing','matricula',
             'nro_promocion','monto_promocion','empresa'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='Y'; // Max. Celda en LETRA
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
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','m.persona_id');
            })
            ->Join('mat_programaciones AS pr', function($join){
                $join->on('pr.id','=','md.programacion_id');
            })
            ->Join('mat_cursos AS c', function($join){
                $join->on('c.id','=','pr.curso_id');
            })
            ->select(
            DB::raw('UPPER(CONCAT(p.paterno,\' \',p.materno,\' \',p.nombre)) AS persona')
            ,DB::raw('UPPER(c.curso) AS tema'),DB::raw('DATE_FORMAT(pr.fecha_inicio, \'el %d de %M del %Y\') AS fecha_seminario'),
            'm.fecha_matricula AS fecha_inscripcion')
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

        $cabecera2=array(
            'Apellidos y Nombre del Pagante','Tema','Fecha del Seminario','Fecha de Inscripción'
        );

        $r['data']=$rsql;
        $r['cabecera2']=$cabecera2;
        $r['length']=$length;
        $r['max']=chr($min);
        return $r;
    }
}
