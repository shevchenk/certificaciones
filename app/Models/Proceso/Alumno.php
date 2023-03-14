<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Models\Mantenimiento\Persona;
use App\Models\Mantenimiento\Menu;
use App\Models\Proceso\MatriculaDetalle;
use App\Models\Proceso\MatriculaSaldo;
use App\Models\Proceso\MatriculaCuota;

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
            ->where('empresa_id','=',Auth::user()->empresa_id)
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
            'mc.curso','mp.link','mm.fecha_matricula','mmd.comentario', 'mmd.archivo_certificado',
            DB::raw('IF(mp.sucursal_id=1,"Virtual","Presencial") as modalidad'),
            DB::raw('CONCAT(p.nombre," ", p.paterno," ", p.materno) as profesor'),
            DB::raw('DATE(mp.fecha_inicio) as fecha'),
            DB::raw('CONCAT(TIME(mp.fecha_inicio)," a ",TIME(mp.fecha_final)) as horario'),
            DB::raw('s.sucursal as sucursal'),
            DB::raw('  
                IFNULL((SELECT SUM(sal)
                        FROM (
                            SELECT matricula_id, cuota, MIN(saldo) sal
                            FROM mat_matriculas_saldos
                            WHERE estado=1
                            GROUP BY matricula_id,cuota
                            HAVING sal > 0
                        ) cuota_deuda
                        WHERE matricula_id = mm.id),0)
                +
                IFNULL((SELECT SUM(ep.monto_cronograma)
                        FROM mat_especialidades_programaciones_cronogramas ep 
                        INNER JOIN mat_matriculas m ON m.especialidad_programacion_id = ep.especialidad_programacion_id AND m.estado=1
                        LEFT JOIN mat_matriculas_saldos ms ON ms.matricula_id=m.id AND ms.cuota=ep.cuota
                        LEFT JOIN mat_matriculas_cuotas mc ON mc.matricula_id=m.id AND mc.cuota=ep.cuota
                        WHERE ep.estado = 1
                        AND ms.id IS NULL AND mc.id IS NULL 
                        AND ep.fecha_cronograma<= CURDATE()
                        AND m.id = mm.id),0)
                +
                IFNULL((SELECT MIN(saldo) sal
                        FROM mat_matriculas_saldos
                        WHERE estado=1
                        AND matricula_detalle_id= mmd.id
                        GROUP BY matricula_detalle_id
                        HAVING sal > 0 ),0) AS deuda_total ')
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
                $join->on('mc.id','=','mp.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
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
                $join->on('mc.id','=','mp.curso_id')
                ->where('mc.empresa_id', Auth::user()->empresa_id);
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
        if( $r->has('id_mat') ){
            for($i=0;$i<count($id_mat);$i++)
            {
                if($notas[$i] !='' ){
                    $alumno = MatriculaDetalle::find($id_mat[$i]);
                    $alumno->nota_curso_alum = $r->notas[$i];
                    $alumno->save();
                }
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

    public static function LoadSaldos( $r )
    {
        $first = DB::table('mat_matriculas AS m')
                ->Join('personas AS p', function($join){
                    $join->on('p.id','=','m.persona_id');
                })
                ->Join(DB::raw('
                    (SELECT matricula_id, cuota, MIN(saldo) saldo
                    FROM mat_matriculas_saldos
                    WHERE matricula_detalle_id IS NULL
                    AND estado=1
                    GROUP BY matricula_id, cuota
                    HAVING saldo > 0) AS s
                '), function($join){
                    $join->on('s.matricula_id','=','m.id');
                })
                ->select('p.dni','p.paterno','p.materno','p.nombre',"p.dni AS curso",
                'm.id AS matricula_id','s.cuota AS matricula_detalle_id','s.saldo')
                ->where( function($query) use($r){
                    if( $r->has('dni') AND trim($r->dni)!='' ){
                        $query->where('p.dni', 'like', '%'.trim($r->dni).'%' );
                    }
                    if( $r->has('paterno') AND trim($r->paterno)!='' ){
                        $query->where('p.paterno', 'like', '%'.trim($r->paterno).'%' );
                    }
                    if( $r->has('materno') AND trim($r->materno)!='' ){
                        $query->where('p.materno', 'like', '%'.trim($r->materno).'%' );
                    }
                    if( $r->has('nombre') AND trim($r->nombre)!='' ){
                        $query->where('p.nombre', 'like', '%'.trim($r->nombre).'%' );
                    }
                });

        $sql=   DB::table('mat_matriculas AS m')
                ->Join('mat_matriculas_detalles AS md', function($join){
                    $join->on('md.matricula_id','=','m.id')
                    ->where('md.estado','=',1);
                })
                ->Join('personas AS p', function($join){
                    $join->on('p.id','=','m.persona_id');
                })
                ->Join('mat_cursos AS c', function($join){
                    $join->on('c.id','=','md.curso_id');
                })
                ->select('p.dni','p.paterno','p.materno','p.nombre','c.curso',
                'md.matricula_id','md.id AS matricula_detalle_id','md.saldo')
                ->where('saldo','>','0')
                ->where( function($query) use($r){
                    if( $r->has('dni') AND trim($r->dni)!='' ){
                        $query->where('p.dni', 'like', '%'.trim($r->dni).'%' );
                    }
                    if( $r->has('paterno') AND trim($r->paterno)!='' ){
                        $query->where('p.paterno', 'like', '%'.trim($r->paterno).'%' );
                    }
                    if( $r->has('materno') AND trim($r->materno)!='' ){
                        $query->where('p.materno', 'like', '%'.trim($r->materno).'%' );
                    }
                    if( $r->has('nombre') AND trim($r->nombre)!='' ){
                        $query->where('p.nombre', 'like', '%'.trim($r->nombre).'%' );
                    }
                })
                ->union($first);
        $r = $sql->get();

        return $r;
    }

    public static function ListarSaldos( $r )
    {
        $sql=   DB::table('mat_matriculas_saldos AS ms')
                ->leftJoin('bancos AS b', 'b.id', '=', 'ms.tipo_pago')
                ->select('ms.id','ms.precio','ms.pago','ms.saldo','ms.matricula_detalle_id'
                ,'ms.archivo','ms.nro_pago', 'ms.fecha_pago',
                DB::raw('b.banco AS tipo_pago')
                )
                ->where( function($query) use($r){
                    if( $r->has('matricula_detalle_id') AND trim($r->matricula_detalle_id)!='' ){
                        $query->where( 'ms.matricula_detalle_id', trim($r->matricula_detalle_id) );
                    }
                    elseif( $r->has('matricula_id') AND trim($r->matricula_id)!='' ){
                        $query->where( 'ms.matricula_id', trim($r->matricula_id) );
                        $query->where( 'ms.cuota', trim($r->cuota) );
                    }
                    else{
                        $query->where('ms.id',0);
                    }
                })
                ->where('ms.estado', 1)
                ->orderBy('ms.id','desc');
        $r = $sql->get();

        return $r;
    }

    public static function SaveSaldos($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $MS = MatriculaSaldo::find($r->id);
        
        $MSF = new MatriculaSaldo;
        if( trim($MS->matricula_detalle_id)=='' ){
            $MSF->matricula_id = $MS->matricula_id;
            $MSF->cuota = $MS->cuota;
        }
        else{
            $MSF->matricula_detalle_id = $MS->matricula_detalle_id;
        }

        $MSF->precio = $MS->precio;
        $MSF->pago = trim($r->monto_pago);
        $MSF->tipo_pago = trim($r->tipo_pago);
        $MSF->nro_pago = trim($r->nro_pago);

        $saldo= $MS->saldo - trim($r->monto_pago);
        if( $saldo<0 ){ $saldo=0; }
        $MSF->saldo = $saldo;
        $MSF->persona_id_created_at = $user_id;
        $MSF->save();

        if( trim($MS->matricula_detalle_id)!='' ){
            $MD = MatriculaDetalle::find($MS->matricula_detalle_id);
            $MD->saldo = $saldo;
            $MD->persona_id_updated_at = $user_id;
            $MD->save();
        }

        if( trim($r->pago_nombre)!='' ){
            $type=explode(".",$r->pago_nombre);
            $extension=".".$type[1];
        }
        $url = "upload/saldos/S".$MSF->id.$extension; 
        if( trim($r->pago_archivo)!='' ){
            $MSF->archivo= $url;
            $MSF->save();
            Menu::fileToFile($r->pago_archivo, $url);
        }
        DB::commit();

        $return['matricula_detalle_id'] = $MS->matricula_detalle_id;
        $return['matricula_id'] = $MS->matricula_id;
        $return['cuota'] = $MS->cuota;
        $return['saldo'] = $saldo;
        $return['rst'] = 1;
        $return['msj'] = 'Registro realizado';
        return $return;
    }

    public static function GuardarPago($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $saldo=0;
        if( trim($r->matricula_detalle_id)=='' ){
            $MS = MatriculaSaldo::where('matricula_id',$r->matricula_id)
                    ->where('cuota',$r->cuota)
                    ->where('estado',1)
                    ->orderBy('id','DESC')
                    ->first();

            if( isset($MS->id) ){
                $MSF = new MatriculaSaldo;
                $MSF->matricula_id = $MS->matricula_id;
                $MSF->cuota = $MS->cuota;
                $MSF->precio = $MS->precio;
                $MSF->pago = trim($r->monto_pago);
                $MSF->tipo_pago = trim($r->tipo_pago);
                $MSF->nro_pago = trim($r->nro_pago);
                $MSF->fecha_pago = trim($r->fecha_pago);

                $saldo= $MS->saldo - trim($r->monto_pago);
                if( $saldo<0 ){ $saldo=0; }
                $MSF->saldo = $saldo;
                $MSF->persona_caja_id = trim($r->persona_caja_id);
                $MSF->persona_marketing_id = trim($r->marketing_id);
                $MSF->persona_id_created_at = $user_id;
                $MSF->save();

                $extension='';
                if( trim($r->pago_nombre)!='' ){
                    $type=explode(".",$r->pago_nombre);
                    $extension=".".$type[1];
                }
                $url = "upload/saldos/S".$MSF->id.$extension; 
                if( trim($r->pago_archivo)!='' ){
                    $MSF->archivo= $url;
                    $MSF->save();
                    Menu::fileToFile($r->pago_archivo, $url);
                }
            }
            else{
                $MC = new MatriculaCuota;
                $MC->matricula_id= $r->matricula_id;
                //$MC->sucursal_id= $r->sucursal[$r->index];
                $MC->cuota= $r->cuota;

                $MC->nro_cuota= trim($r->nro_pago);
                $MC->monto_cuota= trim($r->monto_pago);
                $MC->tipo_pago_cuota= trim($r->tipo_pago);
                $MC->fecha_pago_cuota= trim($r->fecha_pago);

                $extension='';
                if( trim($r->pago_nombre)!='' ){
                    $type=explode(".",$r->pago_nombre);
                    $extension=".".$type[1];
                }
                $url = "upload/m$r->matricula_id/cuotas/C".$r->cuota.$extension; 
                if( trim($r->pago_archivo)!='' ){
                    $MC->archivo_cuota= $url;
                    Menu::fileToFile($r->pago_archivo, $url);
                }

                $MC->persona_caja_id = $r->persona_caja_id;
                $MC->persona_marketing_id = $r->marketing_id;
                $MC->estado = 1;
                $MC->persona_id_created_at=$user_id;
                $MC->save();


                /*********************** Se Agrega Saldos ******************/
                $matricula = Matricula::find($MC->matricula_id);
                $programacionVal= DB::table('mat_especialidades_programaciones_cronogramas')
                                  ->where('cuota',$MC->cuota)
                                  ->where('especialidad_programacion_id',$matricula->especialidad_programacion_id)
                                  ->where('estado',1)
                                  ->first();
                $monto_precio= $programacionVal->monto_cronograma*1;
                $monto_saldo= $programacionVal->monto_cronograma*1 - $MC->monto_cuota;
                if($monto_saldo<0){
                    $monto_saldo=0;
                }
                $saldo = $monto_saldo;

                if( $monto_saldo>0 ){
                    $mtsaldo= new MatriculaSaldo;
                    $mtsaldo->matricula_id= $MC->matricula_id;
                    $mtsaldo->nro_pago= $MC->nro_cuota;
                    $mtsaldo->archivo= $MC->archivo_cuota;
                    $mtsaldo->cuota= $MC->cuota;
                    $mtsaldo->saldo= $monto_saldo;
                    $mtsaldo->precio= $monto_precio;
                    $mtsaldo->pago= $MC->monto_cuota;
                    $mtsaldo->tipo_pago= $MC->tipo_pago_cuota;
                    $mtsaldo->fecha_pago= $MC->fecha_pago_cuota;
                    $mtsaldo->persona_caja_id = $MC->persona_caja_id;
                    $mtsaldo->persona_marketing_id = $MC->persona_marketing_id;
                    $mtsaldo->persona_id_created_at= Auth::user()->id;
                    $mtsaldo->save();
                }
            }
        }
        else{
            $MS = MatriculaSaldo::where('matricula_detalle_id',$r->matricula_detalle_id)
                    ->where('estado',1)
                    ->orderBy('id','DESC')
                    ->first();

            $MSF = new MatriculaSaldo;
            $MSF->matricula_detalle_id = $MS->matricula_detalle_id;
            $MSF->precio = $MS->precio;
            $MSF->pago = trim($r->monto_pago);
            $MSF->tipo_pago = trim($r->tipo_pago);
            $MSF->nro_pago = trim($r->nro_pago);
            $MSF->fecha_pago = trim($r->fecha_pago);

            $saldo= $MS->saldo - trim($r->monto_pago);
            if( $saldo<0 ){ $saldo=0; }
            $MSF->saldo = $saldo;
            $MSF->persona_caja_id = trim($r->persona_caja_id);
            $MSF->persona_marketing_id = trim($r->marketing_id);
            $MSF->persona_id_created_at = $user_id;
            $MSF->save();

            $extension='';
            if( trim($r->pago_nombre)!='' ){
                $type=explode(".",$r->pago_nombre);
                $extension=".".$type[1];
            }
            $url = "upload/saldos/S".$MSF->id.$extension; 
            if( trim($r->pago_archivo)!='' ){
                $MSF->archivo= $url;
                $MSF->save();
                Menu::fileToFile($r->pago_archivo, $url);
            }

            $MD = MatriculaDetalle::find($MS->matricula_detalle_id);
            $MD->saldo = $saldo;
            $MD->persona_id_updated_at = $user_id;
            $MD->save();
        }
        
        DB::commit();

        $return['matricula_detalle_id'] = trim($r->matricula_detalle_id);
        $return['matricula_id'] = trim($r->matricula_id);
        $return['cuota'] = trim($r->cuota);
        $return['saldo'] = $saldo;
        $return['rst'] = 1;
        $return['msj'] = 'Registro realizado';
        return $return;
    }
    // --
}
