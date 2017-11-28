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
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.norden',1);
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->Join('mat_cursos AS mc', function($join) use ($r){
                $join->on('mc.id','=','mp.curso_id');
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
                $join->on('p.id','=','mm.persona_id');
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->select(
            'mm.id',
            'mm.alumno_id',
            'mtp.tipo_participante',
            's.sucursal AS ode',
            'p.paterno',
            'p.materno',
            'p.nombre',
            'mm.fecha_matricula'
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
                }
            );
        $result = $sql->orderBy('mm.id','asc')->paginate(10);
        return $result;
    }

    public static function verDetaMatriculas($r)
    {
        $sql=DB::table('mat_matriculas_detalles as mmd')
            ->Join('mat_matriculas AS m', function($join){
                $join->on('m.id','=','mmd.matricula_id');
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->Join('mat_cursos AS mc', function($join){
                $join->on('mc.id','=','mp.curso_id');
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mp.persona_id');
            })
            ->select(
            'mmd.id',
            'm.id as id_matri',
            'mc.tipo_curso',
            'mc.curso',
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
        $user_id = Auth::user()->id;
        $especialidad = MatriculaRectifica::find($r->id);
        $especialidad->estado = 0;
        $especialidad->persona_id_updated_at=$user_id;
        $especialidad->save();
    }

    public static function UpdateMatriDeta($r)
    {
        $user_id = Auth::user()->id;
        $data = MatriculaDetalle::find($r->id);
        $data->programacion_id = $r->programacion_id;
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
    // --
}
