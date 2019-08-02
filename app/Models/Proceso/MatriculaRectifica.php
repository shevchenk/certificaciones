<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB; //BD

class MatriculaRectifica extends Model
{
    protected   $table = 'mat_matriculas';

    public static function BuscarAlumno($r)
    {
        $sql=Alumno::select('id','direccion','referencia','region_id','provincia_id','distrito_id')
            ->where('estado','=','1')
            ->where('persona_id','=',$r->persona_id);
        $result = $sql->orderBy('id','asc')->first();
        return $result;
    }

    // R.A
    public static function runLoad($r)
    {
        $sql=DB::table('mat_alumnos AS ma')
            ->leftJoin('personas AS p', function($join){
                $join->on('ma.persona_id','=','p.id')
                ->where('p.estado','=',1);
            })
            ->select(
            'ma.id',
            'ma.persona_id',
            'ma.direccion',
            'ma.referencia',
            'ma.estado',
           	'p.nombre',
           	'p.paterno',
           	'p.materno',
           	'p.dni',
           	'p.email',
           	'p.telefono',
           	'p.celular'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
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
        $result = $sql->orderBy('p.nombre','asc')->paginate(10);
        return $result;
    }

    public static function verMatriculas($r)
    {
        $sql=DB::table('mat_matriculas as mm')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.matricula_id','=','mm.id');
            })
            ->Join('mat_cursos AS mc', function($join) use ($r){
                $join->on('mc.id','=','mmd.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
                if( $r->has('tipo_curso') ){
                    $join->where('mc.tipo_curso',2);
                }
                else{
                    $join->where('mc.tipo_curso',1);
                }
            })
            ->Join('mat_tipos_participantes AS mtp', function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mm.persona_marketing_id');
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->leftJoin('mat_especialidades AS me', function($join){
                $join->on('me.id','=','mmd.especialidad_id')
                ->where('me.empresa_id', Auth::user()->empresa_id);
            })
            ->select(
            'mm.id',
            'mm.alumno_id',
            'mm.persona_id',
            'mtp.tipo_participante',
            's.sucursal AS ode',
            'p.paterno',
            'p.materno',
            'p.nombre',
            DB::raw('GROUP_CONCAT(DISTINCT(mm.especialidad_programacion_id)) AS especialidad_programacion_id'),
            'mm.fecha_matricula',
            DB::raw('GROUP_CONCAT(DISTINCT(me.especialidad)) AS especialidad')
            )
            ->where( 
                function($query) use ($r){
                    $query->where('mm.estado','=', 1);

                    if( $r->has("alumno_id") ){
                        $alumno_id=trim($r->alumno_id);
                        if( $alumno_id !='' ){
                            $query->where('mm.alumno_id','=', $alumno_id);
                        }
                    }

                    if( $r->has('especialidad_programacion_id') ){
                        $especialidad_programacion_id= trim($r->especialidad_programacion_id);
                        if($especialidad_programacion_id!=''){
                            $query->where('mm.especialidad_programacion_id',$especialidad_programacion_id);
                        }
                    }
                    elseif( $r->has('especialidad_matricula') ){
                        $query->whereNotNull('mm.especialidad_programacion_id');
                    }
                    else{
                        $query->whereNull('mm.especialidad_programacion_id');
                    }
                }
            );
        $result = $sql->groupBy('mm.id','mm.alumno_id','mm.persona_id','mtp.tipo_participante',
            's.sucursal','p.paterno','p.materno','p.nombre','mm.fecha_matricula')
                    ->orderBy('mm.id','asc')->paginate(10);
        return $result;
    }

    public static function verMatriculaCuota($r)
    {
        $sql="
        SELECT r.cuota, MAX(r.fecha_cronograma) fecha_cronograma, MAX(r.nro_cuota) nro_cuota, MAX(r.monto_cuota) monto_cuota, MAX(r.tipo_pago_cuota) tipo_pago, MAX(r.archivo_cuota) archivo_cuota, $r->matricula_id AS matricula_id
        FROM (
            SELECT cuota, fecha_cronograma, '' nro_cuota, '' monto_cuota, '' tipo_pago_cuota, '' archivo_cuota
            FROM mat_especialidades_programaciones_cronogramas 
            WHERE especialidad_programacion_id= $r->especialidad_programacion_id
            AND estado=1
            UNION 
            SELECT cuota, '' fecha_programada, nro_cuota,monto_cuota,tipo_pago_cuota,archivo_cuota
            FROM mat_matriculas_cuotas
            WHERE matricula_id= $r->matricula_id
            AND estado=1
        ) r
        GROUP BY r.cuota
        ORDER BY r.cuota
        ";
        $result = DB::select($sql);
        return $result;
    }

    public static function verDetaMatriculas($r)
    {
        $sql=DB::table('mat_matriculas_detalles as mmd')
            ->Join('mat_matriculas AS m', function($join){
                $join->on('m.id','=','mmd.matricula_id');
            })
            ->Join('mat_cursos AS mc', function($join){
                $join->on('mc.id','=','mmd.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
            })
            ->leftJoin('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('sucursales AS s', function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->leftJoin('personas AS p', function($join){
                $join->on('p.id','=','mp.persona_id');
            })
            ->select(
            'mmd.id',
            'm.id as id_matri',
            'mc.tipo_curso',
            'mc.curso','mc.id AS curso_id',
             DB::raw("CONCAT(p.paterno,' ', p.materno,', ', p.nombre) as docente"),
             DB::raw("IF(s.id=1,'OnLine','Presencial') as modalidad"),
            's.sucursal',
            'mp.dia',
            'mp.fecha_inicio',
            'mp.fecha_final',
            DB::raw('IFNULL(mmd.nro_pago, 0) AS nro_pago'),
            DB::raw('IFNULL(mmd.monto_pago, 0) AS monto_pago'),
            DB::raw('IFNULL(mmd.nro_pago_certificado, 0) AS nro_pago_certificado'),
            DB::raw('IFNULL(mmd.monto_pago_certificado, 0) AS monto_pago_certificado'),
            'mmd.archivo_pago',
            'mmd.archivo_pago_certificado'
            )
            ->where( 
                function($query) use ($r){
                    $query->where('mmd.estado','=', 1);

                    if( $r->has("matricula_id") ){
                        $matricula_id=trim($r->matricula_id);
                        if( $matricula_id !='' ){
                            $query->where('mmd.matricula_id','=', $matricula_id);
                        }
                    }
                }
            );
        $result = $sql->orderBy('mmd.id','asc')->paginate(10);
        return $result;
    }

    public static function runEditStatus($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $especialidad = MatriculaRectifica::find($r->id);
        $especialidad->estado = 0;
        $especialidad->persona_id_updated_at=$user_id;
        $especialidad->save();

        DB::table('mat_matriculas_detalles')
        ->where('matricula_id', '=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        DB::commit();
    }

    public static function runEditEspecialidadStatus($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $especialidad = MatriculaRectifica::find($r->id);
        $especialidad->estado = 0;
        $especialidad->persona_id_updated_at=$user_id;
        $especialidad->save();

        DB::table('mat_matriculas_detalles')
        ->where('matricula_id', '=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        DB::table('mat_matriculas_cuotas')
        ->where('matricula_id', '=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        DB::commit();
    }

    public static function UpdateMatriDeta($r)
    {
        $user_id = Auth::user()->id;
        $data = MatriculaDetalle::find($r->id);
        $data->programacion_id = $r->programacion_id;
        if( $r->has('curso_id') AND trim($r->curso_id)!='' ){
            $data->curso_id = $r->curso_id;
        }
        $data->persona_id_updated_at=$user_id;
        $data->save();
        
        /*
        DB::table('mat_matriculas_detalles')
            ->where('id', 1)
            ->update(['programacion_id' => $r->programacion_id],
                      ['persona_id_updated_at' => $user_id],  );
        */
    }
    
    public static function UpdatePagosDM($r)
    {
        $user_id = Auth::user()->id;
        $data = MatriculaDetalle::find($r->id);
        
        //if($r->nro_pago)
        $data->nro_pago = $r->nro_pago;
        $data->monto_pago = $r->monto_pago;
        
        //if($r->nro_pago_certificado)
        $data->nro_pago_certificado = $r->nro_pago_certificado;
        $data->monto_pago_certificado = $r->monto_pago_certificado;

        $data->persona_id_updated_at=$user_id;
        $data->save();
    }

    public static function CambiarEspecialidad($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        DB::table('mat_matriculas_detalles')
        ->where('matricula_id', '=', $r->matricula_id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        DB::table('mat_matriculas_cuotas')
        ->where('matricula_id', '=', $r->matricula_id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        $mat= Matricula::find($r->matricula_id);
        $mat->estado= 0;
        $mat->persona_id_updated_at= $user_id;
        $mat->save();

        $newMat= new Matricula;
        $newMat->tipo_participante_id= $mat->tipo_participante_id;
        $newMat->persona_id= $mat->persona_id;
        $newMat->alumno_id= $mat->alumno_id;
        $newMat->sucursal_id= $mat->sucursal_id;
        $newMat->sucursal_destino_id= $mat->sucursal_destino_id;
        $newMat->persona_caja_id= $mat->persona_caja_id;
        $newMat->persona_matricula_id= $mat->persona_matricula_id;
        $newMat->persona_marketing_id= $mat->persona_marketing_id;
        $newMat->fecha_matricula= $mat->fecha_matricula;
        $newMat->tipo_matricula= $mat->tipo_matricula;
        $newMat->nro_pago= $mat->nro_pago;
        $newMat->monto_pago= $mat->monto_pago;
        $newMat->archivo_pago= $mat->archivo_pago;
        $newMat->nro_pago_inscripcion= $mat->nro_pago_inscripcion;
        $newMat->monto_pago_inscripcion= $mat->monto_pago_inscripcion;
        $newMat->archivo_pago_inscripcion= $mat->archivo_pago_inscripcion;
        $newMat->nro_promocion= $mat->nro_promocion;
        $newMat->monto_promocion= $mat->monto_promocion;
        $newMat->archivo_promocion= $mat->archivo_promocion;
        $newMat->archivo_dni= $mat->archivo_dni;
        $newMat->tipo_pago= $mat->tipo_pago;
        $newMat->observacion= $mat->observacion.' - Especialidad Cambiada('.$mat->id.')';
        $newMat->especialidad_programacion_id= $r->especialidad_programacion_id;
        $newMat->estado=1;
        $newMat->persona_id_created_at=$user_id;
        $newMat->save();

        $detMat=DB::table('mat_cursos_especialidades')
        ->where('especialidad_id', '=', $r->especialidad_id)
        ->where('estado',1)
        ->orderBy('orden','asc')
        ->get();

        foreach ($detMat as $key => $value) {
            $newDetMat= new MatriculaDetalle;
            $newDetMat->matricula_id= $newMat->id;
            $newDetMat->norden= $value->orden;
            $newDetMat->curso_id= $value->curso_id;
            $newDetMat->especialidad_id= $value->especialidad_id;
            $newDetMat->nro_pago_certificado= 0;
            $newDetMat->monto_pago_certificado= 0;
            $newDetMat->tipo_matricula_detalle= 2;
            $newDetMat->tipo_pago= 0;
            $newDetMat->estado=1;
            $newDetMat->persona_id_created_at=$user_id;
            $newDetMat->save();
        }

        $detCuota=DB::table('mat_matriculas_cuotas')
        ->where('matricula_id', '=', $r->matricula_id)
        ->orderBy('cuota','asc')
        ->get();

        foreach ($detCuota as $key => $value) {
            $newCuota= new MatriculaCuota;
            $newCuota->matricula_id= $newMat->id;
            $newCuota->cuota= $value->cuota;
            $newCuota->nro_cuota= $value->nro_cuota;
            $newCuota->monto_cuota= $value->monto_cuota;
            $newCuota->tipo_pago_cuota= $value->tipo_pago_cuota;
            $newCuota->archivo_cuota= $value->archivo_cuota;
            $newCuota->estado=1;
            $newCuota->persona_id_created_at=$user_id;
            $newCuota->save();
        }

        DB::commit();
        $return['rst'] = 1;
        $return['msj'] = 'Registro actualizado';
        $return['alumno_id'] = $newMat->alumno_id;
        $return['matricula_id'] = $newMat->id;
        return $return;
    }
    // --
}
