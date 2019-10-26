<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB; //BD

class Llamada extends Model
{
    protected   $table = 'llamadas';

    public static function RegistrarLlamada($r)
    {
        $empresa_id= Auth::user()->empresa_id;
        DB::beginTransaction();
        DB::table('llamadas as ll')
        ->Join('mat_trabajadores AS t', function($join) use($empresa_id){
                $join->on('t.id','=','ll.trabajador_id')
                ->where('t.empresa_id','=',$empresa_id);
        })
        ->where( 
            function($query) use ($r){
                if( $r->has('persona_id') AND $r->persona_id!='' ){
                    $query->where('ll.persona_id','=', $r->persona_id);
                }
            }
        )
        ->where('ll.ultimo_registro',1)
        ->update([
            'll.ultimo_registro' => 0,
            'll.persona_id_updated_at' => Auth::user()->id,
            'll.updated_at' => date('Y-m-d H:i:s')
        ]);

        $llamada= new Llamada;
        $llamada->trabajador_id=$r->teleoperadora;
        $llamada->persona_id=$r->persona_id;
        $llamada->fecha_llamada=$r->fecha;
        if( Input::has('comentario') AND trim( $r->comentario )!='' ){
            $llamada->comentario=$r->comentario;
        }
        $llamada->objecion=$r->objecion;
        $llamada->pregunta=$r->pregunta;
        $llamada->ultimo_registro=1;

        $llamada->tipo_llamada_id=$r->tipo_llamada;

        if( Input::has('fechas') AND trim( $r->fechas )!='' ){
            $llamada->fechas=$r->fechas;
        }

        if( Input::has('detalle_tipo_llamada') AND trim( $r->detalle_tipo_llamada )!='' ){
            $llamada->tipo_llamada_sub_id=$r->sub_tipo_llamada;
            $llamada->tipo_llamada_sub_detalle_id=$r->detalle_tipo_llamada;
        }

        $llamada->persona_id_created_at=Auth::user()->id;
        $llamada->save();
        DB::commit();
        return $llamada;
    }

    public static function CargarInfo($r)
    {
        $empresa_id= Auth::user()->empresa_id;

        $sql=DB::table('personas_captadas AS pc')
            ->select('pc.interesado AS info',DB::raw('DATE(pc.created_at) AS fecha_info'))
            ->where(
                function($query) use($r){
                    if( $r->has("persona_id") ){
                        $persona_id=trim($r->persona_id);
                        if( $persona_id !='' ){
                            $query->where('pc.persona_id',$persona_id);
                        }
                    }
                }
            )
            ->where('pc.empresa_id', $empresa_id)
            ->where('pc.estado',1)
            ->orderBy('pc.created_at','desc')
            ->get();

        return $sql;
    }

