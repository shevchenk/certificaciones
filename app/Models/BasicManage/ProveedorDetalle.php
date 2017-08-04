<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class ProveedorDetalle extends Model
{
    protected   $table = 'proveedores_detalles';

    public static function runEditStatus($r)
    {
        $proveedores_detalles = Auth::user()->id;
        $proveedordetalle = ProveedorDetalle::find($r->id);
        $proveedordetalle->estado = trim( $r->estadof );
        $proveedordetalle->persona_id_updated_at=$proveedores_detalles;
        $proveedordetalle->save();
    }

    public static function runNew($r)
    {
        $proveedores_detalles = Auth::user()->id;
        $proveedordetalle = new ProveedorDetalle;
        $proveedordetalle->proveedor_id = trim( $r->proveedor_id );
        $proveedordetalle->categoria_id = trim( $r->categoria_id );
        $proveedordetalle->articulo_id = trim( $r->articulo_id );
        $proveedordetalle->estado = trim( $r->estado );
        $proveedordetalle->persona_id_created_at=$proveedores_detalles;
        $proveedordetalle->save();
    }

    public static function runEdit($r)
    {
        $proveedores_detalles = Auth::user()->id;
        $proveedordetalle = ProveedorDetalle::find($r->id);
        $proveedordetalle->proveedor_id = trim( $r->proveedor_id );
        $proveedordetalle->categoria_id = trim( $r->categoria_id );
        $proveedordetalle->articulo_id = trim( $r->articulo_id );
        $proveedordetalle->estado = trim( $r->estado );
        $proveedordetalle->persona_id_updated_at=$proveedores_detalles;
        $proveedordetalle->save();
    }

    public static function runLoad($r)
    {
        $sql=ProveedorDetalle::select('proveedores_detalles.id','proveedores_detalles.proveedor_id','proveedores_detalles.categoria_id','proveedores_detalles.articulo_id',
                'categorias.categoria', 'proveedores_detalles.estado',
                'articulos.articulo')
             ->join('proveedores','proveedores.id','=','proveedores_detalles.proveedor_id')
             ->leftjoin('categorias','categorias.id','=','proveedores_detalles.categoria_id')
             ->leftjoin('articulos','articulos.id','=','proveedores_detalles.articulo_id')

             ->where( 
                function($query) use ($r){
                    $query->where('proveedores_detalles.proveedor_id','=',$r->id);
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('proveedores_detalles.estado','like','%'.$estado.'%');
                        }
                    }

                }
            );
        $result = $sql->orderBy('id','asc')->get();
        return $result;
    }
}
