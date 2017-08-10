<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected   $table = 'privilegios';

    public static function runEditStatus($r)
    {
        $cargo = Privilegio::find($r->id);
        $cargo->estado = trim( $r->estadof );
        $cargo->persona_id_updated_at=1;
        $cargo->save();
    }

    public static function runNew($r)
    {
        $cargo = new Privilegio;
        $cargo->cargo = trim( $r->cargo );
        $cargo->estado = trim( $r->estado );
        $cargo->persona_id_created_at=1;
        $cargo->save();
    }

    public static function runEdit($r)
    {
        $cargo = Privilegio::find($r->id);
        $cargo->cargo = trim( $r->cargo );
        $cargo->estado = trim( $r->estado );
        $cargo->persona_id_updated_at=1;
        $cargo->save();
    }

    public static function runLoad($r)
    {
        $sql=Privilegio::select('id','cargo','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("cargo") ){
                        $cargo=trim($r->cargo);
                        if( $cargo !='' ){
                            $query->where('cargo','like','%'.$cargo.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('cargo','asc')->paginate(10);
        return $result;
    }
    
        public static function ListPrivilegio($r)
    {  
        $sql=Privilegio::select('id','privilegio','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('privilegio','asc')->get();
        return $result;
    }
}
