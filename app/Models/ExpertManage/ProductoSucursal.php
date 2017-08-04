<?php

namespace App\Models\ExpertManage;

use Illuminate\Database\Eloquent\Model;

class ProductoSucursal extends Model
{
    protected   $table = 'productos_sucursales';

    public static function runEditStatus($r)
    {
        $producto = ProductoSucursal::find($r->id);
        $producto->estado = trim( $r->estadof );
        $producto->persona_id_updated_at=1;
        $producto->save();
    }

    public static function runNew($r)
    {            
        $producto = new ProductoSucursal;
        $producto->producto_id = trim( $r->producto_id );
        $producto->sucursal_id = trim( $r->sucursal );
        $producto->precio_venta = trim( $r->precio_venta );
        $producto->precio_compra = trim( $r->precio_compra );
        $producto->moneda = trim( $r->moneda );
        $producto->stock = trim( $r->stock );
        $producto->stock_minimo = trim( $r->stock_minimo );
        $producto->dias_alerta = trim( $r->dias_alerta );
        if(trim($r->fecha_vencimiento)!=''){
            $producto->dias_vencimiento = 0; 
            $producto->fecha_vencimiento = trim( $r->fecha_vencimiento );
        }else {
            $producto->dias_vencimiento = trim( $r->dias_vencimiento );
            $producto->fecha_vencimiento  = date('Y-m-d', strtotime('+'.$r->dias_vencimiento.' day', strtotime(date('Y-m-d'))));     
        }
        $producto->estado = trim( $r->estado );
        $producto->persona_id_created_at=1;
        $producto->save();
       
                
    }

    public static function runEdit($r)
    {
        $producto = ProductoSucursal::find($r->id);
        $producto->producto_id = trim( $r->producto_id );
        $producto->sucursal_id = trim( $r->sucursal );
        $producto->precio_venta = trim( $r->precio_venta );
        $producto->precio_compra = trim( $r->precio_compra );
        $producto->moneda = trim( $r->moneda );
        $producto->stock = trim( $r->stock );
        $producto->stock_minimo = trim( $r->stock_minimo );
        $producto->dias_alerta = trim( $r->dias_alerta );
        if(trim($r->fecha_vencimiento)!=''){
            $producto->dias_vencimiento = 0; 
            $producto->fecha_vencimiento = trim( $r->fecha_vencimiento );
        }else {
            $producto->dias_vencimiento = trim( $r->dias_vencimiento );
            $producto->fecha_vencimiento  = date('Y-m-d', strtotime('+'.$r->dias_vencimiento.' day', strtotime(date('Y-m-d'))));     
        }
        $producto->estado = trim( $r->estado );
        $producto->persona_id_updated_at=1;
        $producto->save();
    }

    public static function runLoad($r)
    {
        $sql=ProductoSucursal::select('productos_sucursales.id','s.sucursal','p.producto','p.foto','sucursal_id','producto_id','precio_venta','precio_compra',
                'moneda','stock','stock_minimo','dias_alerta','fecha_vencimiento','dias_vencimiento','productos_sucursales.estado')
            ->join('productos as p','p.id','=','productos_sucursales.producto_id')
            ->join('sucursales as s','s.id','=','productos_sucursales.sucursal_id')
            ->where( 
                function($query) use ($r){
                    if( $r->has("producto") ){
                        $producto=trim($r->producto);
                        if( $producto !='' ){
                            $query->where('producto','like','%'.$producto.'%');
                        }
                    }
                    if( $r->has("sucursal") ){
                        $sucursal=trim($r->sucursal);
                        if( $sucursal !='' ){
                            $query->where('s.sucursal','like','%'.$sucursal.'%');
                        }
                    }
                    if( $r->has("precio_venta") ){
                        $precio_venta=trim($r->precio_venta);
                        if( $precio_venta !='' ){
                            $query->where('precio_venta','like','%'.$precio_venta.'%');
                        }
                    }
                    if( $r->has("precio_compra") ){
                        $precio_compra=trim($r->precio_compra);
                        if( $precio_compra !='' ){
                            $query->where('precio_compra','like','%'.$precio_compra.'%');
                        }
                    }
                    if( $r->has("stock") ){
                        $stock=trim($r->stock);
                        if( $stock !='' ){
                            $query->where('stock','like','%'.$stock.'%');
                        }
                    }
                    if( $r->has("stock_minimo") ){
                        $stock_minimo=trim($r->stock_minimo);
                        if( $stock_minimo !='' ){
                            $query->where('stock_minimo','like','%'.$stock_minimo.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('productos_sucursales.estado','like','%'.$estado.'%');
                        }
                    }

                }
            );
        $result = $sql->orderBy('producto_id','asc')->paginate(10);
        return $result;
    }

}
