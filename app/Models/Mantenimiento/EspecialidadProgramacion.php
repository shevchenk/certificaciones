<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;

class EspecialidadProgramacion extends Model
{
    protected   $table = 'mat_especialidades_programaciones';

    public static function runLoad($r)
    {

        $sql=DB::table('mat_especialidades_programaciones AS mep')
            ->Join('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mep.especialidad_id')
                ->where('me.empresa_id','=', Auth::user()->empresa_id)
                ->where('me.estado','=',1);
            })
            ->leftJoin('mat_especialidades_programaciones_sucursales AS meps',function($join){
                $join->on('meps.especialidad_programacion_id','=','mep.id')
                ->where('meps.estado','=',1);
            })
            ->leftJoin('sucursales AS s',function($join){
                $join->on('s.id','=','meps.sucursal_id')
                ->where('s.estado','=',1);
            })
            ->leftJoin('mat_especialidades_programaciones_cronogramas AS mepc',function($join){
                $join->on('mepc.especialidad_programacion_id','=','mep.id')
                ->where('mepc.estado','=',1);
            })
            ->select(
            'mep.id',
            'me.especialidad',
            'mep.especialidad_id',
            'mep.tipo',
            'mep.nro_cuota',
            'mep.fecha_inicio',
            'mep.estado',
            'mep.costo',
            'mep.costo_mat',
            DB::raw('GROUP_CONCAT( DISTINCT(CONCAT(mepc.fecha_cronograma," => S/ ",mepc.monto_cronograma)) ORDER BY mepc.cuota) AS fecha_cronograma'),
            DB::raw('GROUP_CONCAT( DISTINCT(s.sucursal) ORDER BY s.sucursal SEPARATOR "|") AS sucursal'),
            DB::raw('GROUP_CONCAT( DISTINCT(s.id) ) AS sucursal_id')
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('me.especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("fecha_inicio") ){
                        $fecha_inicio=trim($r->fecha_inicio);
                        if( $fecha_inicio !='' ){
                            $query->where('mep.fecha_inicio','like',$fecha_inicio.'%');
                        }
                    }
                    if( $r->has("nro_cuota") ){
                        $nro_cuota=trim($r->nro_cuota);
                        if( $nro_cuota !='' ){
                            $query->where('mep.nro_cuota','like','%'.$nro_cuota.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('me.estado','=',$estado);
                        }
                    }
                    if( $r->has("tipo") ){
                        $tipo=trim($r->tipo);
                        if( $tipo !='' ){
                            $query->where('mep.tipo','=',$tipo);
                        }
                    }
                }
            );
        $result = $sql->groupBy('mep.id','me.especialidad','mep.especialidad_id','mep.tipo','mep.nro_cuota'
                        ,'mep.fecha_inicio','mep.estado','mep.costo','mep.costo_mat')
                        ->orderBy('mep.fecha_inicio','desc')
                        ->orderBy('me.especialidad','asc')
                        ->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $especialidadProgramacion = EspecialidadProgramacion::find($r->id);
        $especialidadProgramacion->estado = trim( $r->estadof );
        $especialidadProgramacion->persona_id_updated_at=$usuario;
        $especialidadProgramacion->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $usuario = Auth::user()->id;

        $validacion=EspecialidadProgramacion::where('especialidad_id',trim( $r->especialidad_id ))
                    ->where('fecha_inicio',trim( $r->fecha_inicio ))
                    ->first();
        /*$codigo_inicio=65;
        if( isset($validacion->codigo_inicio) ){
            $codigo_inicio=$validacion->codigo_inicio+1;
        }*/
        $especialidadProgramacion = new EspecialidadProgramacion;
        $especialidadProgramacion->especialidad_id = trim( $r->especialidad_id );
        $especialidadProgramacion->tipo = trim( $r->tipo );
        $especialidadProgramacion->fecha_inicio = trim( $r->fecha_inicio );
        $especialidadProgramacion->estado = trim( $r->estado );
        $especialidadProgramacion->costo = trim( $r->costo );
        $especialidadProgramacion->costo_mat = trim( $r->costo_mat );
        $especialidadProgramacion->persona_id_created_at=$usuario;
        if( $r->has('nro_cuota') && $r->nro_cuota!='' ){
            $especialidadProgramacion->nro_cuota= $r->nro_cuota."-".$r->monto_cuota;
        }
        $especialidadProgramacion->save();
        
        $fecha_cronograma = $r->fecha_cronograma;
        $monto_cronograma = $r->monto_cronograma;
        if( $r->has('fecha_cronograma') ){
            for ($i=0; $i < count($fecha_cronograma); $i++) { 
                $EPC = new EspecialidadProgramacionCronograma;
                $EPC->especialidad_programacion_id = $especialidadProgramacion->id;
                $EPC->cuota = ($i+1);
                $EPC->fecha_cronograma = $fecha_cronograma[$i];
                $EPC->monto_cronograma = $monto_cronograma[$i];
                $EPC->persona_id_created_at = $usuario;
                $EPC->save();
            }
        }

        $sucursal_id = $r->sucursal_id;
        if( $r->has('sucursal_id') ){
            for ($i=0; $i < count($sucursal_id); $i++) { 
                $EPS = new EspecialidadProgramacionSucursal;
                $EPS->especialidad_programacion_id = $especialidadProgramacion->id;
                $EPS->sucursal_id = $sucursal_id[$i];
                $EPS->persona_id_created_at = $usuario;
                $EPS->save();
            }
        }
        DB::commit();
    }

    public static function runEdit($r)
    {
        DB::beginTransaction();
        $usuario = Auth::user()->id;
        $especialidadProgramacion = EspecialidadProgramacion::find($r->id);
        $especialidadProgramacion->fecha_inicio = trim( $r->fecha_inicio );
        $especialidadProgramacion->tipo = trim( $r->tipo );
        $especialidadProgramacion->estado = trim( $r->estado );
        $especialidadProgramacion->costo = trim( $r->costo );
        $especialidadProgramacion->costo_mat = trim( $r->costo_mat );
        $especialidadProgramacion->persona_id_updated_at=$usuario;
        if( $r->has('nro_cuota') && $r->nro_cuota!='' ){
            $especialidadProgramacion->nro_cuota= $r->nro_cuota."-".$r->monto_cuota;
        }
        $especialidadProgramacion->save();

        $fecha_cronograma = $r->fecha_cronograma;
        $monto_cronograma = $r->monto_cronograma;
        DB::table('mat_especialidades_programaciones_cronogramas')
        ->where('especialidad_programacion_id','=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
                )
            );
        if( $r->has('fecha_cronograma') ){
            for ($i=0; $i < count($fecha_cronograma); $i++) { 
                $validacronograma=DB::table('mat_especialidades_programaciones_cronogramas')
                ->select('fecha_cronograma','id')
                ->where('especialidad_programacion_id', '=', $r->id)
                ->where('cuota',($i+1))
                ->where('estado',1)
                ->first();

                $crea=false;
                if( isset($validacronograma->fecha_cronograma) AND $validacronograma->fecha_cronograma!=$fecha_cronograma[$i] ){
                    $crea=true;
                }
                elseif( !isset($validacronograma->fecha_cronograma) ){
                    $crea=true;
                }

                if($crea){
                    $EPC = new EspecialidadProgramacionCronograma;
                    $EPC->especialidad_programacion_id = $especialidadProgramacion->id;
                    $EPC->cuota = ($i+1);
                    $EPC->fecha_cronograma = $fecha_cronograma[$i];
                    $EPC->monto_cronograma = $monto_cronograma[$i];
                    $EPC->persona_id_created_at = $usuario;
                    $EPC->save();
                }
                else{
                    $EPC = EspecialidadProgramacionCronograma::find($validacronograma->id);
                    $EPC->estado=1;
                    $EPC->monto_cronograma = $monto_cronograma[$i];
                    $EPC->persona_id_updated_at = $usuario;
                    $EPC->save();
                }
            }
        }

        $sucursal_id = $r->sucursal_id;
        DB::table('mat_especialidades_programaciones_sucursales')
        ->where('especialidad_programacion_id','=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
                )
            );

        if( $r->has('sucursal_id') ){
            for ($i=0; $i < count($sucursal_id); $i++) { 

                $validacronograma=DB::table('mat_especialidades_programaciones_sucursales')
                ->select('sucursal_id','id')
                ->where('especialidad_programacion_id', '=', $r->id)
                ->where('sucursal_id',$sucursal_id[$i])
                ->first();

                if( isset($validacronograma->id) AND $validacronograma->id!='' ){
                    $EPS = EspecialidadProgramacionSucursal::find($validacronograma->id);
                    $EPS->persona_id_updated_at = $usuario;
                }
                else{
                    $EPS = new EspecialidadProgramacionSucursal;
                    $EPS->especialidad_programacion_id = $especialidadProgramacion->id;
                    $EPS->sucursal_id = $sucursal_id[$i];
                    $EPS->persona_id_created_at = $usuario;
                }
                $EPS->estado=1;
                $EPS->save();
            }
        }
        DB::commit();
    }

    public static function CargarCronograma($r)
    {
        $sql=DB::table('mat_especialidades_programaciones_cronogramas AS mepc')
            ->select(
            'mepc.id',
            'mepc.fecha_cronograma',
            'mepc.monto_cronograma'
            )
            ->where('mepc.estado','=',1)
            ->where( 
                function($query) use ($r){
                    if( $r->has("id") ){
                        $especialidad_programacion_id=trim($r->id);
                        if( $especialidad_programacion_id !='' ){
                            $query->where('mepc.especialidad_programacion_id','=',$especialidad_programacion_id);
                        }
                    }
                }
            );
        $result = $sql->orderBy('mepc.cuota','asc')->get();
        return $result;
    }
}
