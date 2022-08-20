<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class CentroOperacion extends Model
{
    protected   $table = 'mat_centro_operaciones';

    public static function runLoad($r)
    {

        $sql=   DB::table('mat_centro_operaciones as co')
                ->leftJoin('mat_ubicacion_region as r', 'r.id', '=', 'co.region_id')
                ->leftJoin('mat_ubicacion_provincia as p', 'p.id', '=', 'co.provincia_id')
                ->leftJoin('mat_ubicacion_distrito as d', 'd.id', '=', 'co.distrito_id')
                ->select('co.id', 'co.centro_operacion', 'co.direccion',
                'co.region_id', 'co.provincia_id', 'co.distrito_id', 'r.region', 'p.provincia', 'd.distrito', 
                'co.estado')
                ->where('empresa_id', Auth::user()->empresa_id)
                ->where( 
                function($query) use ($r){
                    if( $r->has("centro_operacion") ){
                        $centro_operacion=trim($r->centro_operacion);
                        if( $centro_operacion !='' ){
                            $query->where('co.centro_operacion','like','%'.$centro_operacion.'%');
                        }
                    }
                    if( $r->has("direccion") ){
                        $direccion=trim($r->direccion);
                        if( $direccion !='' ){
                            $query->where('co.direccion','like','%'.$direccion.'%');
                        }
                    }
                    if( $r->has("region") ){
                        $region=trim($r->region);
                        if( $region !='' ){
                            $query->where('r.region','like','%'.$region.'%');
                        }
                    }
                    if( $r->has("provincia") ){
                        $provincia=trim($r->provincia);
                        if( $provincia !='' ){
                            $query->where('p.provincia','like','%'.$provincia.'%');
                        }
                    }
                    if( $r->has("distrito") ){
                        $distrito=trim($r->distrito);
                        if( $distrito !='' ){
                            $query->where('d.distrito','like','%'.$distrito.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('co.estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('co.centro_operacion','asc')->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $centroOperacion = CentroOperacion::find($r->id);
        $centroOperacion->estado = trim( $r->estadof );
        $centroOperacion->persona_id_updated_at=$usuario;
        $centroOperacion->save();
    }

    public static function runNew($r)
    {
        $usuario = Auth::user()->id;
        $empresa_id = Auth::user()->empresa_id;
        $centroOperacion = new CentroOperacion;
        $centroOperacion->empresa_id = $empresa_id;
        $centroOperacion->centro_operacion = trim( $r->centro_operacion );
        $centroOperacion->direccion = trim( $r->direccion );
        
        if( $r->has('region_id') ){
            $centroOperacion->region_id = trim( $r->region_id );
            $centroOperacion->provincia_id = trim( $r->provincia_id );
            $centroOperacion->distrito_id = trim( $r->distrito_id );
        }
        
        $centroOperacion->estado = trim( $r->estado );
        $centroOperacion->persona_id_created_at=$usuario;
        $centroOperacion->save();
    }

    public static function runEdit($r)
    {
        $usuario = Auth::user()->id;
        $centroOperacion = CentroOperacion::find($r->id);
        $centroOperacion->centro_operacion = trim( $r->centro_operacion );
        $centroOperacion->direccion = trim( $r->direccion );
        
        if( $r->has('region_id') ){
            $centroOperacion->region_id = trim( $r->region_id );
            $centroOperacion->provincia_id = trim( $r->provincia_id );
            $centroOperacion->distrito_id = trim( $r->distrito_id );
        }

        $centroOperacion->estado = trim( $r->estado );
        $centroOperacion->persona_id_updated_at=$usuario;
        $centroOperacion->save();
    }

    
    public static function ListCentroOperacion($r)
    {
        $sql=CentroOperacion::select('id','centro_operacion')
            ->where('estado','=','1')
            ->where('empresa_id',Auth::user()->empresa_id)
            ->where( 
                function($query) use ($r){
                    if( $r->has("centro_operacion") ){
                        $centro_operacion=trim($r->centro_operacion);
                        if( $centro_operacion !='' ){
                            $query->where('centro_operacion','=',$centro_operacion);
                        }
                    }
                }
            );
        $result = $sql->orderBy('centro_operacion','asc')->get();
        return $result;
    }
    

}
