<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class Bandeja extends Model
{
    protected   $table = 'certificados';

    public static function runLoadAprobado($r)
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
            ->select('s.sucursal', 'a.dni', 'a.paterno', 'a.materno', 'a.nombre', 'a.certificado AS tramite', 
            'c.fecha_estado_certificado AS fecha_ingreso', 'c.created_at AS fecha_tramite', 
            'a.direccion', 'a.referencia', 'a.region', 'a.provincia', 'a.distrito', 'a.nota_certificado', 'a.tipo_certificado',
             'c.fecha_pago', 'c.nro_pago', 'ce.estado_certificado')
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

}