<?php

namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\MatriculaDetalle;
use Illuminate\Support\Facades\Input;
use DB;

class Matricula extends Model
{
    protected   $table = 'mat_matriculas';

    public static function runNew($r)
    {
        /******Validar si alumno existe ***/
        $alumno=Alumno::where('persona_id','=',$r->persona_id)->first();

        DB::beginTransaction();
        if($alumno){
           $al= Alumno::find($alumno->id);
           $al->region_id=trim( $r->region_id);
           $al->provincia_id=trim( $r->provincia_id);
           $al->distrito_id=trim( $r->distrito_id);
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           $al->persona_id_updated_at=Auth::user()->id;
           $al->save();
        }else {
           $al= new Alumno;
           $al->persona_id=trim( $r->persona_id);
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           $al->region_id=trim( $r->region_id);
           $al->provincia_id=trim( $r->provincia_id);
           $al->distrito_id=trim( $r->distrito_id);
           $al->persona_id_created_at=Auth::user()->id;
           $al->save();
        }

        $matricula = new Matricula;
        $matricula->alumno_id = trim($al->id);
        $matricula->tipo_participante_id = trim($r->tipo_participante_id);
        $matricula->persona_id = trim( $r->persona_id);
        $matricula->sucursal_id = trim( $r->sucursal_id);
        if( trim( $r->persona_caja_id )!=''){
        $matricula->persona_caja_id = trim( $r->persona_caja_id );}
        if( trim( $r->responsable_id )!=''){
        $matricula->persona_matricula_id = trim( $r->responsable_id );}
        if( trim( $r->marketing_id )!=''){
        $matricula->persona_marketing_id = trim( $r->marketing_id );}
        $matricula->fecha_matricula = trim( $r->fecha );
        $matricula->tipo_matricula = trim( $r->tipo_matricula );
        if($r->exonera_matricula!='on'){
            if( trim( $r->nro_pago_matricula )!=''){
            $matricula->nro_pago = trim( $r->nro_pago_matricula);}
            if( trim( $r->monto_pago_matricula )!=''){
            $matricula->monto_pago = trim( $r->monto_pago_matricula );}
        }
        if($r->exonera_inscripcion!='on'){
            if( trim( $r->nro_pago_inscripcion )!=''){
            $matricula->nro_pago_inscripcion = trim( $r->nro_pago_inscripcion);}
            if( trim( $r->monto_pago_inscripcion )!=''){
            $matricula->monto_pago_inscripcion = trim( $r->monto_pago_inscripcion );}   
        }

        if( trim($r->nro_promocion)!=''){
            $matricula->nro_promocion = trim( $r->nro_promocion);
            $matricula->monto_promocion = trim( $r->monto_promocion);
        }
    
        $matricula->persona_id_created_at=Auth::user()->id;
        $matricula->save();
        
            if(trim($r->pago_nombre_matricula)!=''){
                $este = new Matricula;
                $url_matricula = "upload/m$matricula->id/ma_0.";
                $ruta_matricula = $este->fileToFile($r->pago_archivo_matricula,'m'.$matricula->id, $url_matricula);
                $matricula->archivo_pago=$ruta_matricula;
            }
            if(trim($r->pago_nombre_inscripcion)!=''){
                $este = new Matricula;
                $url_inscripcion = "upload/m$matricula->id/ins_0.";
                $ruta_inscripcion = $este->fileToFile($r->pago_archivo_inscripcion,'m'.$matricula->id, $url_inscripcion);
                $matricula->archivo_pago_inscripcion=$ruta_inscripcion;
            }
        $matricula->save();
        
        if(Input::has('programacion_id')){
                $programacion_id=$r->programacion_id;
            }
        if(Input::has('especialidad_id')){
                $especialidad_id=$r->especialidad_id;
            }
        
        $nro_pago=$r->nro_pago;
        $monto_pago=$r->monto_pago;
        $nro_pago_certificado=$r->nro_pago_certificado;
        $monto_pago_certificado=$r->monto_pago_certificado;
        $pago_archivo=$r->pago_archivo;
        $pago_nombre=$r->pago_nombre;
        $pago_archivo_certificado=$r->pago_archivo_certificado;
        $pago_nombre_certificado=$r->pago_nombre_certificado;
        $checks= $r->checks;
        
        if($matricula){
            for($i=0;$i<count($nro_pago_certificado);$i++){
                
                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->matricula_id=$matricula->id;
                if(Input::has('programacion_id')){
                    $mtdetalle->programacion_id=$programacion_id[$i]; 
                    if(Input::has('seminario')){
                        $mtdetalle->tipo_matricula_detalle=4;
                    }else{
                        if($nro_pago_certificado[$i]==0 or $monto_pago_certificado[$i]==0){
                            $mtdetalle->tipo_matricula_detalle=1;
                        }else{
                            $mtdetalle->tipo_matricula_detalle=3;
                        }
                    }
                }
                if(Input::has('especialidad_id')){
                        $mtdetalle->especialidad_id=$especialidad_id[$i];
                        $mtdetalle->tipo_matricula_detalle=2;
                }

                foreach ($checks as $key => $value) {
                    if($value==$i){
                        $mtdetalle->gratis=1;
                    }
                }
                $mtdetalle->nro_pago=$nro_pago[$i];
                $mtdetalle->monto_pago=$monto_pago[$i];
                $mtdetalle->nro_pago_certificado=$nro_pago_certificado[$i];
                $mtdetalle->monto_pago_certificado=$monto_pago_certificado[$i];
                
                $este = new Matricula;
                    if(trim($pago_nombre[$i])!=''){
                        $url_curso = "upload/m$matricula->id/cu_".$i.'.';
                        $ruta_curso = $este->fileToFile($pago_archivo[$i],'m'.$matricula->id, $url_curso);
                        $mtdetalle->archivo_pago=$ruta_curso;
                    }
                    
                    if(trim($pago_nombre_certificado[$i])!=''){
                        $url_certificado = "upload/m$matricula->id/ce_".$i.'.';
                        $ruta_certificado = $este->fileToFile($pago_archivo_certificado[$i],'m'.$matricula->id, $url_certificado);
                        $mtdetalle->archivo_pago_certificado=$ruta_certificado;
                    }
                $mtdetalle->persona_id_created_at=Auth::user()->id;
                $mtdetalle->save();
            }

        }
        DB::commit();
    }
    
        public function fileToFile($file,$id, $url){
        if ( !is_dir('upload') ) {
            mkdir('upload',0777);
        }
        if ( !is_dir('upload/'.$id) ) {
            mkdir('upload/'.$id,0777);
        }
        list($type, $file) = explode(';', $file);
        list(, $type) = explode('/', $type);
        if ($type=='jpeg') $type='jpg';
        if (strpos($type,'document')!==False) $type='docx';
        if (strpos($type, 'sheet') !== False) $type='xlsx';
        if ($type=='plain') $type='txt';
        list(, $file)      = explode(',', $file);
        $file = base64_decode($file);
        file_put_contents($url.$type , $file);
        return $url. $type;
    }
        
}
