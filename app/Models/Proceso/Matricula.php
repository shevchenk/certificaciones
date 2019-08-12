<?php

namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\MatriculaDetalle;
use Illuminate\Support\Facades\Input;
use App\Models\Mantenimiento\Menu;
use App\Models\Proceso\MatriculaCuota;
use DB;
use Mail;

class Matricula extends Model
{
    protected   $table = 'mat_matriculas';

    public static function runNew($r)
    {
        /******Validar si alumno existe ***/
        $alumno=Alumno::where('persona_id','=',$r->persona_id)->first();

        DB::beginTransaction();
        $extension='';
        if($alumno){
           $al= Alumno::find($alumno->id);
           if( trim($r->region_id)!='' and trim($r->region_id)!='0' ){
               $al->region_id=trim( $r->region_id);
               $al->provincia_id=trim( $r->provincia_id);
               $al->distrito_id=trim( $r->distrito_id);
           }
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           $al->codigo_interno=trim($r->codigo_interno);
           $al->persona_id_updated_at=Auth::user()->id;
           $al->save();
        }else {
           $al= new Alumno;
           $al->persona_id=trim( $r->persona_id);
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           if( trim($r->region_id)!='' and trim($r->region_id)!='0' ){
               $al->region_id=trim( $r->region_id);
               $al->provincia_id=trim( $r->provincia_id);
               $al->distrito_id=trim( $r->distrito_id);
           }
           $al->codigo_interno=trim($r->codigo_interno);
           $al->persona_id_created_at=Auth::user()->id;
           $al->save();
        }

        $matricula = new Matricula;
        $matricula->alumno_id = trim($al->id);
        $matricula->tipo_participante_id = trim($r->tipo_participante_id);
        $matricula->persona_id = trim( $r->persona_id);
        $matricula->sucursal_id = trim( $r->sucursal_id);
        $matricula->sucursal_destino_id = trim( $r->sucursal_destino_id);
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
            $matricula->tipo_pago = trim( $r->tipo_pago);
        }
    
        $matricula->persona_id_created_at=Auth::user()->id;
        $matricula->observacion=$r->observacion;
        $matricula->save();
            
            if( trim($r->pago_nombre_matricula)!='' ){
                $type=explode(".",$r->pago_nombre_matricula);
                $extension=".".$type[1];
            }
            $url = "upload/m$matricula->id/ma_0".$extension; 
            if( trim($r->pago_archivo_matricula)!='' ){
                $matricula->archivo_pago=$url;
                Menu::fileToFile($r->pago_archivo_matricula, $url);
            }

            if( trim($r->pago_nombre_inscripcion)!='' ){
                $type=explode(".",$r->pago_nombre_inscripcion);
                $extension=".".$type[1];
            }
            $url = "upload/m$matricula->id/ins_0".$extension; 
            if( trim($r->pago_archivo_inscripcion)!='' ){
                $matricula->archivo_pago_inscripcion=$url;
                Menu::fileToFile($r->pago_archivo_inscripcion, $url);
            }

            if( trim($r->nro_promocion)!=''){
                if( trim($r->pago_nombre_promocion)!='' ){
                    $type=explode(".",$r->pago_nombre_promocion);
                    $extension=".".$type[1];
                }
                $url = "upload/m$matricula->id/pro_0".$extension; 
                if( trim($r->pago_archivo_promocion)!='' ){
                    $matricula->archivo_promocion=$url;
                    Menu::fileToFile($r->pago_archivo_promocion, $url);
                }
                
                if( trim($r->dni_nombre)!='' ){
                    $type=explode(".",$r->dni_nombre);
                    $extension=".".$type[1];
                }
                $url = "upload/m$matricula->id/pro_dni_0".$extension; 
                if( trim($r->dni_archivo)!='' ){
                    $matricula->archivo_dni=$url;
                    Menu::fileToFile($r->dni_archivo, $url);
                }
            }

            if( Input::has('especialidad_programacion_id') ){
                $especialidad_programacion_id= $r->especialidad_programacion_id;
                $matricula->especialidad_programacion_id= $especialidad_programacion_id[0];
            }

            $matricula->save();
        
        if(Input::has('programacion_id')){
                $programacion_id=$r->programacion_id;
            }
        if(Input::has('especialidad_id')){
                $especialidad_id=$r->especialidad_id;
            }
        if(Input::has('curso_id')){
                $curso_id=$r->curso_id;
            }
        
        $nro_pago=$r->nro_pago;
        $monto_pago=$r->monto_pago;
        $nro_pago_certificado=$r->nro_pago_certificado;
        $monto_pago_certificado=$r->monto_pago_certificado;
        $pago_archivo=$r->pago_archivo;
        $pago_nombre=$r->pago_nombre;
        $pago_archivo_certificado=$r->pago_archivo_certificado;
        $pago_nombre_certificado=$r->pago_nombre_certificado;
        $tipo_pago=$r->tipo_pago_detalle;
        $dni_nombre_detalle=$r->dni_nombre_detalle;
        $dni_archivo_detalle=$r->dni_archivo_detalle;
        $checks= $r->checks;
        /*if(count($checks)==0){
            $checks=array();
        }*/
            
