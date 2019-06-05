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
        DB::beginTransaction();
        DB::table('llamadas')
        ->where( 
            function($query) use ($r){
                if( $r->has('persona_id') AND $r->persona_id!='' ){
                    $query->where('persona_id','=', $r->persona_id);
                }
            }
        )
        ->where('ultimo_registro',1)
        ->update([
            'ultimo_registro' => 0,
            'persona_id_updated_at' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $llamada= new Llamada;
        $llamada->trabajador_id=$r->teleoperadora;
        $llamada->persona_id=$r->persona_id;
        $llamada->fecha_llamada=$r->fecha;
        if( Input::has('comentario') AND trim( $r->comentario )!='' ){
            $llamada->comentario=$r->comentario;
        }

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

    public static function CargarLlamada($r)
    {
        $sql=DB::table('llamadas AS ll')
            ->Join('tipo_llamadas AS tl', function($join){
                $join->on('tl.id','=','ll.tipo_llamada_id');
            })
            ->Join('mat_trabajadores AS tr', function($join){
                $join->on('tr.id','=','ll.trabajador_id');
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
                        $query->where('ll.fechas','>=',date('Y-m-d'));
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
    // --
}
