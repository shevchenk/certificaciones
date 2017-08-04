<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Articulo extends Model
{
    protected   $table = 'articulos';

    public static function runEditStatus($r)
    {
        $articulo = Articulo::find($r->id);
        $articulo->estado = trim( $r->estadof );
        $articulo->persona_id_updated_at=Auth::user()->id;
        $articulo->save();
    }

    public static function runNew($r)
    {
        $articulo = new Articulo;
        $articulo->articulo = trim( $r->articulo );
        $articulo->categoria_id = trim( $r->categoria);
        $articulo->estado = trim( $r->estado );
        $articulo->persona_id_created_at=Auth::user()->id;
        $articulo->save();
    }

    public static function runEdit($r)
    {
        $articulo = Articulo::find($r->id);
        $articulo->articulo = trim( $r->articulo );
        $articulo->categoria_id = trim( $r->categoria);
        $articulo->estado = trim( $r->estado );
        $articulo->persona_id_updated_at=Auth::user()->id;
        $articulo->save();
    }

    public static function runLoad($r)
    {
        $sql=Articulo::select('articulos.id','categoria_id','categoria','articulo','articulos.estado')
            ->join('categorias','articulos.categoria_id','=','categorias.id')
            ->where( 
                function($query) use ($r){
                    if( $r->has("articulo") ){
                        $articulo=trim($r->articulo);
                        if( $articulo !='' ){
                            $query->where('articulo','like','%'.$articulo.'%');
                        }
                    }
                    if( $r->has("categoria") ){
                        $categoria=trim($r->categoria);
                        if( $categoria !='' ){
                            $query->where('categorias.categoria','like','%'.$categoria.'%');
                        }
                    }
                    if( $r->has("categoria_id") ){
                        $categoria_id=trim($r->categoria_id);
                        if( $categoria_id !='' ){
                            $query->where('articulos.categoria_id','like','%'.$categoria_id.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('articulos.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('articulo','asc')->get();
        return $result;
    }
    
        public static function ListArticulo($r)
    {
        $sql=Articulo::select('id','articulo','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('articulo','asc')->get();
        return $result;
    }
    

}
