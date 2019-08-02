<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class Rol extends Model
{
    protected   $table = 'mat_roles';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $rol = Rol::find($r->id);
        $rol->estado = trim( $r->estadof );
        $rol->persona_id_updated_at=$usuario;
        $rol->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $rol = new Rol;
        $rol->rol = trim( $r->rol );
        $rol->estado = trim( $r->estado );
        $rol->persona_id_created_at=$usuario;
        $rol->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $rol = Rol::find($r->id);
        $rol->rol = trim( $r->rol );
        $rol->estado = trim( $r->estado );
        $rol->persona_id_updated_at=$usuario;
        $rol->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('mat_roles AS r')
            ->select(
            'r.id',
            'r.rol',
            'r.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("rol") ){
                        $rol=trim($r->rol);
                        if( $rol !='' ){
                            $query->where('r.rol','like','%'.$rol.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('r.estado',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('r.rol','asc')->paginate(10);
        return $result;
    }
    
    public static function ListRol($r)
    {  
        $sql=Rol::select('id','rol','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('rol','asc')->get();
        return $result;
    }
}
