<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class MatriculaDetalle extends Model
{
    protected   $table = 'mat_matriculas_detalles';

    public static function runEditDetalleStatus($r)
    {
        $user_id = Auth::user()->id;
        $md = MatriculaDetalle::find($r->id);
        $md->estado = 0;
        $md->persona_id_updated_at=$user_id;
        $md->save();
    }

    public static function runEditDetalleEspecialidadStatus($r)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $md = MatriculaDetalle::find($r->id);
        $md->estado = 0;
        $md->persona_id_updated_at=$user_id;
        $md->save();

        $mdn = new MatriculaDetalle;
        $mdn->matricula_id = $md->matricula_id;
        $mdn->norden = $md->norden;
        $mdn->curso_id = $md->curso_id;
        $mdn->especialidad_id = $md->especialidad_id;
        $mdn->nro_pago = $md->nro_pago;
        $mdn->monto_pago = $md->monto_pago;
        $mdn->nro_pago_certificado = $md->nro_pago_certificado;
        $mdn->monto_pago_certificado = $md->monto_pago_certificado;
        $mdn->tipo_matricula_detalle = $md->tipo_matricula_detalle;
        $mdn->tipo_pago = $md->tipo_pago;
        $mdn->archivo_pago = $md->archivo_pago;
        $mdn->archivo_pago_certificado = $md->archivo_pago_certificado;
        $mdn->archivo_dni = $md->archivo_dni;
        $mdn->nota_curso_alum = $md->nota_curso_alum;
        $mdn->gratis = $md->gratis;
        $mdn->comentario = $md->comentario;
        $mdn->validavideo = $md->validavideo;
        $mdn->validadescarga = $md->validadescarga;
        $mdn->fecha_entrega = $md->fecha_entrega;
        $mdn->comentario_entrega = $md->comentario_entrega;
        $mdn->estado=1;
        $mdn->persona_id_created_at=$user_id;
        $mdn->save();
        DB::commit();
    }

    public static function CargarMP($r)
    {
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_matriculas_detalles AS mmd',function($join){
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mp.sucursal_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->select('mmd.programacion_id', 'mmd.especialidad_id'
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") ) AS tipo_formacion')
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) ) AS formacion')
                    , DB::raw( 'MIN(mc.curso) AS curso'), DB::raw('MIN(s.sucursal) AS local'), DB::raw('MIN(mp.dia) AS frecuencia')
                    , DB::raw( 'MIN( CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) ) AS horario')
                    , DB::raw( 'MIN(mp.turno) AS turno'), DB::raw('MIN( DATE(mp.fecha_inicio) ) AS inicio')
                    , DB::raw( 'COUNT( DISTINCT(mmd.id) ) AS cant')
                    )
            ->where( 
                function($query) use ($r){

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("especialidad") OR $r->has('especialidad2') ){
                        if( $r->has('especialidad2') ){
                            $r['especialidad'] = explode(",", trim($r->especialidad2) );
                        }
                        $especialidad = $r->especialidad;
                        if( count($especialidad)>0 AND trim($especialidad[0])!='' ){
                            $query ->whereIn('me.id', $especialidad);
                        }
                    }

                    if( $r->has("curso") OR $r->has('curso2') ){
                        if( $r->has('curso2') ){
                            $r['curso'] = explode(",", trim($r->curso2) );
                        }
                        $curso = $r->curso;
                        if( count($curso)>0 AND trim($curso[0])!='' ){
                            $query ->whereIn('mc.id', $curso);
                        }
                    }

                    if( $r->has("paterno") ){
                        $paterno = trim($r->paterno);
                        if( $paterno !='' ){
                            $query ->where('p.paterno','like', '%'.$paterno.'%');
                        }
                    }

                    if( $r->has("materno") ){
                        $materno = trim($r->materno);
                        if( $materno !='' ){
                            $query ->where('p.materno','like', '%'.$materno.'%');
                        }
                    }

                    if( $r->has("nombre") ){
                        $nombre = trim($r->nombre);
                        if( $nombre !='' ){
                            $query ->where('p.nombre','like', '%'.$nombre.'%');
                        }
                    }

                    if( $r->has("dni") ){
                        $dni = trim($r->dni);
                        if( $dni !='' ){
                            $query ->where('p.dni','like', '%'.$dni.'%');
                        }
                    }
                }
            )
            ->where('mm.estado',1)
            ->groupBy('mmd.especialidad_id', 'mmd.programacion_id');
            
        $result =   $sql->orderBy('mmd.especialidad_id','desc')
                        ->orderBy('mmd.programacion_id','asc')
                        ->get();

        return $result;
    }

    public static function CargarProgramacionMP($r)
    {
        $result = [];
        if( $r->has("especialidad_id") AND $r->especialidad_id > 0 ){
            $result=DB::table('mat_programaciones AS mp')
                ->join('mat_cursos AS mc',function($join) use($r){
                    $join->on('mc.id','=','mp.curso_id');
                    if( !$r->has('global') ){
                        $join->where('mc.empresa_id', Auth::user()->empresa_id);
                    }
                })
                ->join('sucursales AS s',function($join){
                    $join->on('s.id','=','mp.sucursal_id');
                })
                ->join('mat_cursos_especialidades AS mce',function($join) use($r){
                    $join->on('mce.curso_id','=','mp.curso_id')
                    ->where('mce.estado', '1')
                    ->where('mce.especialidad_id', $r->especialidad_id);
                })
                ->select('mp.id as programacion_id', 'mp.curso_id'
                        ,  'mc.curso', 's.sucursal AS local', 'mp.dia AS frecuencia'
                        ,  DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final))  AS horario')
                        ,  'mp.turno', DB::raw('DATE(mp.fecha_inicio)  AS inicio')
                )
                ->where('mp.estado', '1')
                ->orderBy('mc.curso','asc')
                ->get();
        }
        else{
            $result=DB::table('mat_programaciones AS mp')
                ->join('mat_cursos AS mc',function($join) use($r){
                    $join->on('mc.id','=','mp.curso_id');
                    if( !$r->has('global') ){
                        $join->where('mc.empresa_id', Auth::user()->empresa_id);
                    }
                })
                ->join('sucursales AS s',function($join){
                    $join->on('s.id','=','mp.sucursal_id');
                })
                ->select('mp.id as programacion_id', 'mp.curso_id'
                        ,  'mc.curso', 's.sucursal AS local', 'mp.dia AS frecuencia'
                        ,  DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final))  AS horario')
                        ,  'mp.turno', DB::raw('DATE(mp.fecha_inicio)  AS inicio')
                )
                ->where('mp.estado', '1')
                ->orderBy('mc.curso','asc')
                ->get();
        }
        return $result;
    }

    public static function ActualizarMP($r)
    {
        /* Información del grupo afectar */
        $programacion_id = $r->programacion_id;
        /*********************************/
        /* Información del grupo afectar */
        $cant = $r->cant;
        $especialidad = $r->especialidad;
        $programacion = $r->programacion;
        /*********************************/
        $where = "";
        $sql = "";

        if( $r->has("paterno") AND trim($r->paterno) != '' ){
            $where .= " AND p.paterno LIKE '%$r->paterno%' ";   
        }

        if( $r->has("materno") AND trim($r->materno) != '' ){
            $where .= " AND p.materno LIKE '%$r->materno%' ";   
        }

        if( $r->has("nombre") AND trim($r->nombre) != '' ){
            $where .= " AND p.nombre LIKE '%$r->nombre%' ";   
        }

        if( $r->has("dni") AND trim($r->dni) != '' ){
            $where .= " AND p.dni LIKE '%$r->dni%' ";   
        }

        $inicio = 0;
        for ($i=0; $i < count($cant); $i++) { 
            $especialidad_aux = "";
            $especialidad_id = "";
            if( $r->has("especialidad_id") AND $r->especialidad_id > 0 ){
                $especialidad_aux = ", especialidad_id = $especialidad[$i] ";
                $especialidad_id = " AND especialidad_id = $r->especialidad_id ";
            }

            $prog = explode("_", $programacion[$i]);
            $programacion_aux = $prog[0];
            $curso_aux = $prog[1];
            
            $sql = "UPDATE mat_matriculas_detalles mmd
                    INNER JOIN (
                        SELECT md.id 
                        FROM mat_matriculas_detalles md
                        INNER JOIN mat_matriculas m ON m.id = md.matricula_id
                        INNER JOIN personas p ON p.id = m.persona_id 
                        WHERE programacion_id = $programacion_id
                        $especialidad_id
                        AND md.estado = 1
                        $where
                        ORDER BY p.paterno, p.materno, p.nombre
                        LIMIT 0,$cant[$i]
                    ) mmd_aux ON mmd_aux.id = mmd.id
                    SET programacion_id = $programacion_aux, curso_id = $curso_aux $especialidad_aux";
            DB::update($sql);
            //$inicio += $cant[$i];
        }
        //dd($sql);

    }
}
