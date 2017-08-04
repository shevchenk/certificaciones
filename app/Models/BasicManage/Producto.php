<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Producto extends Model
{
    protected   $table = 'productos';

    public static function runEditStatus($r)
    {
        $producto = Producto::find($r->id);
        $producto->estado = trim( $r->estadof );
        $producto->persona_id_updated_at=Auth::user()->id;
        $producto->save();
    }

    public static function runNew($r)
    {            
        $producto = new Producto;
        $producto->producto = trim( $r->producto );
        $producto->articulo_id = trim( $r->articulo );
        $producto->unidad_medida = trim( $r->unidad_medida );
        $producto->estado = trim( $r->estado );
        $producto->persona_id_created_at=Auth::user()->id;
        if(trim($r->imagen_nombre)!=''){
        $producto->foto=$r->imagen_nombre;
        $este = new Producto;
        $url = "img/product/".$r->imagen_nombre; 
        $este->fileToFile($r->imagen_archivo, $url);}
        else {
        $producto->foto=null;    
        }
        $producto->save();
       
                
    }

    public static function runEdit($r)
    {
        $producto = Producto::find($r->id);
        $producto->producto = trim( $r->producto );
        $producto->articulo_id = trim( $r->articulo );
        $producto->unidad_medida = trim( $r->unidad_medida );
        $producto->estado = trim( $r->estado );
        $producto->persona_id_created_at=Auth::user()->id;
        if(trim($r->imagen_nombre)!=''){
            $producto->foto=$r->imagen_nombre;
        }else {
            $producto->foto=null;    
        }
        if(trim($r->imagen_archivo)!=''){
            $este = new Producto;
            $url = "img/product/".$r->imagen_nombre; 
            $este->fileToFile($r->imagen_archivo, $url);
        }
        $producto->save();
    }

    public static function runLoad($r)
    {
        $sql=Producto::select('productos.id','a.articulo','articulo_id','producto','unidad_medida','foto','productos.estado')
            ->join('articulos as a','a.id','=','productos.articulo_id')
            ->where( 
                function($query) use ($r){
                    if( $r->has("producto") ){
                        $producto=trim($r->producto);
                        if( $producto !='' ){
                            $query->where('producto','like','%'.$producto.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('productos.estado','like','%'.$estado.'%');
                        }
                    }
                    if( $r->has("articulo") ){
                        $articulo=trim($r->articulo);
                        if( $articulo !='' ){
                            $query->where('a.articulo','like','%'.$articulo.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('producto','asc')->get();
        return $result;
    }
    
    
        public function fileToFile($file, $url)
    {
        if ( !is_dir('img') ) {
            mkdir('img',0777);
        }
        if ( !is_dir('img/product') ) {
            mkdir('img/product',0777);
        }
        list($type, $file) = explode(';', $file);
        list(, $type) = explode('/', $type);
        if ($type=='jpeg') $type='jpg';
        if (strpos($type,'document')!==False) $type='docx';
        if (strpos($type, 'sheet') !== False) $type='xlsx';
        if (strpos($type, 'pdf') !== False) $type='pdf';
        if ($type=='plain') $type='txt';
        list(, $file)      = explode(',', $file);
        $file = base64_decode($file);
        file_put_contents($url , $file);
        return $url. $type;
    }

}