    public static function CargarLlamada($r)
    {
        $empresa_id= Auth::user()->empresa_id;
        $sql=DB::table('llamadas AS ll')
            ->Join('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','ll.tipo_llamada_id');
            })
            ->Join('mat_trabajadores AS tr', function($join) use($empresa_id){
                $join->on('tr.id','=','ll.trabajador_id')
                ->where('tr.empresa_id','=',$empresa_id);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','tr.persona_id');
            })
            ->Join('personas AS p2', function($join){
                $join->on('p2.id','=','ll.persona_id');
            })
            ->select(
            'll.fecha_llamada',DB::raw('CONCAT(p.paterno,\' \',p.materno,\', \',p.nombre) AS teleoperador')
            ,DB::raw('CONCAT(p2.paterno,\' \',p2.materno,\', \',p2.nombre) AS persona'),'p2.dni'
            ,'tl.tipo_llamada','ll.fechas','ll.comentario','ll.id'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("persona_id") ){
                        $persona_id=trim($r->persona_id);
                        if( $persona_id !='' ){
                            $query->where('ll.persona_id',$persona_id);
                        }
                    }
                    if( $r->has('pendiente') AND $r->pendiente==1 ){
                        $query->where('ll.fechas','>=',date('Y-m-d'))
                        ->where('ll.ultimo_registro',1);
                    }
                }
            );

            $result=array();
            if( $r->has('pendiente') AND $r->pendiente==1 ){
                $sql->Join('personas_distribuciones AS pd', function($join) use( $r ){
                    $join->on('pd.persona_id','=','p2.id')
                    ->where('pd.estado',1)
                    ->where('pd.trabajador_id',$r->teleoperadora);
                });
                $sql->addSelect(DB::raw('p2.id AS persona_id,p2.carrera,p2.empresa,p2.fuente,pd.fecha_distribucion,p2.telefono,p2.celular,current_date() AS hoy'));
                $result= $sql->orderBy('ll.fechas','asc')->get();
            }
            else{
                $result = $sql->orderBy('ll.fecha_llamada','desc')->get();
            }
            

        return $result;
    }

    public static function GuardarAsignacion($r)
    {
        DB::beginTransaction();
        $empresa_id= Auth::user()->empresa_id;
        $cant= count($r->trabajador);
        $trabajador= $r->trabajador;
        $asig= $r->asig_trabajador;
        $fecha_ini= $r->fecha_ini;
        $fecha_fin= $r->fecha_fin;
        //$tipo_asignar= trim($r->tipo_asignar);
        $usuario= Auth::user()->id;
        $pos=0;
        $filtros_array=array();
        $chknoasig = $r->chknoasig;
        $chknocall = $r->chknocall;
        $chkinteresado = $r->chkinteresado;
        $chkpendiente = $r->chkpendiente;
        $chknointeresado = $r->chknointeresado;
        $chkotros = $r->chkotros;

        if( !is_array($chknoasig) ){ $chknoasig=array(); }
        if( !is_array($chknocall) ){ $chknocall=array(); }
        if( !is_array($chkinteresado) ){ $chkinteresado=array(); }
        if( !is_array($chkpendiente) ){ $chkpendiente=array(); }
        if( !is_array($chknointeresado) ){ $chknointeresado=array(); }
        if( !is_array($chkotros) ){ $chkotros=array(); }
        
        for ($i=0; $i < count($chknoasig) ; $i++) { 
            $detchk= explode("|",$chknoasig[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NULL
            )";
            array_push($filtros_array, $filtro);
        }
        for ($i=0; $i < count($chknocall) ; $i++) { 
            $detchk= explode("|",$chknocall[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NOT NULL 
            AND l.persona_id IS NULL
            )";
            array_push($filtros_array, $filtro);
        }
        for ($i=0; $i < count($chkinteresado) ; $i++) { 
            $detchk= explode("|",$chkinteresado[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NOT NULL 
            AND l.tipo_llamada_id=1
            )";
            array_push($filtros_array, $filtro);
        }
        for ($i=0; $i < count($chkpendiente) ; $i++) { 
            $detchk= explode("|",$chkpendiente[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NOT NULL 
            AND l.tipo_llamada_id=2
            )";
            array_push($filtros_array, $filtro);
        }
        for ($i=0; $i < count($chknointeresado) ; $i++) { 
            $detchk= explode("|",$chknointeresado[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NOT NULL 
            AND l.tipo_llamada_id=8
            )";
            array_push($filtros_array, $filtro);
        }
        for ($i=0; $i < count($chkotros) ; $i++) { 
            $detchk= explode("|",$chkotros[$i]);
            $filtro=
            "(
            pc.ad_name='".$detchk[0]."' 
            AND pc.interesado='".$detchk[1]."'
            AND DATE(pc.created_at)='".$detchk[2]."'
            AND d.persona_id IS NOT NULL 
            AND l.tipo_llamada_id<>1 
            AND l.tipo_llamada_id<>2 
            AND l.tipo_llamada_id<>8
            )";
            array_push($filtros_array, $filtro);
        }

        $filtros= implode(" OR ",$filtros_array);

        for ($i=0; $i < $cant ; $i++) { 
            if( $asig[$i]*1>0 ){
                $sql="  INSERT INTO personas_distribuciones 
                        (persona_id, trabajador_id, fecha_distribucion, estado, created_at, persona_id_created_at)
                        SELECT pc.persona_id, $trabajador[$i], CURDATE(), 1, NOW(), $usuario
                        FROM personas_captadas pc 
                        INNER JOIN empresas e ON e.id=pc.empresa_id AND e.id = $empresa_id
                        LEFT JOIN (
                            SELECT ll.persona_id, t.empresa_id, MIN(ll.tipo_llamada_id) tipo_llamada_id
                            FROM llamadas ll
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
                        AND (
                            $filtros
                        )
                        ORDER BY pc.persona_id
                        LIMIT $pos,$asig[$i]";
                DB::insert($sql);
                $pos=$pos+$asig[$i];
            }
        }

        $sql="  UPDATE personas_distribuciones pd 
                INNER JOIN mat_trabajadores t ON t.id=pd.trabajador_id AND t.empresa_id='$empresa_id'
                INNER JOIN (
                    SELECT MAX(pd2.id) idmax, pd2.persona_id
                    FROM personas_distribuciones pd2 
                    INNER JOIN mat_trabajadores t2 ON t2.id=pd2.trabajador_id AND t2.empresa_id='$empresa_id'
                    WHERE pd2.estado=1 
                    GROUP BY pd2.persona_id
                    HAVING COUNT(pd2.id)>1
                ) un ON un.persona_id=pd.persona_id
                SET pd.estado=0
                WHERE pd.id!=un.idmax
                AND pd.estado=1";
        DB::update($sql);
        
        DB::commit();
    }
    // --
}
