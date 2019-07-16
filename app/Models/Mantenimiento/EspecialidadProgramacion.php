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
                ->where('me.estado','=',1);
            })
            ->select(
            'mep.id',
            'me.especialidad',
            'mep.fecha_inicio',
            'mep.codigo_inicio',
            'mep.horario',
            'mep.cronograma',
            'mep.estado',
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
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('me.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('mep.fecha_inicio','asc')
                        ->orderBy('me.especialidad','asc')
                        ->orderBy('mep.codigo_inicio','asc')
                        ->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $especialidad_id = Auth::user()->id;
        $especialidadProgramacion = EspecialidadProgramacion::find($r->id);
        $especialidadProgramacion->estado = trim( $r->estadof );
        $especialidadProgramacion->persona_id_updated_at=$especialidad_id;
        $especialidadProgramacion->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $especialidad_id = Auth::user()->id;

        $validacion=EspecialidadProgramacion::where('especialidad_id',trim( $r->especialidad_id ))
                    ->where('fecha_inicio',trim( $r->fecha_inicio ))
                    ->orderBy('codigo_inicio','desc')
                    ->first();
        $codigo_inicio=65;
        if( isset($validacion->codigo_inicio) ){
            $codigo_inicio=$validacion->codigo_inicio+1;
        }
        $especialidadProgramacion = new EspecialidadProgramacion;
        $especialidadProgramacion->especialidad_id = trim( $r->especialidad_id );
        $especialidadProgramacion->fecha_inicio = trim( $r->fecha_inicio );
        $especialidadProgramacion->codigo_inicio = $codigo_inicio;
        $especialidadProgramacion->horario = trim( $r->horario );
        $especialidadProgramacion->cronograma = trim( $r->cronograma );
        $especialidadProgramacion->estado = trim( $r->estado );
        $especialidadProgramacion->persona_id_created_at=$especialidad_id;
        $especialidadProgramacion->save();
        DB::commit();
    }

    public static function runEdit($r)
    {
        DB::beginTransaction();
        $especialidad_id = Auth::user()->id;
        $especialidad = EspecialidadProgramacion::find($r->id);
        $especialidadProgramacion->horario = trim( $r->horario );
        $especialidadProgramacion->cronograma = trim( $r->cronograma );
        $especialidadProgramacion->estado = trim( $r->estado );
        $especialidadProgramacion->persona_id_updated_at=$especialidad_id;
        DB::commit();
    }
}
