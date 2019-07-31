<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class MedioPublicitario extends Model
{
    protected   $table = 'medios_publicitarios';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = MedioPublicitario::find($r->id);
        $tipo_evaluacion->estado = trim( $r->estadof );
        $tipo_evaluacion->persona_id_updated_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = new MedioPublicitario;
        $tipo_evaluacion->medio_publicitario = trim( $r->medio_publicitario );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_created_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = MedioPublicitario::find($r->id);
        $tipo_evaluacion->medio_publicitario = trim( $r->medio_publicitario );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_updated_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('medios_publicitarios AS mp')
            ->select(
            'mp.id',
            'mp.medio_publicitario',
            'mp.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("medio_publicitario") ){
                        $medio_publicitario=trim($r->medio_publicitario);
                        if( $medio_publicitario !='' ){
                            $query->where('mp.medio_publicitario','like','%'.$medio_publicitario.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mp.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('mp.medio_publicitario','asc')->paginate(10);
        return $result;
    }
    
    public static function ListMedioPublicitario($r)
    {  
        $sql=MedioPublicitario::select('id','medio_publicitario','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('medio_publicitario','asc')->get();
        return $result;
    }
}
