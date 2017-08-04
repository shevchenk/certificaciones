<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cliente extends Model
{
    protected   $table = 'clientes';

    public static function runEditStatus($r)
    {
        $proveedor = Cliente::find($r->id);
        $proveedor->estado = trim( $r->estadof );
        $proveedor->persona_id_updated_at=1;
        $proveedor->save();
    }

    public static function runNew($r)
    {
        $proveedor = new Cliente;
        $proveedor->persona_id = trim( $r->persona_id );
        if(trim( $r->empresa_id )!=''){
        $proveedor->empresa_id = trim( $r->empresa_id );}
        $proveedor->estado = trim( $r->estado );
        $proveedor->persona_id_created_at=1;
        $proveedor->save();
    }

    public static function runEdit($r)
    {
        $proveedor = Cliente::find($r->id);
        $proveedor->persona_id = trim( $r->persona_id );
        if(trim( $r->empresa_id )!=''){
        $proveedor->empresa_id = trim( $r->empresa_id );}
        else {
        $proveedor->empresa_id = null;}    
        $proveedor->estado = trim( $r->estado );
        $proveedor->persona_id_updated_at=1;
        $proveedor->save();
    }

    public static function runLoad($r)
    {
        $sql=Cliente::select(DB::raw('IFNULL(empresas.razon_social,"") as razon_social'),'clientes.id','clientes.persona_id','personas.paterno','personas.materno','personas.nombre',
                               DB::raw('IFNULL(clientes.empresa_id,"") as empresa_id'),'clientes.estado')
            ->join('personas','clientes.persona_id','=','personas.id')
            ->leftjoin('empresas','clientes.empresa_id','=','empresas.id')
            ->where( 
                function($query) use ($r){
                
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('clientes.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('clientes.id','asc')->get();
        return $result;
    }
  

}
