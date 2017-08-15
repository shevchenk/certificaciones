<?php

namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\MatriculaDetalle;

class Matricula extends Model
{
    protected   $table = 'mat_matriculas';

    public static function runNew($r)
    {
        /******Validar si alumno existe ***/
        $alumno=Alumno::where('persona_id','=',$r->persona_id)->first();
        
        if($alumno){
           $al= Alumno::find($alumno->id);
           $al->region_id=trim( $r->region_id);
           $al->provincia_id=trim( $r->provincia_id);
           $al->distrito_id=trim( $r->distrito_id);
           $al->save();
        }else {
           $al= new Alumno;
           $al->persona_id=trim( $r->persona_id);
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim('');
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
        $matricula->persona_matricula_id = trim( $r->responsable_id );
        if( trim( $r->persona_marketing_id )!=''){
        $matricula->persona_marketing_id = trim( $r->marketing_id );}
        $matricula->fecha_matricula = trim( $r->fecha );
        $matricula->tipo_matricula = trim( $r->tipo_participante_id );
        $matricula->nro_pago = trim( 1 );
        $matricula->monto_pago = trim( 1 );
        $matricula->persona_id_created_at=Auth::user()->id;
        $matricula->save();
        
        $programacion_id=$r->programacion_id;
        $nro_pago=$r->nro_pago;
        $monto_pago=$r->monto_pago;
        $nro_pago_certificado=$r->nro_pago_certificado;
        $monto_pago_certificado=$r->monto_pago_certificado;
        $pago_archivo=$r->pago_archivo;
        $pago_nombre=$r->pago_nombre;
        $pago_archivo_certificado=$r->pago_archivo_certificado;
        $pago_nombre_certificado=$r->pago_nombre_certificado;
        
        if($matricula){
            for($i=0;$i<count($programacion_id);$i++){
                $este = new Matricula;
                $url = "upload/$matricula->id/pago".$i.'.';
                $ruta_archivo = $este->fileToFile($pago_archivo[$i], $matricula->id, $url);
                
                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->matricula_id=$matricula->id;
                $mtdetalle->programacion_id=$programacion_id[$i];
                $mtdetalle->nro_pago=$nro_pago[$i];
                $mtdetalle->monto_pago=$monto_pago[$i];
                $mtdetalle->nro_pago_certificado=$nro_pago_certificado[$i];
                $mtdetalle->monto_pago_certificado=$monto_pago_certificado[$i];
                $mtdetalle->archivo_pago=$ruta_archivo;
                $mtdetalle->persona_id_created_at=Auth::user()->id;
                $mtdetalle->save();
            }
//            
//            if (Input::has('nro_pagos') && Input::get('nro_pagos')>0) {
//                    $alumnoProbPagos=[];
//                    $file = Input::get('pago_archivo');
//                    for ($i=0; $i < Input::get('nro_pagos'); $i++) {
//                        
//                        $url = "upload/$problema->id/pago".$i.'.';
//                        $ruta_archivo = $this->fileToFile($file[$i], $problema->id, $url);
//                        $alumnoProbPago =new AlumnoProblemaPago( [
//                            'fecha' => Input::get('tp_fecha')[$i],
//                            'recibo' => Input::get('tp_recibo')[$i],
//                            'monto' => Input::get('tp_monto')[$i],
//                            'ruta_archivo' => $ruta_archivo,
//                            'usuario_created_at'=>$id,
//                            'alumno_problema_id'=>$alumnoProblema->id
//                        ]);
//                        array_push($alumnoProbPagos, $alumnoProbPago);
//                    }
//                    $alumnoProblema->alumnoProbPagos()->saveMany($alumnoProbPagos);
//                }

        }
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
