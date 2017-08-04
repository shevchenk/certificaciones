<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Categoria extends Model
{
    protected   $table = 'categorias';

    public static function runEditStatus($r)
    {
        $categoria = Categoria::find($r->id);
        $categoria->estado = trim( $r->estadof );
        $categoria->persona_id_updated_at=Auth::user()->id;
        $categoria->save();
    }

    public static function runNew($r)
    {
        $categoria = new Categoria;
        $categoria->categoria = trim( $r->categoria );
        $categoria->estado = trim( $r->estado );
        $categoria->persona_id_created_at=Auth::user()->id;
        $categoria->save();
    }

    public static function runEdit($r)
    {
        $categoria = Categoria::find($r->id);
        $categoria->categoria = trim( $r->categoria );
        $categoria->estado = trim( $r->estado );
        $categoria->persona_id_updated_at=Auth::user()->id;
        $categoria->save();
    }

    public static function runLoad($r)
    {
        $sql=Categoria::select('id','categoria','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("categoria") ){
                        $categoria=trim($r->categoria);
                        if( $categoria !='' ){
                            $query->where('categoria','like','%'.$categoria.'%');
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
        $result = $sql->orderBy('categoria','asc')->get();
        return $result;
    }
    
        public static function ListCategoria($r)
    {
        $sql=Categoria::select('id','categoria','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('categoria','asc')->get();
        return $result;
    }
    

}
