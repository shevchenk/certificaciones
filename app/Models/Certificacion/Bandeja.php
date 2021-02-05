<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Certificacion\BandejaHistorico;
use App\Models\Proceso\Alumnos;
use App\Models\Mantenimiento\CertificadoEstado;

class Bandeja extends Model
{
    protected   $table = 'certificados';

    public static function runLoad($r)
    {
        $sql=DB::table('alumnos AS a')
            ->join('certificados AS c',function($join){
                $join->on('c.alumno_id','=','a.id')
                ->where('c.estado','=',1);
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','a.sucursal_id')
                ->where('s.estado','=',1);
            })
            ->join('certificados_estados AS ce',function($join){
                $join->on('ce.id','=','c.certificado_estado_id')
                ->where('ce.estado','=',1);
            })
            ->select('c.id','s.sucursal', 'a.dni', 'a.paterno', 'a.materno', 'a.nombre', 'a.certificado AS tramite', 
            'c.fecha_estado_certificado AS fecha_ingreso', 'c.created_at AS fecha_tramite', 
            'a.direccion', 'a.referencia', 'a.region', 'a.provincia', 'a.distrito', 'a.nota_certificado', 'a.tipo_certificado',
             'c.fecha_pago', 'c.nro_pago', 'ce.estado_certificado','a.sucursal_id')
            ->where( 
                function($query) use ($r){
                    if( $r->certificado_estado_id!='' ){
                        $query->where('ce.id','=',$r->certificado_estado_id)
                            ->where('a.estado','=',1);
                    }

                    if( $r->has("sucursal") ){
                        $sucursal=trim($r->sucursal);
                        if( $sucursal !='' ){
                            $query->where('s.sucursal','like','%'.$sucursal.'%');
                        }
                    }

                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('a.dni','like',$dni.'%');
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('a.paterno','like','%'.$paterno.'%');
                        }
                    }

                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('a.materno','like','%'.$materno.'%');
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('a.nombre','like','%'.$nombre.'%');
                        }
                    }

                    if( $r->has("tramite") ){
                        $tramite=trim($r->tramite);
                        if( $tramite !='' ){
                            $query->where('a.certificado','like','%'.$tramite.'%');
                        }
                    }

                    if( $r->has("fecha_ingreso") ){
                        $fecha_ingreso=trim($r->fecha_ingreso);
                        if( $fecha_ingreso !='' ){
                            $query->where('c.fecha_estado_certificado','like',$fecha_ingreso.'%');
                        }
                    }

                    if( $r->has("fecha_tramite") ){
                        $fecha_tramite=trim($r->fecha_tramite);
                        if( $fecha_tramite !='' ){
                            $query->where('c.created_at','like',$fecha_tramite.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('s.sucursal','asc')->paginate(10);
        return $result;
    }

        public static function runLoadteleoperada($r)
    {
        $sql=DB::table('alumnos AS a')
            ->join('certificados AS c',function($join){
                $join->on('c.alumno_id','=','a.id')
                ->where('c.estado','=',1);
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','a.sucursal_id')
                ->where('s.estado','=',1);
            })
            ->join('certificados_estados AS ce',function($join){
                $join->on('ce.id','=','c.certificado_estado_id')
                ->where('ce.estado','=',1);
            })
            ->select('c.id','s.sucursal', 'a.dni', 'a.paterno', 'a.materno', 'a.nombre', 'a.certificado AS tramite', 
            'c.fecha_estado_certificado AS fecha_ingreso', 'c.created_at AS fecha_tramite', 
            'a.direccion', 'a.referencia', 'a.region', 'a.provincia', 'a.distrito', 'a.nota_certificado', 'a.tipo_certificado',
             'c.fecha_pago', 'c.nro_pago', 'ce.estado_certificado','a.sucursal_id',
            DB::raw("IFNULL((SELECT CONCAT('Respuesta: ',CASE pad.estado_contesta  WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END,'<br>Detalle: ',IFNULL(mc.detalle,''),'<br>Observación: ',observacion,'<br>Fecha: ',pad.created_at)
                        FROM pago_alumno_detalles pad
                        LEFT JOIN mat_contesta mc ON mc.id=pad.contesta_id
                        WHERE pad.certificado_id=c.id
                        AND pad.estado=1
                        AND pad.certificado_estado_id=".$r->certificado_estado_id."
                        ORDER BY pad.created_at DESC
                        LIMIT 0,1),'') as ultima_llamada"))
            ->where( 
                function($query) use ($r){
                    if( $r->certificado_estado_id!='' ){
                        $query->where('ce.id','=',$r->certificado_estado_id)
                            ->where('a.estado','=',1);
                    }

                    if( $r->has("sucursal") ){
                        $sucursal=trim($r->sucursal);
                        if( $sucursal !='' ){
                            $query->where('s.sucursal','like','%'.$sucursal.'%');
                        }
                    }

                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('a.dni','like',$dni.'%');
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('a.paterno','like','%'.$paterno.'%');
                        }
                    }

                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('a.materno','like','%'.$materno.'%');
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('a.nombre','like','%'.$nombre.'%');
                        }
                    }

                    if( $r->has("tramite") ){
                        $tramite=trim($r->tramite);
                        if( $tramite !='' ){
                            $query->where('a.certificado','like','%'.$tramite.'%');
                        }
                    }

                    if( $r->has("fecha_ingreso") ){
                        $fecha_ingreso=trim($r->fecha_ingreso);
                        if( $fecha_ingreso !='' ){
                            $query->where('c.fecha_estado_certificado','like',$fecha_ingreso.'%');
                        }
                    }

                    if( $r->has("fecha_tramite") ){
                        $fecha_tramite=trim($r->fecha_tramite);
                        if( $fecha_tramite !='' ){
                            $query->where('c.created_at','like',$fecha_tramite.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('s.sucursal','asc')->paginate(10);
        return $result;
    }

    public static function runEditStatus($r)
    {
        $id=$r->id;
        $usuario_id = Auth::user()->id;
        
        if( $r->has('id') ){
            for($i=0;$i<count($id);$i++){     
                    $certificado = Bandeja::find($id[$i]);
                    $r->certificado_estado_id=$certificado->certificado_estado_id;
                    $r->sucursal_id=$certificado->sucursal_id;

                    $alumno= Alumnos::find($certificado->alumno_id);

                    $certificados=CertificadoEstado::find($certificado->certificado_estado_id);

                    if( $alumno->tipo_certificado==1 ){
                        if( $r->sucursal_id!=1 ){
                            $r->certificado_estado_id = $certificados->ruta_sede_pago;
                        }
                        else{
                            $r->certificado_estado_id = $certificados->ruta_online_pago;
                        }
                    }
                    elseif( $alumno->tipo_certificado==2 ){
                        if( $r->sucursal_id!=1 ){
                            $r->certificado_estado_id = $certificados->ruta_sede_nopago;
                        }
                        else{
                            $r->certificado_estado_id = $certificados->ruta_online_nopago;
                        }
                    }
                    elseif( $alumno->tipo_certificado==3 ){
                        if( $r->sucursal_id!=1 ){
                            $r->certificado_estado_id = $certificados->ruta_sede_snpago;
                        }
                        else{
                            $r->certificado_estado_id = $certificados->ruta_online_snpago;
                        }
                    }

                    $certificado->certificado_estado_id=$r->certificado_estado_id;

                    if( $r->has('nro_pago') AND trim($r->nro_pago)!='' ){
                        $certificado->nro_pago = $r->nro_pago;
                        $certificado->monto_pago = $r->monto_pago;
                        $certificado->fecha_pago = $r->fecha_pago;
                        $certificado->tipo_pago = $r->tipo_pago;
                    }

                    $certificado->persona_id_updated_at=$usuario_id;
                    $certificado->save();

                    $certificado_hist = new BandejaHistorico;
                    $certificado_hist->certificado_id=$certificado->id;
                    $certificado_hist->certificado_estado_id=$r->certificado_estado_id;
                    $certificado_hist->persona_id_created_at=$usuario_id;
                    $certificado_hist->save(); 
            }
        }
    }

}
