<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class Banco extends Model
{
    protected   $table = 'bancos';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = Banco::find($r->id);
        $medio_captacion->estado = trim( $r->estadof );
        $medio_captacion->persona_id_updated_at=$usuario;
        $medio_captacion->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = new Banco;
        $medio_captacion->banco = trim( $r->banco );
        $medio_captacion->estado = trim( $r->estado );
        $medio_captacion->persona_id_created_at=$usuario;
        $medio_captacion->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $medio_captacion = Banco::find($r->id);
        $medio_captacion->banco = trim( $r->banco );
        $medio_captacion->estado = trim( $r->estado );
        $medio_captacion->persona_id_updated_at=$usuario;
        $medio_captacion->save();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('bancos AS b')
            ->select(
            'b.id',
            'b.banco',
            'b.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("banco") ){
                        $banco=trim($r->banco);
                        if( $banco !='' ){
                            $query->where('b.banco','like','%'.$banco.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('b.estado',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('b.banco','asc')->paginate(10);
        return $result;
    }
    
    public static function ListBanco($r)
    {  
        $sql=Banco::select('id','banco','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('banco','asc')->get();
        return $result;
    }
}
