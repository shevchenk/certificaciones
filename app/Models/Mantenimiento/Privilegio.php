<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected   $table = 'privilegios';

    public static function runEditStatus($r)
    {
        $privilegio = Privilegio::find($r->id);
        $privilegio->estado = trim( $r->estadof );
        $privilegio->persona_id_updated_at=1;
        $privilegio->save();
    }

    public static function runNew($r)
    {
        $privilegio = new Privilegio;
        $privilegio->privilegio = trim( $r->privilegio );
        $privilegio->estado = trim( $r->estado );
        $privilegio->persona_id_created_at=1;
        $privilegio->save();
    }

    public static function runEdit($r)
    {
        $privilegio = Privilegio::find($r->id);
        $privilegio->privilegio = trim( $r->privilegio );
        $privilegio->estado = trim( $r->estado );
        $privilegio->persona_id_updated_at=1;
        $privilegio->save();
    }

    public static function runLoad($r)
    {
        $sql=Privilegio::select('id','privilegio','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("privilegio") ){
                        $privilegio=trim($r->privilegio);
                        if( $privilegio !='' ){
                            $query->where('privilegio','like','%'.$privilegio.'%');
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
        $result = $sql->orderBy('privilegio','asc')->paginate(10);
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
