<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use DB;
class Empleado extends Model
{
    protected   $table = 'empleados';

    public static function runEditStatus($r)
    {
        $empleado = Empleado::find($r->id);
        $empleado->estado = trim( $r->estadof );
        $empleado->persona_id_updated_at=1;
        $empleado->save();
    }

    public static function runNew($r)
    {
        $empleado = new Empleado;
        $empleado->persona_id = trim( $r->persona_id );
        $empleado->cargo_id = trim( $r->cargo );
        $empleado->sucursal_id = trim( $r->sucursal );
        if(trim( $r->fecha_inicio )!=''){
        $empleado->fecha_inicio = trim( $r->fecha_inicio );}
        else {
        $empleado->fecha_inicio = null;}    
        if(trim( $r->fecha_final)!=''){
        $empleado->fecha_final = trim( $r->fecha_final );}
        else {
        $empleado->fecha_final = null;}  
        $empleado->estado = trim( $r->estado );
        $empleado->persona_id_created_at=1;
        $empleado->save();
    }

    public static function runEdit($r)
    {
        $empleado = Empleado::find($r->id);
        $empleado->persona_id = trim( $r->persona_id );
        $empleado->cargo_id = trim( $r->cargo );
        $empleado->sucursal_id = trim( $r->sucursal );
        if(trim( $r->fecha_inicio )!=''){
        $empleado->fecha_inicio = trim( $r->fecha_inicio );}
        else {
        $empleado->fecha_inicio = null;}    
        if(trim( $r->fecha_final)!=''){
        $empleado->fecha_final = trim( $r->fecha_final );}
        else {
        $empleado->fecha_final = null;}  
        $empleado->estado = trim( $r->estado );
        $empleado->persona_id_updated_at=1;
        $empleado->save();
    }

    public static function runLoad($r)
    {
        $sql=Empleado::select('empleados.id','persona_id','personas.paterno','personas.materno','personas.nombre','cargo_id','sucursal_id',
                             'cargos.cargo','sucursales.sucursal','empleados.estado',DB::raw('IFNULL(fecha_inicio,"") as fecha_inicio'),DB::raw('IFNULL(fecha_final,"") as fecha_final'))
            ->join('personas','empleados.persona_id','=','personas.id')
            ->join('cargos','empleados.cargo_id','=','cargos.id')
            ->join('sucursales','empleados.sucursal_id','=','sucursales.id')
            ->where( 
                function($query) use ($r){
                    if( $r->has("persona") ){
                        $persona=trim($r->persona);
                        if( $persona !='' ){
                            $query->whereRaw('CONCAT_WS(" ",personas.paterno,personas.materno,personas.nombre ) like "%'.$persona.'%"');
                        }
                    }
                    if( $r->has("sucursal") ){
                        $sucursal=trim($r->sucursal);
                        if( $sucursal !='' ){
                            $query->where('sucursales.sucursal','like','%'.$sucursal.'%');
                        }
                    }
                    if( $r->has("cargo") ){
                        $cargo=trim($r->cargo);
                        if( $cargo !='' ){
                            $query->where('cargos.cargo','like','%'.$cargo.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('empleados.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('empleados.id','asc')->get();
        return $result;
    }
    
        public static function ListEmpleado($r)
    {
        $sql=Empleado::select('id','empleado','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('empleado','asc')->get();
        return $result;
    }
    

}
