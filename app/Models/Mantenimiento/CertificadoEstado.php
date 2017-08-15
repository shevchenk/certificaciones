<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class CertificadoEstado extends Model
{
    protected   $table = 'certificados_estados';

    public static function runEditStatus($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = CertificadoEstado::find($r->id);
        $certificadoestado->estado = trim( $r->estadof );
        $certificadoestado->persona_id_updated_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runNew($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = new CertificadoEstado;
        $certificadoestado->estado_certificado = trim( $r->estado_certificado );
        $certificadoestado->detalle = trim( $r->detalle );
        $certificadoestado->tiempo_espera = trim( $r->tiempo_espera );
        $certificadoestado->estado = trim( $r->estado );
        $certificadoestado->persona_id_created_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runEdit($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = CertificadoEstado::find($r->id);
        $certificadoestado->estado_certificado = trim( $r->estado_certificado );
        $certificadoestado->detalle = trim( $r->detalle );
        $certificadoestado->tiempo_espera = trim( $r->tiempo_espera );
        $certificadoestado->estado = trim( $r->estado );
        $certificadoestado->persona_id_updated_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runLoad($r)
    {

        $sql=CertificadoEstado::select('id','estado_certificado','detalle','tiempo_espera','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("estado_certificado") ){
                        $estado_certificado=trim($r->estado_certificado);
                        if( $estado_certificado !='' ){
                            $query->where('estado_certificado','like','%'.$estado_certificado.'%');
                        }
                    }
                    if( $r->has("detalle") ){
                        $detalle=trim($r->detalle);
                        if( $detalle !='' ){
                            $query->where('detalle','like','%'.$detalle.'%');
                        }
                    }
                    if( $r->has("tiempo_espera") ){
                        $tiempo_espera=trim($r->tiempo_espera);
                        if( $tiempo_espera !='' ){
                            $query->where('tiempo_espera','like','%'.$tiempo_espera.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('estado_certificado','asc')->paginate(10);
        return $result;
    }
    
    public static function ListCertificadoEstado($r)
    {
        $sql=CertificadoEstado::select('id','estado_certificado','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('estado_certificado','asc')->get();
        return $result;
    }
    

}
