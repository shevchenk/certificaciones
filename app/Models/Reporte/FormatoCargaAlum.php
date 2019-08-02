<?php
namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class FormatoCargaAlum extends Model
{
    protected   $table = '';

    public static function runLoad($r)
    {
      if(trim($r->formato) == 'S')
        $tipo_certif = 3;
      else
        $tipo_certif = 2;

      $sql=DB::table('mat_alumnos AS ma')
          ->join('personas AS p',function($join){
              $join->on('ma.persona_id','=','p.id');
          })
          ->join('mat_matriculas AS mm',function($join){
              $join->on('ma.id','=','mm.alumno_id');
          })
          ->join('mat_matriculas_detalles AS mmd',function($join){
              $join->on('mm.id','=','mmd.matricula_id');
          })
          ->join('mat_programaciones AS mp',function($join){
              $join->on('mmd.programacion_id','=','mp.id');
          })
          ->join('mat_cursos AS mc',function($join){
              $join->on('mp.curso_id','=','mc.id')
              ->where('mc.empresa_id', Auth::user()->empresa_id);
          })
          ->join('mat_ubicacion_region AS mur',function($join){
              $join->on('ma.region_id','=','mur.id');
          })
          ->join('mat_ubicacion_provincia AS mup',function($join){
              $join->on('ma.provincia_id','=','mup.id');
          })
          ->join('mat_ubicacion_distrito AS mud',function($join){
              $join->on('ma.distrito_id','=','mud.id');
          })
            ->select('mm.sucursal_destino_id as sucursal_id',
                      DB::raw('mmd.id as id_envio'),
                      'p.nombre', 'p.paterno', 'p.materno', 'p.dni',
                      DB::raw('mc.certificado_curso as certificado'),
                      DB::raw('mmd.nota_curso_alum as nota_certificado'),
                      DB::raw($tipo_certif.' as tipo_certificado'),
                     'ma.direccion', 'ma.referencia', 'mur.region', 'mup.provincia', 'mud.distrito')
            ->where(
                function($query) use ($r){
                    if( $r->has("formato")){
                        $formato=trim($r->formato);
                        if($formato == 'S') //Seminario
                          $query ->where('mc.tipo_curso', '=', 2);
                        else
                          $query ->where('mc.tipo_curso','=', 1);
                    }
                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE_FORMAT(mm.fecha_matricula,"%Y-%m")'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }
                    if( $r->has("fecha_inicial_dia") AND $r->has("fecha_final_dia")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween(DB::raw('DATE(mm.fecha_matricula)'), array($r->fecha_inicial,$r->fecha_final));
                        }
                    }
                }
            );
            

        $result = $sql->orderBy('p.dni','asc')->get();
        return $result;
    }

    public static function runExport($r)
    {
        $rsql= FormatoCargaAlum::runLoad($r);

        $length=array(
            'A'=>15,'B'=>12,'C'=>20,'D'=>20,'E'=>20,'F'=>15,'G'=>15,'H'=>15,
            'I'=>15,'J'=>25,'K'=>15,
            'L'=>15,'M'=>15,'N'=>15
        );
        $cabecera=array(
            'ID SUCURSAL','ID ENVIO','NOMBRE','APE. PATERNO','APE. MATERNO','DNI','CERTIFICADO','NOTA_CERTIF',
            'TIPO_CERTIF','DIRECCION','REFERENCIA',
            'REGION','PROVINCIA','DISTRITO'
        );
        $campos=array(
            'sucursal_id', 'id_envio', 'nombre', 'paterno', 'materno', 'dni', 'certificado',
            'nota_certificado', 'tipo_certificado', 'direccion', 'referencia', 'region', 'provincia',
            'distrito'
        );

        $r['data']=$rsql;
        $r['cabecera']=$cabecera;
        $r['campos']=$campos;
        $r['length']=$length;
        $r['max']='N'; // Max. Celda en LETRA
        return $r;
    }
}
