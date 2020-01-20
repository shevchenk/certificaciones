<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class Tarea extends Model
{
    protected   $table = 'mat_tareas';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $tarea = Tarea::find($r->id);
        $tarea->estado = trim( $r->estadof );
        $tarea->persona_id_updated_at=$usuario;
        $tarea->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $tarea = new Tarea;
        $tarea->tarea = trim( $r->tarea );
        $tarea->rol_id = trim( $r->rol_id );
        $tarea->estado = trim( $r->estado );
        $tarea->persona_id_created_at=$usuario;
        $tarea->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $tarea = Tarea::find($r->id);
        $tarea->tarea = trim( $r->tarea );
        $tarea->rol_id = trim( $r->rol_id );
        $tarea->estado = trim( $r->estado );
        $tarea->persona_id_updated_at=$usuario;
        $tarea->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('mat_tareas AS r')
            ->select(
            'r.id',
            'r.tarea',
            'r.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("tarea") ){
                        $tarea=trim($r->tarea);
                        if( $tarea !='' ){
                            $query->where('r.tarea','like','%'.$tarea.'%');
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
        $result = $sql->orderBy('r.tarea','asc')->paginate(10);
        return $result;
    }
    
    public static function ListTarea($r)
    {  
        $sql=Tarea::select('id','tarea','estado')
            ->where('estado','=','1')
            ->where( 
                function($query) use ($r){
                    if( $r->has("rol_id") ){
                        $rol_id=trim($r->rol_id);
                        if( $rol_id !='' ){
                            $query->where('rol_id',$rol_id);
                        }
                    }
                }
            );
        $result = $sql->orderBy('tarea','asc')->get();
        return $result;
    }
}
