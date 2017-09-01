<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Trabajador extends Model
{
    protected   $table = 'mat_trabajadores';

    
    public static function runEditStatus($r)
    {
        $trabajador = Trabajador::find($r->id);
        $trabajador->estado = trim( $r->estadof );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runNew($r)
    {

        $trabajador = new Trabajador;
        $trabajador->persona_id = trim( $r->persona_id );
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->codigo = trim( $r->codigo );
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_created_at=Auth::user()->id;
        $trabajador->save();
    }
    public static function runEdit($r)
    {

        $trabajador = Trabajador::find($r->id);
        $trabajador->persona_id = trim( $r->persona_id );
        $trabajador->rol_id = trim( $r->rol_id );
        $trabajador->codigo = trim( $r->codigo );
        $trabajador->estado = trim( $r->estado );
        $trabajador->persona_id_updated_at=Auth::user()->id;
        $trabajador->save();
    }

    public static function runLoad($r)
    {
        $sql=Trabajador::select('mat_trabajadores.codigo','mat_trabajadores.id','mat_trabajadores.persona_id',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as trabajador')
                                ,'mat_trabajadores.rol_id','r.rol','mat_trabajadores.estado')
             ->join('personas as p','p.id','=','mat_trabajadores.persona_id')
             ->join('mat_roles as r','r.id','=','mat_trabajadores.rol_id')
             ->where( 
                function($query) use ($r){
                    if( $r->has("trabajador") ){
                        $trabajador=trim($r->trabajador);
                        if( $trabajador !='' ){
                            $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) like "%'.$trabajador.'%"');
                        }
                    }
                    if( $r->has("rol") ){
                        $rol=trim($r->rol);
                        if( $rol !='' ){
                            $query->where('r.rol','like','%'.$rol.'%');
                        }
                    }
                    if( $r->has("rol_id") ){
                        $rol_id=trim($r->rol_id);
                        if( $rol_id !='' ){
                            $query->where('mat_trabajadores.rol_id','=',$rol_id);
                        }
                    }
                    if( $r->has("codigo") ){
                        $codigo=trim($r->codigo);
                        if( $codigo !='' ){
                            $query->where('mat_trabajadores.codigo','like','%'.$codigo.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mat_trabajadores.estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('mat_trabajadores.id','asc')->paginate(10);
        return $result;
    }
    

}