        if($matricula){
            for($i=0;$i<count($curso_id);$i++){
                
                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->norden=$i+1;
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

                /*foreach ($checks as $key => $value) {
                    if($value==$i){
                        $mtdetalle->gratis=1;
                    }
                }*/
                $mtdetalle->nro_pago=$nro_pago[$i];
                $mtdetalle->monto_pago=$monto_pago[$i];
                $mtdetalle->nro_pago_certificado=$nro_pago_certificado[$i];
                $mtdetalle->monto_pago_certificado=$monto_pago_certificado[$i];
                $mtdetalle->tipo_pago=$tipo_pago[$i];
                $mtdetalle->curso_id=$curso_id[$i];

                if(Input::has('especialidad_id') AND !$r->has('nro_certificado')){
                        $mtdetalle->especialidad_id=$especialidad_id[$i];
                        $mtdetalle->tipo_matricula_detalle=2;
                        $mtdetalle->nro_pago_certificado=0;
                        $mtdetalle->monto_pago_certificado=0;
                        $mtdetalle->tipo_pago=0;
                }
                elseif( Input::has('especialidad_id') AND $r->has('nro_certificado') ){
                    if( $r->nro_certificado[$i]!='' AND $r->monto_certificado[$i]!='' ){
                        $mtdetalle->especialidad_id=$especialidad_id[$i];
                        $mtdetalle->tipo_matricula_detalle=5;
                        $mtdetalle->nro_pago_certificado=$r->nro_certificado[$i];
                        $mtdetalle->monto_pago_certificado=$r->monto_certificado[$i];
                        $mtdetalle->tipo_pago=$r->tipo_pago_certificado[$i];
                    }
                    else{
                        $mtdetalle->especialidad_id=$especialidad_id[$i];
                        $mtdetalle->tipo_matricula_detalle=5;
                        $mtdetalle->nro_pago_certificado=0;
                        $mtdetalle->monto_pago_certificado=0;
                        $mtdetalle->tipo_pago=0;
                    }

                    if( trim($pago_nombre_certificado[$i])!='' ){
                        $type=explode(".",$pago_nombre_certificado[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_".$i.$extension; 
                    if( trim($pago_archivo_certificado[$i])!='' ){
                        $mtdetalle->archivo_pago_certificado=$url;
                        Menu::fileToFile($pago_archivo_certificado[$i], $url);
                    }

                    $mtdetalle->archivo_dni=$matricula->archivo_dni;
                }
                
                if( trim($r->nro_promocion)==''){
                    if( trim($pago_nombre[$i])!='' ){
                        $type=explode(".",$pago_nombre[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cur_".$i.$extension; 
                    if( trim($pago_archivo[$i])!='' ){
                        $mtdetalle->archivo_pago=$url;
                        Menu::fileToFile($pago_archivo[$i], $url);
                    }

                    if( trim($pago_nombre_certificado[$i])!='' ){
                        $type=explode(".",$pago_nombre_certificado[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_".$i.$extension; 
                    if( trim($pago_archivo_certificado[$i])!='' ){
                        $mtdetalle->archivo_pago_certificado=$url;
                        Menu::fileToFile($pago_archivo_certificado[$i], $url);
                    }

                    if( trim($dni_nombre_detalle[$i])!='' ){
                        $type=explode(".",$dni_nombre_detalle[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_dni_".$i.$extension; 
                    if( trim($dni_archivo_detalle[$i])!='' ){
                        $mtdetalle->archivo_dni=$url;
                        Menu::fileToFile($dni_archivo_detalle[$i], $url);
                    }
                }
                
                $mtdetalle->persona_id_created_at=Auth::user()->id;
                $mtdetalle->save();
            }
        }

        if( Input::has('cuota') ){
            $cuota= $r->cuota;
            $nro_cuota= $r->nro_cuota;
            $monto_cuota= $r->monto_cuota;
            $tipo_pago_cuota= $r->tipo_pago_cuota;
            $pago_nombre_cuota= $r->pago_nombre_cuota;
            $pago_archivo_cuota= $r->pago_archivo_cuota;

            for ($i=0; $i < count($cuota) ; $i++) { 
                if( trim($monto_cuota[$i])!='' ){
                    $matriculaCuotas= new MatriculaCuota;
                    $matriculaCuotas->matricula_id= $matricula->id;
                    $matriculaCuotas->cuota= $cuota[$i];
                    $matriculaCuotas->nro_cuota= $nro_cuota[$i];
                    $matriculaCuotas->monto_cuota= $monto_cuota[$i];
                    $matriculaCuotas->tipo_pago_cuota= $tipo_pago_cuota[$i];
                    if( trim($pago_nombre_cuota[$i])!='' ){
                        $type=explode(".",$pago_nombre_cuota[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cuotas/C".$cuota[$i].$extension; 
                    if( trim($pago_archivo_cuota[$i])!='' ){
                        $matriculaCuotas->archivo_cuota= $url;
                        Menu::fileToFile($pago_archivo_cuota[$i], $url);
                    }
                    $matriculaCuotas->persona_id_created_at=Auth::user()->id;
                    $matriculaCuotas->save();
                }
            }
        }
        DB::commit();
        $email='jorgeshevchenk@gmail.com';
        $emailseguimiento='jorgeshevchenk1988@gmail.com';
        $texto='.::Inscripción de Seminarios::.';
        $parametros=array(
            'id'=>'123',
        );
        /*Mail::send('email.matricula', $parametros , 
            function($message) use( $email,$emailseguimiento,$texto ) {
                $message
                ->to($email)
                ->cc($emailseguimiento)
                ->subject($texto);
            }
        );*/
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
