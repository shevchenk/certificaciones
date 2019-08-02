<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class Empresa extends Model
{
    protected   $table = 'empresas';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = Empresa::find($r->id);
        $tipo_evaluacion->estado = trim( $r->estadof );
        $tipo_evaluacion->persona_id_updated_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = new Empresa;
        $tipo_evaluacion->empresa = trim( $r->empresa );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_created_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = Empresa::find($r->id);
        $tipo_evaluacion->empresa = trim( $r->empresa );
        $tipo_evaluacion->estado = trim( $r->estado );
        $tipo_evaluacion->persona_id_updated_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('empresas AS e')
            ->select(
            'e.id',
            'e.empresa',
            'e.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("empresa") ){
                        $empresa=trim($r->empresa);
                        if( $empresa !='' ){
                            $query->where('e.empresa','like','%'.$empresa.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('e.estado',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('e.empresa','asc')->paginate(10);
        return $result;
    }
    
    public static function ListEmpresa($r)
    {  
        $sql=Empresa::select('id','empresa','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('empresa','asc')->get();
        return $result;
    }
}
