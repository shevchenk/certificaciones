<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Models\Mantenimiento\Persona;
use App\Models\Proceso\MatriculaDetalle;

use Illuminate\Support\Facades\DB; //BD

class Alumno extends Model
{
    protected   $table = 'mat_alumnos';

    public static function ValidarVideo($r)
    {
        $matriculaDetalle= MatriculaDetalle::find($r->id);
        $matriculaDetalle->validavideo=$matriculaDetalle->validavideo+1;
        $matriculaDetalle->persona_id_updated_at=Auth::user()->id;
        $matriculaDetalle->save();
        $result = 'ok';
        return $result;
    }

    public static function ValidarDescarga($r)
    {
        $matriculaDetalle= MatriculaDetalle::find($r->id);
        $matriculaDetalle->validadescarga=$matriculaDetalle->validadescarga+1;
        $matriculaDetalle->persona_id_updated_at=Auth::user()->id;
        $matriculaDetalle->save();
        $result = 'ok';
        return $result;
    }

    public static function ValidarComentario($r)
    {
        $matriculaDetalle= MatriculaDetalle::find($r->id);
        $matriculaDetalle->comentario=trim($r->comentario);
        $matriculaDetalle->persona_id_updated_at=Auth::user()->id;
        $matriculaDetalle->save();
        $result = 'ok';
        return $result;
    }

    public static function RegistrarEntrega($r)
    {
        $matriculaDetalle= MatriculaDetalle::find($r->id);
        $matriculaDetalle->fecha_entrega=trim($r->fecha_entrega);
        $matriculaDetalle->comentario_entrega=trim($r->comentario_entrega);
        $matriculaDetalle->persona_id_updated_at=Auth::user()->id;
        $matriculaDetalle->save();
        $result = 'ok';
        return $result;
    }

    public static function BuscarPersona($r)
    {
        $id=Auth::user()->id;
        $persona= Persona::find($id);
        $result = $persona;
        return $result;
    }

    public static function BuscarAlumno($r)
    {
        $sql=Alumno::select('id','codigo_interno','direccion','referencia','region_id','provincia_id','distrito_id')
            ->where('estado','=','1')
            ->where('persona_id','=',$r->persona_id);
        $result = $sql->orderBy('id','asc')->first();
        return $result;
    }

