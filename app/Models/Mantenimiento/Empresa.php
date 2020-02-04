<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class Empresa extends Model
{
    protected   $table = 'empresas';

    public static function runEditStatus($r)
    {
        $usuario = Auth::user()->id;
        $tipo_evaluacion = Empresa::find($r->id);
        $tipo_evaluacion->estado = trim( $r->estadof );
        $tipo_evaluacion->persona_id_updated_at=$usuario;
        $tipo_evaluacion->save();
    }

    public static function runNew($r)
    {
        DB::beginTransaction();
        $usuario = Auth::user()->id;
        $empresa = new Empresa;
        $empresa->empresa = trim( $r->empresa );
        $empresa->nota_minima = trim( $r->nota_minima );
        $empresa->trabajo_final = trim( $r->trabajo_final );
        if( isset($r->peso_trabajo_final) ){
            $empresa->peso_trabajo_final = trim( $r->peso_trabajo_final );
        }
        $empresa->persona_id_created_at=$usuario;
        $empresa->save();

        $peso_evaluacion= $r->peso_evaluacion;
        $tipo_evaluacion= $r->tipo_evaluacion;
        for ($i=0; $i < count($tipo_evaluacion) ; $i++) { 
            $EE = new EmpresaEvaluacion;
            $EE->empresa_id = $empresa->id;
            $EE->tipo_evaluacion_id = $tipo_evaluacion[$i];
            $EE->persona_id_created_at = $usuario;
            $EE->peso_evaluacion=$peso_evaluacion[$i];
            $EE->orden= ($i+1);
            $EE->estado=1;
            $EE->save();
        }

        $sql="
        INSERT INTO tipo_llamadas (empresa_id, tipo_llamada, plataforma, peso, obs, estado, created_at, persona_id_created_at)
        SELECT $empresa->id, tipo_llamada, plataforma, peso, obs, 1, NOW(), 1
        FROM tipo_llamadas
        WHERE empresa_id = 2";
        DB::insert($sql);

        $sql="
        SELECT MIN(id)+7 AS id
        FROM tipo_llamadas
        WHERE empresa_id=".$empresa->id;
        $idmin = DB::select($sql);
        $idmintl = $idmin[0]->id;

        //dd($idmintl . " | ".$empresa->id);

        $sql="
        INSERT INTO tipo_llamadas_sub (tipo_llamada_id, tipo_llamada_sub, estado, created_at, persona_id_created_at)
        SELECT $idmintl, tls.tipo_llamada_sub, 1, NOW(), 1
        FROM tipo_llamadas tl 
        INNER JOIN tipo_llamadas_sub tls ON tls.tipo_llamada_id = tl.id
        WHERE tl.empresa_id=2";
        DB::insert($sql);

        $sql="
        SELECT MIN(tls.id) AS id
        FROM tipo_llamadas tl 
        INNER JOIN tipo_llamadas_sub tls ON tls.tipo_llamada_id = tl.id
        WHERE tl.empresa_id=".$empresa->id;
        $idmin = DB::select($sql);
        $idmintl = $idmin[0]->id;

        $sql="
        INSERT INTO tipo_llamadas_sub_detalle (tipo_llamada_sub_id, tipo_llamada_sub_detalle, detalle, estado, created_at, persona_id_created_at)
        SELECT $idmintl - 42 + tls.id, tlsd.tipo_llamada_sub_detalle, '', 1, NOW(), 1
        FROM tipo_llamadas tl 
        INNER JOIN tipo_llamadas_sub tls ON tls.tipo_llamada_id = tl.id
        INNER JOIN tipo_llamadas_sub_detalle tlsd ON tlsd.tipo_llamada_sub_id=tls.id 
        WHERE tl.empresa_id=2";
        DB::insert($sql);

        DB::commit();
    }

    public static function runEdit($r)
    {
        DB::beginTransaction();
        $usuario = Auth::user()->id;
        $empresa = Empresa::find($r->id);
        $empresa->empresa = trim( $r->empresa );
        $empresa->nota_minima = trim( $r->nota_minima );
        $empresa->trabajo_final = trim( $r->trabajo_final );
        if( isset($r->peso_trabajo_final) ){
            $empresa->peso_trabajo_final = trim( $r->peso_trabajo_final );
        }
        $empresa->persona_id_updated_at=$usuario;
        $empresa->save();

        DB::table('empresas_tipos_evaluaciones')
        ->where('empresa_id','=', $r->id)
        ->update(
            array(
                'estado' => 0,
                'persona_id_updated_at' => $usuario,
                'updated_at' => date('Y-m-d H:i:s')
                )
            );

        $peso_evaluacion= $r->peso_evaluacion;
        $tipo_evaluacion= $r->tipo_evaluacion;
        for ($i=0; $i < count($tipo_evaluacion) ; $i++) { 
            $valida=DB::table('empresas_tipos_evaluaciones')
            ->select('id')
            ->where('empresa_id', '=', $r->id)
            ->where('tipo_evaluacion_id',$tipo_evaluacion[$i])
            ->first();

            if( isset($valida->id) AND $valida->id!='' ){
                $EE = EmpresaEvaluacion::find($valida->id);
                $EE->persona_id_updated_at = $usuario;
            }
            else{
                $EE = new EmpresaEvaluacion;
                $EE->empresa_id = $r->id;
                $EE->tipo_evaluacion_id = $tipo_evaluacion[$i];
                $EE->persona_id_created_at = $usuario;
            }
            $EE->peso_evaluacion=$peso_evaluacion[$i];
            $EE->orden= ($i+1);
            $EE->estado=1;
            $EE->save();
        }
        DB::commit();
    }

    public static function runLoad($r)
    {   
        $sql=DB::table('empresas AS e')
            ->select(
            'e.id',
            'e.empresa','e.nota_minima',
            'e.trabajo_final','e.peso_trabajo_final',
            'e.estado'
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("empresa") ){
                        $empresa=trim($r->empresa);
                        if( $empresa !='' ){
                            $query->where('e.empresa','like','%'.$empresa.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('e.estado',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('e.empresa','asc')->paginate(10);
        return $result;
    }
    
    public static function ListEmpresa($r)
    {  
        $sql=Empresa::select('id','empresa','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('empresa','asc')->get();
        return $result;
    }

    public static function ListEmpresaUsuario($r)
    {  
        $persona_id= Auth::user()->id;
        $result=DB::table('personas as p')
                ->join('personas_empresas as pe', function($join){
                        $join->on('pe.persona_id','p.id')
                        ->where('pe.estado',1);
                })
                ->join('empresas AS e', function($join){
                        $join->on('e.id','pe.empresa_id')
                        ->where('e.estado',1);
                })
                ->select('e.id','e.empresa')
                ->where('pe.persona_id',$persona_id)
                ->orderBy('e.empresa','asc')
                ->get();

        return $result;
    }

    public static function CargarEvaluaciones($r)
    {
        $sql=DB::table('empresas_tipos_evaluaciones as ete')
             ->join('tipos_evaluaciones as te','te.id','=','ete.tipo_evaluacion_id')
             ->select('te.id','te.tipo_evaluacion','ete.peso_evaluacion')
             ->where('ete.empresa_id',$r->id)
             ->where('ete.estado',1)
             ->orderBy('orden','asc')
             ->get();
        return $sql;
    }

    public static function ListarRegion($r)
    {  
        $fecha_ini= $r->fecha_ini;
        $fecha_fin= $r->fecha_fin;
        $empresa_id= $r->empresas;
        $sql="  SELECT DISTINCT(distrito) as distrito
                FROM personas_captadas
                WHERE estado = 1
                AND DATE(created_at) BETWEEN '$fecha_ini' AND '$fecha_fin'
                AND empresa_id = '$empresa_id'
                AND distrito != ''
                ORDER BY distrito";
        $result= DB::select($sql);
        return $result;
    }

    public static function ListarCampana($r)
    {  
        $fecha_ini= $r->fecha_ini;
        $fecha_fin= $r->fecha_fin;
        $empresa_id= $r->empresas;
        $sql="  SELECT DISTINCT(ad_name) as campana
                FROM personas_captadas
                WHERE estado = 1
                AND DATE(created_at) BETWEEN '$fecha_ini' AND '$fecha_fin'
                AND empresa_id = '$empresa_id'
                AND ad_name != ''
                ORDER BY ad_name";
        $result= DB::select($sql);
        return $result;
    }
}
