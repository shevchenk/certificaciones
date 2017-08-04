<?php

namespace App\Models\ExpertManage;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected   $table = 'entidad';

    public static function runEditStatus($r)
    {
        $entidad = Entidad::find($r->id);
        $entidad->estado = trim( $r->estadof );
        $entidad->persona_id_updated_at=1;
        $entidad->save();
    }

    public static function runNew($r)
    {
        $entidad = new Entidad;
        $entidad->persona_id = trim( $r->persona_id );
        $entidad->entidad = trim( $r->entidad );
        $entidad->ruc = trim( $r->ruc );
        $entidad->nombre_comercial = trim( $r->nombre_comercial );
        $entidad->igv = trim( $r->igv );
        $entidad->cambio_moneda = trim( $r->cambio_moneda );
        //$entidad->celular = trim( $r->celular );
        
        $entidad->estado = trim( $r->estado );
        $entidad->persona_id_created_at=1;
        $entidad->save();
    }

    public static function runEdit($r)
    {
        $entidad = Entidad::find($r->id);
        $entidad->persona_id = trim( $r->persona_id );
        $entidad->entidad = trim( $r->entidad );
        $entidad->ruc = trim( $r->ruc );
        $entidad->nombre_comercial = trim( $r->nombre_comercial );
        $entidad->igv = trim( $r->igv );
        $entidad->cambio_moneda = trim( $r->cambio_moneda );
        //$entidad->celular = trim( $r->celular );
        
        $entidad->estado = trim( $r->estado );
        $entidad->persona_id_updated_at=1;
        $entidad->save();
    }

    public static function runLoad($r)
    {
        $sql=Entidad::select('entidad.id','entidad.persona_id','entidad.ruc','entidad.entidad','entidad.nombre_comercial',
                             'entidad.igv','entidad.cambio_moneda',
                            'entidad.estado','personas.paterno','personas.materno','personas.nombre')
             ->join('personas','entidad.persona_id','=','personas.id')
             ->where( 
                function($query) use ($r){
                      if( $r->has("persona") ){
                        $persona=trim($r->persona);
                        if( $persona !='' ){
                            $query->whereRaw('CONCAT_WS(" ",personas.paterno,personas.materno,personas.nombre ) like "%'.$persona.'%"');
                        }
                    }
                    if( $r->has("entidad") ){
                        $entidad=trim($r->entidad);
                        if( $entidad !='' ){
                            $query->where('entidad.entidad','like','%'.$entidad.'%');
                        }
                    }
                    if( $r->has("ruc") ){
                        $ruc=trim($r->ruc);
                        if( $ruc !='' ){
                            $query->where('entidad.ruc','like','%'.$ruc.'%');
                        }
                    }
                    if( $r->has("nombre_comercial") ){
                        $nombre_comercial=trim($r->nombre_comercial);
                        if( $nombre_comercial !='' ){
                            $query->where('entidad.nombre_comercial','like','%'.$nombre_comercial.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('entidad.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('persona_id','asc')->paginate(10);
        return $result;
    }
 
}