    public static function MisSeminarios($r)
    {
        $sql=DB::table('mat_matriculas as mm')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado','=',1);
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id')
                ->where('mp.estado','=',1);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mp.persona_id');
            })
            ->Join('mat_cursos AS mc', function($join) use ($r){
                $join->on('mc.id','=','mp.curso_id');
                if( $r->has('tipo_curso') AND $r->tipo_curso!='' ){
                    $join->where('mc.tipo_curso',$r->tipo_curso);
                }
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->select(
            'mmd.id','mmd.validavideo',
            'mc.curso','mp.link','mm.fecha_matricula','mmd.comentario',
            DB::raw('IF(mp.sucursal_id=1,"Virtual","Presencial") as modalidad'),
            DB::raw('CONCAT(p.nombre," ", p.paterno," ", p.materno) as profesor'),
            DB::raw('DATE(mp.fecha_inicio) as fecha'),
            DB::raw('CONCAT(TIME(mp.fecha_inicio)," a ",TIME(mp.fecha_final)) as horario'),
            DB::raw('s.sucursal as sucursal')
            )
            ->where('mm.estado','1')
            ->where( 
                function($query) use ($r){
                        $persona_id=Auth::user()->id;
                        if( $persona_id !='' ){
                            $query->where('mm.persona_id','=', $persona_id);
                        }
                }
            );
        $result = $sql->orderBy('mp.fecha_inicio','desc')->get();
        return $result;
    }

    public static function MiSeminario($r)
    {
        $sql=DB::table('mat_matriculas as mm')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado','=',1);
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id')
                ->where('mp.estado','=',1);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->Join('mat_cursos AS mc', function($join){
                $join->on('mc.id','=','mp.curso_id');
            })
            ->select(
            'mmd.id',
            'mc.curso',
            DB::raw('CONCAT(p.nombre," ", p.paterno," ", p.materno) as persona'),
            DB::raw('DATE(mp.fecha_inicio) as fecha'),
            DB::raw('CONCAT(TIME(mp.fecha_inicio)," a ",TIME(mp.fecha_final)) as horario')
            )
            ->where('mm.estado','1')
            ->where( 
                function($query) use ($r){
                        $matricula_detalle_id=$r->id;
                        if( $matricula_detalle_id !='' ){
                            $query->where('mmd.id','=', $matricula_detalle_id);
                        }
                }
            );
        $result = $sql->first();
        return $result;
    }

    public static function ListarSeminarios($r)
    {
        ini_set('max_execution_time', 300);
        $sql=DB::table('mat_matriculas as mm')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado','=',1);
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id')
                ->where('mp.estado','=',1);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mp.persona_id');
            })
            ->Join('mat_cursos AS mc', function($join){
                $join->on('mc.id','=','mp.curso_id');
            })
            ->Join('sucursales AS s', function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->select(
            'mmd.id','mc.curso','mp.link','mm.fecha_matricula','mmd.comentario',
            'mmd.fecha_entrega','mmd.comentario_entrega','mmd.validadescarga',
            DB::raw('IF(mp.sucursal_id=1,"Virtual","Presencial") as modalidad'),
            DB::raw('CONCAT(p.nombre," ", p.paterno," ", p.materno) as profesor'),
            DB::raw('DATE(mp.fecha_inicio) as fecha'),
            DB::raw('CONCAT(TIME(mp.fecha_inicio)," a ",TIME(mp.fecha_final)) as horario'),
            DB::raw('s.sucursal as sucursal')
            )
            ->where('mm.estado','1')
            ->where( 
                function($query) use ($r){
                        $persona_id=$r->persona_id;
                        if( $persona_id !='' ){
                            $query->where('mm.persona_id','=', $persona_id);
                        }
                }
            );
        $result = $sql->orderBy('mp.fecha_inicio','desc')->get();
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
           	'p.celular',
            'p.id AS persona_id'
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
            //->groupBy('me.id','me.especialidad','me.certificado_especialidad','me.estado');
        $result = $sql->orderBy('p.nombre','asc')->paginate(10);
        return $result;
    }

    public static function verCursos($r)
    {
        $sql=DB::table('mat_matriculas as mm')
            ->Join('mat_matriculas_detalles AS mmd', function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado','=',1);
            })
            ->Join('mat_programaciones AS mp', function($join){
                $join->on('mp.id','=','mmd.programacion_id')
                ->where('mp.estado','=',1);
            })
            ->Join('personas AS p', function($join){
                $join->on('p.id','=','mp.persona_id');
            })
            ->Join('mat_cursos AS mc', function($join){
                $join->on('mc.id','=','mp.curso_id');
            })
            ->select(
            'mmd.id as matricula_detalle_id',
            'mc.curso',
            DB::raw('CONCAT(p.nombre, p.paterno, p.materno) as profesor'),
            DB::raw('DATE(mp.fecha_inicio) as fecha_inicio'),
            DB::raw('IFNULL(mmd.nota_curso_alum,"") as nota_curso_alum'),
            DB::raw('IFNULL(mmd.nota_curso_alum,0) as nota_curso_alum'),
            DB::raw('DATE(mp.fecha_final) as fecha_final'),
            DB::raw('CURDATE() as hoy')
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("alumno_id") ){
                        $alumno_id=trim($r->alumno_id);
                        if( $alumno_id !='' ){
                            $query->where('mm.alumno_id','=', $alumno_id);
                        }
                    }
                }
            );
        $result = $sql->orderBy('mmd.id','asc')->paginate(10);
        return $result;
    }


    public static function editarNotaAlum($r)
    {
        $especialidad_id = Auth::user()->id;
        $id_mat = $r->id_mat;
        $notas = $r->notas;

        //ESTO HACE QUE GRABE EN LA TABLE DETALLE LOS CURSOS, LO QUE SE ESCOJE EN EL COMBO CURSO
        for($i=0;$i<count($id_mat);$i++)
        {
        	if($notas[$i] !='' ){
	            $alumno = MatriculaDetalle::find($id_mat[$i]);
	        	$alumno->nota_curso_alum = $r->notas[$i];
	            $alumno->save();
        	}
        }
    }


    public static function runEditStatus($r)
    {
        $especialidad_id = Auth::user()->id;
        $especialidad = Especialidad::find($r->id);
        $especialidad->estado = trim( $r->estadof );
        $especialidad->persona_id_updated_at=$especialidad_id;
        $especialidad->save();
    }
    // --
}
