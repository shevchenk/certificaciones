<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use DB;

class Opcion extends Model
{
    protected   $table = 'opciones';

    public static function runEditStatus($r)
    {
        $opcion = Opcion::find($r->id);
        $opcion->estado = trim( $r->estadof );
        $opcion->persona_id_updated_at=1;
        $opcion->save();
    }

    public static function runNew($r)
    {
        $opcion = new Opcion;
        $opcion->opcion = trim( $r->opcion );
        $opcion->estado = trim( $r->estado );
        $opcion->persona_id_created_at=1;
        $opcion->save();
    }

    public static function runEdit($r)
    {
        $opcion = Opcion::find($r->id);
        $opcion->opcion = trim( $r->opcion );
        $opcion->estado = trim( $r->estado );
        $opcion->persona_id_updated_at=1;
        $opcion->save();
    }

        public static function runLoad($r)
    {
        $sql=DB::table('opciones as o')
                    ->join('menus as m', 'o.menu_id', '=', 'm.id')
                    ->select(
                        'o.id',
                        'o.opcion',
                        'o.ruta',
                        'o.estado',
                        'm.menu as menu',
                        'o.menu_id'
                    )
            ->where( 
                function($query) use ($r){
                    if( $r->has("opcion") ){
                        $opcion=trim($r->opcion);
                        if( $opcion !='' ){
                            $query->where('opcion','like','%'.$opcion.'%');
                        }   
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','=',''.$estado.'');
                        }
                    }
                }
            );
        $result = $sql->orderBy('opcion','asc')->paginate(10);
        return $result;
    }

/*    public static function runLoad($r)
    {
        $sql=Opcion::select('id','opcion','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("opcion") ){
                        $opcion=trim($r->opcion);
                        if( $opcion !='' ){
                            $query->where('opcion','like','%'.$opcion.'%');
                        }   
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','=',''.$estado.'');
                        }
                    }
                }
            );
        $result = $sql->orderBy('opcion','asc')->paginate(10);
        return $result;
    }*/
    
    public static function ListOpcion($r)
    {  
        $sql=Opcion::select('id','opcion','class_icono','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('opcion','asc')->get();
        return $result;
    }
}
