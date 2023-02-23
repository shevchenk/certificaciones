<?php

namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Mantenimiento\EspecialidadProgramacion;
use App\Models\Mantenimiento\Menu;
use App\Models\Mantenimiento\Persona;
use App\Models\Mantenimiento\Programacion;
use App\Models\Mantenimiento\Trabajador;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\ApiPro;
use App\Models\Proceso\MatriculaCuota;
use App\Models\Proceso\MatriculaDetalle;
use App\Models\Proceso\MatriculaSaldo;
use App\Models\Reporte\Seminario;
use App\Mail\EmailSend;
use Illuminate\Support\Facades\Input;
use DB;
use Mail;

class Matricula extends Model
{
    protected   $table = 'mat_matriculas';

    public static function runNew($r)
    {
        /******Validar si alumno existe ***/
        ini_set('memory_limit', '1024M');
        set_time_limit(600);
        $alumno=Alumno::where('persona_id','=',$r->persona_id)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->first();

        DB::beginTransaction();
        $extension='';
        $codini='';
        if( Auth::user()->empresa_id==3 ){
            $codini='EI112';
        }
        if($alumno){
           $al= Alumno::find($alumno->id);
           if( trim($r->region_id)!='' and trim($r->region_id)!='0' ){
               $al->region_id=trim( $r->region_id);
               $al->provincia_id=trim( $r->provincia_id);
               $al->distrito_id=trim( $r->distrito_id);
           }
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           $al->codigo_interno=$codini.trim($r->codigo_interno);
           $al->persona_id_updated_at=Auth::user()->id;
           $al->save();
        }else {
           $al= new Alumno;
           $al->empresa_id=Auth::user()->empresa_id;
           $al->persona_id=trim( $r->persona_id);
           $al->direccion=trim( $r->direccion);
           $al->referencia=trim($r->referencia);
           if( trim($r->region_id)!='' and trim($r->region_id)!='0' ){
               $al->region_id=trim( $r->region_id);
               $al->provincia_id=trim( $r->provincia_id);
               $al->distrito_id=trim( $r->distrito_id);
           }
           $al->codigo_interno=$codini.trim($r->codigo_interno);
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
        $trabajador = Trabajador::find($r->persona_caja_id);
        $matricula->persona_caja_id = $trabajador->persona_id;}
        if( trim( $r->responsable_id )!=''){
        $matricula->persona_matricula_id = trim( $r->responsable_id );}
        if( trim( $r->medio_captacion_id )!=''){
            $matricula->medio_captacion_id = trim( $r->medio_captacion_id );}
        if( trim( $r->medio_captacion_id2 )!=''){
            $matricula->medio_captacion_id2 = trim( $r->medio_captacion_id2 );}
        if( trim( $r->marketing_id )!=''){
        $trabajador = Trabajador::find($r->marketing_id);
        $matricula->persona_marketing_id = $trabajador->persona_id;}
        //$matricula->fecha_matricula = trim( $r->fecha );
        //
        $matricula->fecha_matricula = date("Y-m-d");
        $matricula->tipo_matricula = trim( $r->tipo_matricula );

        if( trim( $r->nro_pago_matricula )!=''){
            $matricula->nro_pago = trim( $r->nro_pago_matricula);
            $matricula->monto_pago = trim( $r->monto_pago_matricula );
            $matricula->tipo_pago_matricula = trim( $r->tipo_pago_matricula );
        }

        if( trim( $r->nro_pago_inscripcion )!=''){
            $matricula->nro_pago_inscripcion = trim( $r->nro_pago_inscripcion);
            $matricula->monto_pago_inscripcion = trim( $r->monto_pago_inscripcion );
            $matricula->tipo_pago_inscripcion = trim( $r->tipo_pago_inscripcion );
        }

        if( trim($r->nro_promocion)!=''){
            $matricula->nro_promocion = trim( $r->nro_promocion);
            $matricula->monto_promocion = trim( $r->monto_promocion);
            $matricula->tipo_pago = trim( $r->tipo_pago);
        }
    
        $matricula->persona_id_created_at=Auth::user()->id;
        $matricula->observacion=$r->observacion;
        $matricula->llamada_id=$r->llamada_id;
        $matricula->fecha_estado= date("Y-m-d");
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
            }

            if( $r->has('dni_nombre') ){
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

            if( Input::has('especialidad_programacion_id2') ){
                $especialidad_programacion_id= $r->especialidad_programacion_id2;
                $matricula->especialidad_programacion_id= $especialidad_programacion_id;
            }

            if( Input::has('especialidad_programacion_id') OR Input::has('especialidad_programacion_id2') ){
                /*********************** Se Agrega Saldos Inscripción ******************/
                $programacionVal= DB::table('mat_especialidades_programaciones')
                                  ->find($matricula->especialidad_programacion_id);
                $matricula->adicional = $programacionVal->adicional;
                if( trim( $r->nro_pago_inscripcion )!='' ){
                    $monto_precio= $programacionVal->costo*1;
                    $monto_saldo= $programacionVal->costo*1 - $r->monto_pago_inscripcion;
                    if($monto_saldo<0){
                        $monto_saldo=0;
                    }
                    if( $monto_saldo>0 ){
                        $mtsaldo= new MatriculaSaldo;
                        $mtsaldo->matricula_id= $matricula->id;
                        $mtsaldo->nro_pago= $matricula->nro_pago_inscripcion;
                        $mtsaldo->archivo= $matricula->archivo_pago_inscripcion;
                        $mtsaldo->saldo= $monto_saldo;
                        $mtsaldo->precio= $monto_precio;
                        $mtsaldo->pago= $r->monto_pago_inscripcion;
                        $mtsaldo->tipo_pago= $r->tipo_pago_inscripcion;
                        $mtsaldo->persona_caja_id = $matricula->persona_caja_id;
                        $mtsaldo->persona_id_created_at= Auth::user()->id;
                        $mtsaldo->save();
                    }
                }
                /****************************************************************/
                /*********************** Se Agrega Saldos Matrícula ******************/
                if( trim( $r->nro_pago_matricula )!='' ){
                    $monto_precio= $programacionVal->costo_mat*1;
                    $monto_saldo= $programacionVal->costo_mat*1 - $r->monto_pago_matricula;
                    if($monto_saldo<0){
                        $monto_saldo=0;
                    }
                    if( $monto_saldo>0 ){
                        $mtsaldo= new MatriculaSaldo;
                        $mtsaldo->matricula_id= $matricula->id;
                        $mtsaldo->nro_pago= $matricula->nro_pago;
                        $mtsaldo->archivo= $matricula->archivo_pago;
                        $mtsaldo->cuota= 0;
                        $mtsaldo->saldo= $monto_saldo;
                        $mtsaldo->precio= $monto_precio;
                        $mtsaldo->pago= $r->monto_pago_matricula;
                        $mtsaldo->tipo_pago= $r->tipo_pago_matricula;
                        $mtsaldo->persona_caja_id = $matricula->persona_caja_id;
                        $mtsaldo->persona_id_created_at= Auth::user()->id;
                        $mtsaldo->save();
                    }
                }
                /****************************************************************/
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
                $monto_saldo=0;
                $monto_precio=-1;
                $mtdetalle=new MatriculaDetalle;
                $mtdetalle->norden=$i+1;
                $mtdetalle->matricula_id=$matricula->id;

                if( !isset($nro_pago_certificado[$i]) ){
                    $nro_pago_certificado[$i] = 0;
                    $monto_pago_certificado[$i] = 0;
                }
                if( !isset($tipo_pago[$i]) ){
                    $tipo_pago[$i] = 0;
                }
                if(Input::has('programacion_id')){
                    $mtdetalle->programacion_id=$programacion_id[$i]; 

                    if( trim($r->nro_promocion)=='' AND !Input::has('especialidad_programacion_id') ){
                        $programacionVal= Programacion::find($programacion_id[$i]);
                        $monto_precio= $programacionVal->costo;
                        $monto_saldo= $programacionVal->costo - $monto_pago_certificado[$i];
                        if($monto_saldo<0){
                            $monto_saldo=0;
                        }

                        if( $monto_pago_certificado[$i]*1==0 ){
                            $mtdetalle->gratis = 1;
                        }
                    }

                    $mtdetalle->saldo=$monto_saldo;

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
                if( !isset($nro_pago[$i]) ){
                    $nro_pago[$i] = 0;
                    $monto_pago[$i] = 0;
                }
                $mtdetalle->nro_pago=$nro_pago[$i];
                $mtdetalle->monto_pago=$monto_pago[$i];
                $mtdetalle->nro_pago_certificado=$nro_pago_certificado[$i];
                $mtdetalle->monto_pago_certificado=$monto_pago_certificado[$i];
                $mtdetalle->tipo_pago=$tipo_pago[$i];
                $mtdetalle->curso_id=$curso_id[$i];

                if( Input::has('especialidad2_id') ){
                    $mtdetalle->especialidad_id=$r->especialidad2_id;
                }

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

                    if( isset($pago_nombre_certificado[$i]) AND trim($pago_nombre_certificado[$i])!='' ){
                        $type=explode(".",$pago_nombre_certificado[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_".$i.$extension; 
                    if( isset($pago_archivo_certificado[$i]) AND trim($pago_archivo_certificado[$i])!='' ){
                        $mtdetalle->archivo_pago_certificado=$url;
                        Menu::fileToFile($pago_archivo_certificado[$i], $url);
                    }

                    $mtdetalle->archivo_dni=$matricula->archivo_dni;
                }
                
                if( trim($r->nro_promocion)==''){
                    if( isset($pago_nombre[$i]) AND trim($pago_nombre[$i])!='' ){
                        $type=explode(".",$pago_nombre[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cur_".$i.$extension; 
                    if( isset( $pago_archivo[$i] ) AND trim($pago_archivo[$i])!='' ){
                        $mtdetalle->archivo_pago=$url;
                        Menu::fileToFile($pago_archivo[$i], $url);
                    }

                    if( isset( $pago_nombre_certificado[$i] ) AND trim($pago_nombre_certificado[$i])!='' ){
                        $type=explode(".",$pago_nombre_certificado[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_".$i.$extension; 
                    if( isset( $pago_archivo_certificado[$i] ) AND trim($pago_archivo_certificado[$i])!='' ){
                        $mtdetalle->archivo_pago_certificado=$url;
                        Menu::fileToFile($pago_archivo_certificado[$i], $url);
                    }

                    if( isset( $dni_nombre_detalle[$i] ) AND trim($dni_nombre_detalle[$i])!='' ){
                        $type=explode(".",$dni_nombre_detalle[$i]);
                        $extension=".".$type[1];
                    }
                    $url = "upload/m$matricula->id/cer_dni_".$i.$extension; 
                    if( isset( $dni_archivo_detalle[$i] ) AND trim($dni_archivo_detalle[$i])!='' ){
                        $mtdetalle->archivo_dni=$url;
                        Menu::fileToFile($dni_archivo_detalle[$i], $url);
                    }
                }
                
                $mtdetalle->persona_id_created_at=Auth::user()->id;
                $mtdetalle->save();

                if( trim($r->nro_promocion)=='' AND $monto_saldo>0){
                    $mtsaldo= new MatriculaSaldo;
                    $mtsaldo->matricula_detalle_id= $mtdetalle->id;
                    $mtsaldo->nro_pago= $mtdetalle->nro_pago_certificado;
                    $mtsaldo->archivo= $mtdetalle->archivo_pago_certificado;
                    $mtsaldo->saldo= $monto_saldo;
                    $mtsaldo->precio= $monto_precio;
                    $mtsaldo->pago= $monto_pago_certificado[$i];
                    $mtsaldo->tipo_pago= $tipo_pago[$i];
                    $mtsaldo->persona_caja_id = $matricula->persona_caja_id;
                    $mtsaldo->persona_id_created_at= Auth::user()->id;
                    $mtsaldo->save();
                }
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
                    $matriculaCuotas->persona_caja_id=$matricula->persona_caja_id;
                    $matriculaCuotas->save();
                    /*********************** Se Agrega Saldos ******************/
                    $programacionVal= DB::table('mat_especialidades_programaciones_cronogramas')
                                      ->where('cuota',$cuota[$i])
                                      ->where('especialidad_programacion_id',$matricula->especialidad_programacion_id)
                                      ->where('estado',1)
                                      ->first();
                    $monto_precio= $programacionVal->monto_cronograma*1;
                    $monto_saldo= $programacionVal->monto_cronograma*1 - $monto_cuota[$i];
                    if($monto_saldo<0){
                        $monto_saldo=0;
                    }

                    if( $monto_saldo>0 ){
                        $mtsaldo= new MatriculaSaldo;
                        $mtsaldo->matricula_id= $matricula->id;
                        $mtsaldo->nro_pago= $nro_cuota[$i];
                        $mtsaldo->archivo= $matriculaCuotas->archivo_cuota;
                        $mtsaldo->cuota= $cuota[$i];
                        $mtsaldo->saldo= $monto_saldo;
                        $mtsaldo->precio= $monto_precio;
                        $mtsaldo->pago= $monto_cuota[$i];
                        $mtsaldo->tipo_pago= $tipo_pago_cuota[$i];
                        $mtsaldo->persona_caja_id = $matricula->persona_caja_id;
                        $mtsaldo->persona_id_created_at= Auth::user()->id;
                        $mtsaldo->save();
                    }
                }
            }
        }

        $persona= DB::table('personas')->where('id',$r->persona_id)->first();
        $cursos=    DB::table('mat_matriculas AS m')
                    ->join('mat_matriculas_detalles AS md',function($join){
                        $join->on('md.matricula_id','=','m.id')
                        ->where('md.estado',1);
                    })
                    ->join('mat_cursos AS c',function($join){
                        $join->on('c.id','=','md.curso_id');
                    })
                    ->join('empresas AS e',function($join){
                        $join->on('e.id','=','c.empresa_id');
                    })
                    ->leftJoin('mat_especialidades AS me',function($join){
                        $join->on('me.id','=','md.especialidad_id');
                    })
                    ->select('c.curso','c.tipo_curso','e.empresa','c.empresa_id','me.especialidad')
                    ->where('m.id',$matricula->id)
                    ->get();
        $usuario= Auth::user()->id;

        $privilegio =DB::table('personas_privilegios_sucursales')
        ->where('privilegio_id',14)
        ->where('sucursal_id',1)
        ->where('persona_id',$persona->id)
        ->first();

        if( !isset($privilegio->id) ){
            DB::table('personas_privilegios_sucursales')->insert(
                array(
                    'privilegio_id' => 14,
                    'sucursal_id' => 1,
                    'persona_id' => $persona->id,
                    'created_at'=> date('Y-m-d H:i:s'),
                    'persona_id_created_at'=> $usuario,
                    'estado' => 1,
                    'persona_id_updated_at' => $usuario
                )
            );
        }
        else{
            DB::table('personas_privilegios_sucursales')
            ->where('persona_id',$persona->id)
            ->where('privilegio_id',14)
            ->where('sucursal_id',1)
            ->update(
                array(
                    'estado' => 1
                )
            );
        }

        DB::commit();

        $email=trim($persona->email);
        $emailseguimiento='jorgeshevchenk1988@gmail.com';
        $parametros=array(
            'email'=>$email,
            'emailseguimiento'=>$emailseguimiento,
            'persona'=>$persona,
            'cursos'=>$cursos,
            'matricula_id'=>$matricula->id,
            'subject'=>'.::Bienvenido, inscripción realizada con éxito::.',
            'blade' => 'emails.inscripcion.inscripcion'.$cursos[0]->empresa_id,
        );

        if( session('validar_email')==1 
            AND $email!=''
            AND (
                $cursos[0]->empresa_id==4
                OR $cursos[0]->empresa_id==1
                OR $cursos[0]->empresa_id==3
            )
        ){
            
            Mail::send($parametros['blade'],$parametros, 
                function($message) use($parametros){
                    $message->to($parametros['email']);
                    $message->bcc([$parametros['emailseguimiento']]);
                    $message->subject($parametros['subject']);
                }
            );
        }

        return $matricula->id;
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
    
    public static function ValidarMatricula($r)
    {
        $matricula_id= $r->matricula_id;
        $matricula= Matricula::find($matricula_id);
        return $matricula->validada;
    }

    public static function BandejaValida($r)
    {
        $id=Auth::user()->id;
        $set=DB::statement('SET group_concat_max_len := @@max_allowed_packet');
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_especialidades_programaciones AS ep',function($join){
                $join->on('ep.id','=','mm.especialidad_programacion_id');
            })
            ->join('mat_matriculas_detalles AS mmd',function($join) use($r) {
                $join->on('mmd.matricula_id','=','mm.id');
                if( ($r->has("inactivos") AND $r->inactivos == 1) OR ($r->has("estado_mat") AND ($r->estado_mat == 'Anulado' OR $r->estado_mat == 'Rechazado')) ){
                    $join->where('mmd.estado',0);
                }
                else{
                    $join->where('mmd.estado',1);
                }
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mm.sucursal_destino_id');
            })
            ->join('mat_tipos_participantes AS mtp',function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('empresas AS e',function($join){
                $join->on('e.id','=','mc.empresa_id');
            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');
            })
            ->leftJoin('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('sucursales AS s3',function($join){
                $join->on('s3.id','=','mp.sucursal_id');
            })
            ->leftJoin('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');
            })
            ->leftJoin('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');
            })
            ->leftJoin('personas AS psup',function($join){
                $join->on('psup.id','=','mm.persona_id_updated_at');
            })
            ->leftJoin('mat_medios_captaciones AS meca',function($join){
                $join->on('meca.id','=','mm.medio_captacion_id');
            })
            ->leftJoin('mat_medios_captaciones AS meca2',function($join){
                $join->on('meca2.id','=','mm.medio_captacion_id2');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->leftJoin('bancos AS b',function($join){
                $join->on('b.id','=','mmd.tipo_pago');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'mm.fecha_matricula'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre))) as marketing')
                    ,'mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada','ep.tipo AS tipo_mat'
                    ,DB::raw('GROUP_CONCAT( DISTINCT(s3.sucursal) ) AS lugar_estudio'),'e.empresa AS empresa_inscripcion'
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") ) AS tipo_formacion')
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) ) AS formacion')
                    , DB::raw(' MIN(mm.archivo_pago) as archivo_pago_matricula, MIN(mm.archivo_pago_inscripcion) AS archivo_pago_inscripcion, MIN(mm.archivo_promocion) AS archivo_pago_promocion ')
                    /*,'mc.curso AS curso', 'mp.dia AS frecuencia'
                    , DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) AS horario')
                    , 'mp.turno', DB::raw('DATE(mp.fecha_inicio) AS inicio')*/
                    , DB::raw('GROUP_CONCAT( CONCAT(mc.curso, "|", IFNULL(mp.dia,""), "|", IFNULL(TIME(mp.fecha_inicio),""), " - ", IFNULL(TIME(mp.fecha_final),""), "|", IFNULL(mp.turno,""), "|", IFNULL(DATE(mp.fecha_inicio),""), "|", mmd.id, "|", mmd.especialidad_id, "|", IFNULL(mmd.programacion_id,"")) ORDER BY mmd.id SEPARATOR "^^" ) AS detalle')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id )
                                ) AS nro_pago')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id )
                                ) AS monto_pago')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.archivo_pago_certificado ORDER BY mmd.id )
                                ) AS archivo')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( 
                                    IFNULL(b.banco,"") ORDER BY mmd.id )
                                ) AS tipo_pago')
                    ,DB::raw(' IF(ep.tipo = 1, "", GROUP_CONCAT( mmd.tipo_pago ORDER BY mmd.id ) ) AS tipo_pago_id')
                    ,DB::raw(' IF(ep.tipo = 1, "", GROUP_CONCAT( mmd.id ORDER BY mmd.id ) ) AS matricula_detalle_id')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_pago AS nro_pago_matricula','mm.monto_pago AS monto_pago_matricula'
                    ,DB::raw('(SELECT banco FROM bancos WHERE id = mm.tipo_pago_matricula) AS tipo_pago_matricula')
                    ,'mm.tipo_pago_matricula AS tipo_pago_matricula_id'
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('(SELECT banco FROM bancos WHERE id = mm.tipo_pago) AS tipo_pago_promocion')
                    ,'mm.tipo_pago AS tipo_pago_promocion_id'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('(SELECT banco FROM bancos WHERE id = mm.tipo_pago_inscripcion) AS tipo_pago_inscripcion')
                    ,'mm.tipo_pago_inscripcion AS tipo_pago_inscripcion_id'
                    //,DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total')
                    ,'s.sucursal','s2.sucursal AS recogo_certificado', 'mm.estado_mat', 'mm.fecha_estado', DB::raw('MIN(mm.observacion) AS obs, MIN(mm.observacion_mat) AS obs2')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre))) as cajera')
                    ,'meca.medio_captacion','meca2.medio_captacion AS medio_captacion2'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre))) as matricula')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",psup.paterno,psup.materno,psup.nombre))) as supervisor')
                    )
            ->where( 
                function($query) use ($r, $id){

                    if( $r->has("fecha_matricula") AND trim($r->fecha_matricula) != '' ){
                        $query->where('mm.fecha_matricula', $r->fecha_matricula);
                    }

                    if( $r->has("fecha_estado") AND trim($r->fecha_estado) != '' ){
                        $query->where('mm.fecha_estado', $r->fecha_estado);
                    }

                    if( $r->has("trabajador") AND trim($r->trabajador) != '' ){
                        $query->whereRaw('CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre) LIKE "'.$r->trabajador.'%"');
                    }

                    if( $r->has("alumno") AND trim($r->alumno) != '' ){
                        $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre) LIKE "'.$r->alumno.'%"');
                    }

                    if( $r->has("carrera") AND trim($r->carrera) != '' ){
                        $query->whereRaw('me.especialidad LIKE "%'.$r->carrera.'%"');
                    }

                    if( $r->has("curso") AND trim($r->curso) != '' ){
                        $query->whereRaw('mc.curso LIKE "%'.$r->curso.'%"');
                    }

                    if( $r->has("estado_mat") AND trim($r->estado_mat) != '' ){
                        if( $r->estado_mat == 'JefeVenta' ){
                            $query->where('mm.estado_mat', '!=', 'Pendiente');
                        }
                        elseif( $r->estado_mat == 'Marketing' ){
                            $query->whereNotIn('mm.estado_mat', ['Observado','A Corregir']);
                        }
                        elseif( $r->estado_mat == 'Observado' ){
                            $query->whereIn('mm.estado_mat', ['Observado','A Corregir']);
                        }
                        else {
                            $query->where('mm.estado_mat', $r->estado_mat);
                        }
                    }

                    if( ($r->has("inactivos") AND $r->inactivos == 1) OR ($r->has("estado_mat") AND ($r->estado_mat == 'Anulado' OR $r->estado_mat == 'Rechazado')) ){
                        $query->where('mm.estado',0);
                    }
                    else{
                        $query->where('mm.estado',1);
                    }

                    if( $r->has("fecha_inicial") AND $r->has("fecha_final")){
                        $inicial=trim($r->fecha_inicial);
                        $final=trim($r->fecha_final);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_matricula', array($r->fecha_inicial,$r->fecha_final));
                        }
                    }

                    if( $r->has("fecha_estado_i") AND $r->has("fecha_estado_f")){
                        $inicial=trim($r->fecha_estado_i);
                        $final=trim($r->fecha_estado_f);
                        if( $inicial !=''AND $final!=''){
                            $query ->whereBetween('mm.fecha_estado', array($r->fecha_estado_i,$r->fecha_estado_f));
                        }
                    }

                    if( $r->has('vendedor') AND $r->vendedor==1 ){
                        $persona_id=Auth::user()->id;
                        $query->where('mm.persona_marketing_id',$persona_id);
                    }

                    if( $r->has('matricula_id') ){
                        $query->where('mm.id', $r->matricula_id);
                    }
                    /*else if( !$r->has("fecha_estado_i") AND !$r->has("fecha_estado_f")){
                        $query->whereRaw('FIND_IN_SET( mm.sucursal_id, (SELECT GROUP_CONCAT(DISTINCT(ppv.sucursal_id))
                        FROM personas_privilegios_sucursales ppv
                        WHERE ppv.persona_id='.$id.') ) > 0');
                    }*/
                }
            )
            ->groupBy('mm.id','mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula','e.empresa','ep.tipo'
                    ,'mm.especialidad_programacion_id'
                    ,'s.sucursal','s2.sucursal','mm.nro_promocion','mm.monto_promocion', 'mm.monto_pago', 'mm.nro_pago', 'mm.estado_mat', 'mm.fecha_estado'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion','meca.medio_captacion','meca2.medio_captacion'
                    ,'mm.tipo_pago_matricula');

        $result = array();
        if( $r->has('exportar') ){
            $result = $sql->orderBy('mm.id','asc')->get();
        }
        else{
            $result = $sql->orderBy('mm.id','asc')->paginate(10);

        }
        
        return $result;
    }

    public static function ActualizaMat($r)
    {
        DB::beginTransaction();

        $id=Auth::user()->id;
        
        $matricula= Matricula::find($r->matricula_id);
        $matricula->persona_id_updated_at = $id;

        /* TODO: Inscripción   ************************************************************************************************/
        if( $r->has('nro_pago_inscripcion') AND trim($matricula->nro_pago_inscripcion) != trim($r->nro_pago_inscripcion) ){
            $matricula->nro_pago_inscripcion = trim($r->nro_pago_inscripcion);
        }
        if( $r->has('tipo_pago_inscripcion') AND trim($matricula->tipo_pago_inscripcion) != trim($r->tipo_pago_inscripcion) ){
            $matricula->tipo_pago_inscripcion = trim($r->tipo_pago_inscripcion);
        }
        if( $r->has('monto_pago_inscripcion') AND trim($matricula->monto_pago_inscripcion) != trim($r->monto_pago_inscripcion) ){
            $total = trim($matricula->monto_pago_inscripcion)*1;
            $matricula->monto_pago_inscripcion = trim($r->monto_pago_inscripcion)*1;
            if( $r->has('salsi') AND trim($r->salsi) != '' ){
                $matriculaSaldo = MatriculaSaldo::find($r->salsi_id);
                $total = $matriculaSaldo->precio;
                $saldo = $total - trim($matricula->monto_pago_inscripcion)*1;
                if( $saldo > 0  ){
                    $matriculaSaldo->pago = $matricula->monto_pago_inscripcion;
                    $matriculaSaldo->saldo = $saldo;
                    $matriculaSaldo->tipo_pago = $matricula->tipo_pago_inscripcion;
                    $matriculaSaldo->nro_pago = $matricula->nro_pago_inscripcion;
                }
                else{
                    $matriculaSaldo->estado = 0;
                }
                $matriculaSaldo->persona_id_updated_at = $id;
                $matriculaSaldo->save();
            }
            else{
                $saldo = $total - trim($matricula->monto_pago_inscripcion)*1;
                if( $saldo > 0  ){
                    $matriculaSaldo = new MatriculaSaldo;
                    $matriculaSaldo->matricula_id = $matricula->id;
                    $matriculaSaldo->persona_caja_id = $matricula->persona_caja_id;
                    $matriculaSaldo->cuota = "-1";
                    $matriculaSaldo->precio = $total;
                    $matriculaSaldo->pago = $matricula->monto_pago_inscripcion;
                    $matriculaSaldo->saldo = $saldo;
                    $matriculaSaldo->tipo_pago = $matricula->tipo_pago_inscripcion;
                    $matriculaSaldo->nro_pago = $matricula->nro_pago_inscripcion;
                    $matriculaSaldo->archivo = $matricula->archivo_pago_inscripcion;
                    $matriculaSaldo->estado = 1;
                    $matriculaSaldo->persona_id_created_at = $id;
                    $matriculaSaldo->save();
                }
            }
        }
        /**********************************************************************************************************************/
        /* TODO: Matrícula   **************************************************************************************************/
        if( $r->has('nro_pago_matricula') AND trim($matricula->nro_pago) != trim($r->nro_pago_matricula) ){
            $matricula->nro_pago = trim($r->nro_pago_matricula);
        }
        if( $r->has('tipo_pago_matricula') AND trim($matricula->tipo_pago_matricula) != trim($r->tipo_pago_matricula) ){
            $matricula->tipo_pago_matricula = trim($r->tipo_pago_matricula);
        }
        if( $r->has('monto_pago_matricula') AND trim($matricula->monto_pago) != trim($r->monto_pago_matricula) ){
            $total = trim($matricula->monto_pago)*1;
            $matricula->monto_pago = trim($r->monto_pago_matricula)*1;
            if( $r->has('salsm') AND trim($r->salsm) != '' ){
                $matriculaSaldo = MatriculaSaldo::find($r->salsm_id);
                $total = $matriculaSaldo->precio;
                $saldo = $total - trim($matricula->monto_pago)*1;
                if( $saldo > 0  ){
                    $matriculaSaldo->pago = $matricula->monto_pago;
                    $matriculaSaldo->saldo = $saldo;
                    $matriculaSaldo->tipo_pago = $matricula->tipo_pago_matricula;
                    $matriculaSaldo->nro_pago = $matricula->nro_pago;
                }
                else{
                    $matriculaSaldo->estado = 0;
                }
                $matriculaSaldo->persona_id_updated_at = $id;
                $matriculaSaldo->save();
            }
            else{
                $saldo = $total - trim($matricula->monto_pago)*1;
                if( $saldo > 0  ){
                    $matriculaSaldo = new MatriculaSaldo;
                    $matriculaSaldo->matricula_id = $matricula->id;
                    $matriculaSaldo->persona_caja_id = $matricula->persona_caja_id;
                    $matriculaSaldo->cuota = "0";
                    $matriculaSaldo->precio = $total;
                    $matriculaSaldo->pago = $matricula->monto_pago;
                    $matriculaSaldo->saldo = $saldo;
                    $matriculaSaldo->tipo_pago = $matricula->tipo_pago_matricula;
                    $matriculaSaldo->nro_pago = $matricula->nro_pago;
                    $matriculaSaldo->archivo = $matricula->archivo_pago_matricula;
                    $matriculaSaldo->estado = 1;
                    $matriculaSaldo->persona_id_created_at = $id;
                    $matriculaSaldo->save();
                }
            }
        }
        /**********************************************************************************************************************/
        /* TODO: Promoción   **************************************************************************************************/
        if( $r->has('nro_promocion') AND trim($matricula->nro_promocion) != trim($r->nro_promocion) ){
            $matricula->nro_promocion = trim($r->nro_promocion);
        }
        if( $r->has('tipo_pago_promocion') AND trim($matricula->tipo_pago) != trim($r->tipo_pago_promocion) ){
            $matricula->tipo_pago = trim($r->tipo_pago_promocion);
        }
        if( $r->has('monto_promocion') AND trim($matricula->monto_promocion) != trim($r->monto_promocion) ){
            $matricula->monto_promocion = trim($r->monto_promocion)*1;
        }
        /**********************************************************************************************************************/
        /* TODO: Archivos Ins, Mat y Pro **************************************************************************************/
        if( $r->has('pago_archivo_matricula') AND trim($r->pago_archivo_matricula)!='' ){
            $type=explode(".",$r->pago_nombre_matricula);
            $extension=".".$type[1];
            $url = "upload/m$matricula->id/ma_0".$extension;
            $matricula->archivo_pago=$url;
            Menu::fileToFile($r->pago_archivo_matricula, $url);
        }

        if( $r->has('pago_archivo_inscripcion') AND trim($r->pago_archivo_inscripcion)!='' ){
            $type=explode(".",$r->pago_nombre_inscripcion);
            $extension=".".$type[1];
            $url = "upload/m$matricula->id/ins_0".$extension; 
            $matricula->archivo_pago_inscripcion=$url;
            Menu::fileToFile($r->pago_archivo_inscripcion, $url);
        }

        if( $r->has('pago_archivo_promocion') AND trim($r->pago_archivo_promocion)!='' ){
            $type=explode(".",$r->pago_nombre_promocion);
            $extension=".".$type[1];
            $url = "upload/m$matricula->id/pro_0".$extension; 
            $matricula->archivo_promocion=$url;
            Menu::fileToFile($r->pago_archivo_promocion, $url);
        }
        /**********************************************************************************************************************/
        /* TODO: Cuotas   *****************************************************************************************************/
        if( $r->has('cuota_id') AND is_array($r->cuota_id) ){
            foreach ($r->cuota_id as $key => $value) {
                $matriculaCuotas = MatriculaCuota::find($value);

                if( trim($matriculaCuotas->nro_cuota) != trim($r->nro_cuota[$key]) ){
                    $matriculaCuotas->nro_cuota = trim($r->nro_cuota[$key]);
                }
                if( trim($matriculaCuotas->tipo_pago_cuota) != trim($r->tipo_pago_cuota[$key]) ){
                    $matriculaCuotas->tipo_pago_cuota = trim($r->tipo_pago_cuota[$key]);
                }
                if( trim($matriculaCuotas->monto_cuota) != trim($r->monto_cuota[$key]) ){
                    $total = trim($matriculaCuotas->monto_cuota)*1;
                    $matriculaCuotas->monto_cuota = trim($r->monto_cuota[$key])*1;
                    
                    if( $matriculaCuotas->monto_cuota*1 == 0 ){
                        $matriculaCuotas->estado = 0;
                    }
                    $saldc =    MatriculaSaldo::where('cuota', $matriculaCuotas->cuota)
                                ->where('matricula_id', $matriculaCuotas->matricula_id)
                                ->where('estado', 1)
                                ->where('saldo','>',0)
                                ->orderBy('saldo','asc')
                                ->first();
                    if( isset($saldc->id) ){
                        $matriculaSaldo = MatriculaSaldo::find($saldc->id);
                        $total = $matriculaSaldo->precio;
                        $saldo = $total - trim($matriculaCuotas->monto_cuota)*1;
                        if( $saldo > 0 AND $matriculaCuotas->estado == 1 ){
                            $matriculaSaldo->pago = $matriculaCuotas->monto_cuota;
                            $matriculaSaldo->saldo = $saldo;
                            $matriculaSaldo->tipo_pago = $matriculaCuotas->tipo_pago_cuota;
                            $matriculaSaldo->nro_pago = $matriculaCuotas->nro_cuota;
                        }
                        else{
                            $matriculaSaldo->estado = 0;
                        }
                        $matriculaSaldo->persona_id_updated_at = $id;
                        $matriculaSaldo->save();
                    }
                    elseif( $matriculaCuotas->estado == 1 ){
                        $saldo = $total - trim($matriculaCuotas->monto_cuota)*1;
                        if( $saldo > 0  ){
                            $matriculaSaldo = new MatriculaSaldo;
                            $matriculaSaldo->matricula_id = $matriculaCuotas->matricula_id;
                            $matriculaSaldo->persona_caja_id = $matriculaCuotas->persona_caja_id;
                            $matriculaSaldo->cuota = $matriculaCuotas->cuota;
                            $matriculaSaldo->precio = $total;
                            $matriculaSaldo->pago = $matriculaCuotas->monto_cuota;
                            $matriculaSaldo->saldo = $saldo;
                            $matriculaSaldo->tipo_pago = $matriculaCuotas->tipo_pago_cuota;
                            $matriculaSaldo->nro_pago = $matriculaCuotas->nro_cuota;
                            $matriculaSaldo->archivo = $matriculaCuotas->archivo_cuota;
                            $matriculaSaldo->estado = 1;
                            $matriculaSaldo->persona_id_created_at = $id;
                            $matriculaSaldo->save();
                        }
                    }
                }

                if( $r->has('pago_archivo_cuota') AND trim($r->pago_archivo_cuota[$key])!='' ){
                    $type=explode(".",$r->pago_nombre_cuota[$key]);
                    $extension=".".$type[1];
                    $url = "upload/m$matricula->id/cuotas/C".$matriculaCuotas->cuota.$extension;
                    $matriculaCuotas->archivo_cuota= $url;
                    Menu::fileToFile($r->pago_archivo_cuota[$key], $url);
                }

                $matriculaCuotas->persona_id_updated_at = $id;
                $matriculaCuotas->save();
            }
        }
        /**********************************************************************************************************************/
        /* TODO: Cursos   *****************************************************************************************************/
        if( $r->has('matricula_detalle_id') AND is_array($r->matricula_detalle_id) ){
            foreach ($r->matricula_detalle_id as $key => $value) {
                $matriculaDetalle = MatriculaDetalle::find($value);

                if( trim($matriculaDetalle->nro_pago_certificado) != trim($r->nro_pago_certificado[$key]) ){
                    $matriculaDetalle->nro_pago_certificado = trim($r->nro_pago_certificado[$key]);
                }
                if( trim($matriculaDetalle->tipo_pago) != trim($r->tipo_pago[$key]) ){
                    $matriculaDetalle->tipo_pago = trim($r->tipo_pago[$key]);
                }
                if( trim($matriculaDetalle->monto_pago_certificado) != trim($r->monto_pago_certificado[$key]) ){
                    $total = trim($matriculaDetalle->monto_pago_certificado)*1;
                    $matriculaDetalle->monto_pago_certificado = trim($r->monto_pago_certificado[$key])*1;
                    
                    $saldc =    MatriculaSaldo::where('cuota', '-1')
                                ->where('matricula_detalle_id', $matriculaDetalle->id)
                                ->where('estado', 1)
                                ->where('saldo','>',0)
                                ->orderBy('saldo','asc')
                                ->first();
                    $saldo = 0;
                    if( isset($saldc->id) ){
                        $matriculaSaldo = MatriculaSaldo::find($saldc->id);
                        $total = $matriculaSaldo->precio;
                        $saldo = $total - trim($matriculaDetalle->monto_pago_certificado)*1;
                        if( $saldo > 0 ){
                            $matriculaSaldo->pago = $matriculaDetalle->monto_pago_certificado;
                            $matriculaSaldo->saldo = $saldo;
                            $matriculaSaldo->tipo_pago = $matriculaDetalle->tipo_pago;
                            $matriculaSaldo->nro_pago = $matriculaDetalle->nro_pago_certificado;
                        }
                        else{
                            $matriculaSaldo->estado = 0;
                        }
                        $matriculaSaldo->persona_id_updated_at = $id;
                        $matriculaSaldo->save();
                    }
                    else{
                        $saldo = $total - trim($matriculaDetalle->monto_pago_certificado)*1;
                        if( $saldo > 0  ){
                            $matriculaSaldo = new MatriculaSaldo;
                            $matriculaSaldo->matricula_detalle_id = $matriculaDetalle->id;
                            $matriculaSaldo->persona_caja_id = $matricula->persona_caja_id;
                            $matriculaSaldo->cuota = '-1';
                            $matriculaSaldo->precio = $total;
                            $matriculaSaldo->pago = $matriculaDetalle->monto_pago_certificado;
                            $matriculaSaldo->saldo = $saldo;
                            $matriculaSaldo->tipo_pago = $matriculaDetalle->tipo_pago;
                            $matriculaSaldo->nro_pago = $matriculaDetalle->nro_pago_certificado;
                            $matriculaSaldo->archivo = $matriculaDetalle->archivo_pago_certificado;
                            $matriculaSaldo->estado = 1;
                            $matriculaSaldo->persona_id_created_at = $id;
                            $matriculaSaldo->save();
                        }
                    }

                    if( $saldo < 0 ){
                        $saldo = 0;
                    }
                    $matriculaDetalle->saldo = $saldo;
                }

                if( $r->has('pago_archivo_certificado') AND trim($r->pago_archivo_certificado[$key])!='' ){
                    $type=explode(".",$r->pago_nombre_certificado[$key]);
                    $extension=".".$type[1];
                    $url = "upload/m$matricula->id/cer_".$matriculaDetalle->id.$extension;
                    $matriculaDetalle->archivo_pago_certificado=$url;
                    Menu::fileToFile($r->pago_archivo_certificado[$key], $url);
                }

                $matriculaDetalle->persona_id_updated_at = $id;
                $matriculaDetalle->save();
            }
        }
        /**********************************************************************************************************************/
        /* TODO: Programacion**************************************************************************************************/
        if( $r->has('mmd_id') AND is_array($r->mmd_id) ){
            foreach ($r->mmd_id as $key => $value) {
                $programacion = Programacion::find($r->mmd_programacion_id[$key]);
                $matriculaDetalle = MatriculaDetalle::find($value);

                if( trim($matriculaDetalle->programacion_id) != $programacion->id ){
                    $matriculaDetalle->programacion_id = $programacion->id;
                    $matriculaDetalle->curso_id = $programacion->curso_id;
                }

                $matriculaDetalle->persona_id_updated_at = $id;
                $matriculaDetalle->save();
            }
        }
        /**********************************************************************************************************************/
        $matricula->save();

        DB::commit();
    }

    public static function ActualizaEstadoMat($r)
    {
        DB::beginTransaction();
        $return = array('rst' => 1);
        $id=Auth::user()->id;
        
        $matricula= Matricula::find($r->matricula_id);
        $matricula->estado_mat = $r->estado_mat;
        $matricula->fecha_estado = date("Y-m-d");
        $matricula->persona_id_updated_at = $id;

        if( $r->estado_mat == 'Anulado' ){
            $matricula->observacion = $r->observacion . " | ".$matricula->observacion;
            $matricula->estado = 0;

            DB::table('mat_matriculas_detalles')
            ->where('matricula_id', '=', $r->matricula_id)
            ->update(
                array(
                    'estado' => 0,
                    'persona_id_updated_at' => $id,
                    'updated_at' => date('Y-m-d H:i:s')
                )
            );
        }
        elseif( $r->estado_mat == 'Observado' ){
            $matricula->observacion_mat = $r->observacion;
        }
        elseif( $r->estado_mat == 'Pre Aprobado' ){
            $persona = Persona::find($matricula->persona_id);
            $adicional = array('','');
            if( trim($matricula->adicional) != '' ){
                $adicional = explode("|",$matricula->adicional);
            }
            if( !isset($adicional[1]) ){ //En caso no exista dicho valor.
                $adicional[1] = "";
            }

            $sexo = array("M"=> "Masculino", "F"=> "Femenino");
            $bandeja = Matricula::InformacionMatricula($r);
            $detalle = explode("^^", $bandeja->detalle);
            $curso = []; $modalidad = []; $fecha_inicio = []; $horario = []; $frecuencia = []; $turno = [];
            foreach ($detalle as $key => $value) {
                $dvalue = explode("|", $value);
                array_push( $curso, $dvalue[0] );
                array_push( $frecuencia, $dvalue[1] );
                array_push( $horario, $dvalue[2] );
                array_push( $turno, $dvalue[3] );
                array_push( $fecha_inicio, $dvalue[4] );
                $mod = "Presencial";
                if( $dvalue[5] == '1' ){
                    $mod = "Virtual";
                }
                array_push( $modalidad, $mod );
            }
            $detcurso = explode(",", $bandeja->monto_pago);
            $total_pago = 0;
            $cuotas = Matricula::LoadCuotas($r);
            $pagos = Seminario::runPagos($r);
            $especialidadProgramacion = EspecialidadProgramacion::find($matricula->especialidad_programacion_id);
            
            $presi = 0; //Precio Inscripción;
            $presm = 0; //Precio Matricula;
            if( $bandeja->monto_pago_inscripcion*1 > 0 ){
                $presi = $bandeja->monto_pago_inscripcion*1;
            }
            if( $bandeja->monto_pago_matricula*1 > 0 ){
                $presm = $bandeja->monto_pago_matricula*1;
            }
            if( isset( $pagos[0]->presi ) ){
                $presi = $pagos[0]->presi;
            }
            if( isset( $pagos[0]->presm ) ){
                $presm = $pagos[0]->presm;
            }

            $nro_cuota = "";
            $detcuota = array("","","");
            if( ($bandeja->cronograma) != '' AND isset( $especialidadProgramacion->tipo ) AND $especialidadProgramacion->tipo == 1 ){
                $detcuota = explode(",", $bandeja->cronograma);
                $nro_cuota = count($detcuota)."C";
                if( !isset($detcuota[1]) ){
                    $detcuota[1] = "";
                }
                if( !isset($detcuota[2]) ){
                    $detcuota[2] = "";
                }
            }

            $nro_pago = '';
            $monto_pago = '';
            $tipo_pago = '';
            $archivo_pago = '';

            if( isset( $especialidadProgramacion->tipo ) AND $especialidadProgramacion->tipo == 1 ){ //Deterinar si cargo pago de cuotas o pagos de cursos
                foreach ($cuotas as $key => $value) {
                    $coma = ",";
                    if($key == 0){
                        $coma = "";
                    }
                    $nro_pago .= $coma.$value->nro_cuota;
                    $monto_pago .= $coma.$value->monto_cuota;
                    $tipo_pago .= $coma.$value->tipo_pago_cuota;
                    $total_pago += $value->monto_cuota*1;
                    $archivo_pago .= $coma.$value->archivo_cuota;
                }
            }
            elseif( trim($bandeja->nro_promocion) == '' ){
                $nro_pago = $bandeja->nro_pago;
                $monto_pago = $bandeja->monto_pago;
                $tipo_pago = $bandeja->tipo_pago;
                $archivo_pago = $bandeja->archivo;
                foreach( $detcurso as $key => $value ){
                    $total_pago += $value*1;
                }
            }

            if( trim($bandeja->nro_pago_inscripcion) == '' ){
                $bandeja->nro_pago_inscripcion="";
                $bandeja->tipo_pago_inscripcion="";
                $bandeja->monto_pago_inscripcion="";
            }

            if( trim($bandeja->nro_pago_matricula) == '' ){
                $bandeja->nro_pago_matricula="";
                $bandeja->tipo_pago_matricula="";
                $bandeja->monto_pago_matricula="";
            }

            if( trim($bandeja->nro_promocion) == '' ){
                $bandeja->nro_promocion="";
                $bandeja->tipo_pago_promocion="";
                $bandeja->monto_promocion="";
            }

            if( isset($sexo[$persona->sexo]) AND trim($sexo[$persona->sexo])!='' ){
                $sexo = $sexo[$persona->sexo];
            }
            else{
                $sexo = '';
            }

            $datos = array(
                "opcion" => "IniciarProceso",
                "id" => $matricula->id,
                "sexo" => $sexo,
                "fecha_nacimiento" => $persona->fecha_nacimiento,
                "dni_alumno" => $persona->dni,
                "paterno_alumno" => $persona->paterno,
                "materno_alumno" => $persona->materno,
                "nombre_alumno" => $persona->nombre,
                "dni_responsable" => Auth::user()->dni,
                "telefono_alumno" => $persona->telefono,
                "celular_alumno" => $persona->celular,
                "email_alumno" => $persona->email,
                "direccion_alumno" => $persona->direccion_dir,
                "empresa_id" => "e".Auth::user()->empresa_id,
                "carrera" => $bandeja->formacion,
                "curso" => implode(" | ", $curso),
                "modalidad" => implode(" | ", $modalidad),
                "fecha_inicio" => implode(" | ", $fecha_inicio),
                "horario" => implode(" | ", $horario),
                "frecuencia" => implode(" | ", $frecuencia),
                "local_estudios" => $bandeja->lugar_estudio,

                "inscripcion" => $presi,
                "matricula" => $presm,
                "cuotas" => $nro_cuota,
                "1c" => $detcuota[0],
                "2c" => $detcuota[1],
                "3c" => $detcuota[2],
                "adicional1" => $adicional[0],
                "adicional2" => $adicional[1],
                "url" => env('APP_URL'),
                "tipo_documento_id" => env('DOCUMENTO_ID'), //Esto varia según el documento de BD

                "nro_ins" => $bandeja->nro_pago_inscripcion,
                "tipo_ins" => $bandeja->tipo_pago_inscripcion,
                "monto_ins" => $bandeja->monto_pago_inscripcion,
                "archivo_ins" => $bandeja->archivo_pago_inscripcion,

                "nro_mat" => $bandeja->nro_pago_matricula,
                "tipo_mat" => $bandeja->tipo_pago_matricula,
                "monto_mat" => $bandeja->monto_pago_matricula,
                "archivo_mat" => $bandeja->archivo_pago_matricula,

                "nro_cur" => str_replace( ",", " | ", $nro_pago),
                "tipo_cur" => str_replace( ",", " | ", $tipo_pago),
                "monto_cur" => str_replace( ",", " | ", $monto_pago),
                "archivo_cur" => $archivo_pago,
                "total_cur" => $total_pago,

                "nro_pro" => $bandeja->nro_promocion,
                "tipo_pro" => $bandeja->tipo_pago_promocion,
                "monto_pro" => $bandeja->monto_promocion,
                "archivo_pro" => $bandeja->archivo_pago_promocion,
                
                "cajero" => $bandeja->cajera,
                "vendedor" => $bandeja->marketing,
                "responsable" => $bandeja->matricula,
                "supervisor" => $bandeja->supervisor,
                "created_at" => $bandeja->created_at,
                "updated_at" => $bandeja->updated_at,
                "medio_captacion2" => $bandeja->medio_captacion2,
            );
            $datos = json_encode($datos);
            $key = base64_encode(hash_hmac("sha256", $datos.date("Ymd"), env('KEY'), true));
            
            $parametros = array(
                'key' => $key,
                'datos' => $datos,
            );
            
            $url = env('URL_PROCESO'.Auth::user()->empresa_id)."?".http_build_query($parametros);
            $objArr = ApiPro::curl($url, $parametros);
            if( isset($objArr->rst) AND $objArr->rst*1 == 1 ){
                $matricula->expediente = $objArr->expediente;
                $matricula->fecha_expediente = $objArr->fecha_expediente;
            }
            elseif( isset($objArr->rst) ){
                DB::rollBack();
                $return['rst'] = $objArr->rst;
            }
            else{
                DB::rollBack();
                $return['rst'] = 0;
            }
        }
        
        if( $return['rst'] == 1 ){
            $matricula->save();
            DB::commit();
        }
        return $return;
    }

    public static function LoadCuotas($r)
    {
        $result =   DB::table('mat_matriculas_cuotas AS mmc')
                    ->join('mat_matriculas AS m','m.id', '=', 'mmc.matricula_id')
                    ->join('mat_especialidades_programaciones AS ep','ep.id', '=', 'm.especialidad_programacion_id')
                    ->leftJoin('bancos AS b', 'b.id', '=', 'mmc.tipo_pago_cuota')
                    ->select('mmc.nro_cuota','mmc.monto_cuota', 'mmc.cuota', 'mmc.archivo_cuota', 'mmc.tipo_pago_cuota AS tipo_pago_cuota_id'
                        ,'mmc.id', 'mmc.matricula_id', 'ep.nro_cuota AS programado'
                        ,DB::raw('b.banco AS tipo_pago_cuota')
                    )
                    ->where('mmc.estado', '1')
                    ->where( 
                        function($query) use ($r){
                            if( $r->has("matricula_id") AND trim($r->matricula_id) != '' ){
                                $query->where('mmc.matricula_id', $r->matricula_id);
                            }
                        }
                    )
                    ->orderBy('mmc.cuota','asc')
                    ->get();

        return $result;
    }

    public static function InformacionMatricula($r)
    {
        $set=DB::statement('SET group_concat_max_len := @@max_allowed_packet');
        $sql=DB::table('mat_matriculas AS mm')
            ->join('mat_especialidades_programaciones AS ep',function($join){
                $join->on('ep.id','=','mm.especialidad_programacion_id');
            })
            ->join('mat_matriculas_detalles AS mmd',function($join) use($r) {
                $join->on('mmd.matricula_id','=','mm.id')
                ->where('mmd.estado',1);
            })
            ->join('personas AS p',function($join){
                $join->on('p.id','=','mm.persona_id');
            })
            ->join('mat_alumnos AS ma',function($join){
                $join->on('ma.id','=','mm.alumno_id');
            })
            ->join('sucursales AS s',function($join){
                $join->on('s.id','=','mm.sucursal_id');
            })
            ->join('sucursales AS s2',function($join){
                $join->on('s2.id','=','mm.sucursal_destino_id');
            })
            ->join('mat_tipos_participantes AS mtp',function($join){
                $join->on('mtp.id','=','mm.tipo_participante_id');
            })
            ->join('mat_cursos AS mc',function($join) use($r){
                $join->on('mc.id','=','mmd.curso_id');
                if( !$r->has('global') ){
                    $join->where('mc.empresa_id', Auth::user()->empresa_id);
                }
            })
            ->join('empresas AS e',function($join){
                $join->on('e.id','=','mc.empresa_id');
            })
            ->join('personas AS pmat',function($join){
                $join->on('pmat.id','=','mm.persona_matricula_id');
            })
            ->leftJoin('mat_programaciones AS mp',function($join){
                $join->on('mp.id','=','mmd.programacion_id');
            })
            ->leftJoin('sucursales AS s3',function($join){
                $join->on('s3.id','=','mp.sucursal_id');
            })
            ->leftJoin('personas AS pcaj',function($join){
                $join->on('pcaj.id','=','mm.persona_caja_id');
            })
            ->leftJoin('personas AS pmar',function($join){
                $join->on('pmar.id','=','mm.persona_marketing_id');
            })
            ->leftJoin('mat_trabajadores AS t',function($join) use($r){
                $join->on('t.persona_id','=','mm.persona_marketing_id')
                ->where('t.empresa_id', '=', Auth::user()->empresa_id);
            })
            ->leftJoin('personas AS psup',function($join){
                $join->on('psup.id','=','mm.persona_id_updated_at');
            })
            ->leftJoin('mat_medios_captaciones AS meca',function($join){
                $join->on('meca.id','=','mm.medio_captacion_id');
            })
            ->leftJoin('mat_medios_captaciones AS meca2',function($join){
                $join->on('meca2.id','=','mm.medio_captacion_id2');
            })
            ->leftJoin('mat_especialidades_programaciones AS mep',function($join){
                $join->on('mep.id','=','mm.especialidad_programacion_id');
            })
            ->leftJoin('mat_especialidades AS me',function($join){
                $join->on('me.id','=','mmd.especialidad_id');
            })
            ->select('mm.id',DB::raw('"PLATAFORMA"'),'mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada','ep.tipo AS tipo_mat'
                    ,'mm.fecha_matricula',DB::raw('GROUP_CONCAT( DISTINCT(s3.sucursal) ) AS lugar_estudio'),'e.empresa AS empresa_inscripcion'
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), "Modular") ) AS tipo_formacion')
                    , DB::raw( 'MIN( IF( mmd.especialidad_id is null, IF( mc.tipo_curso=2, "Seminario", "Curso Libre" ), me.especialidad) ) AS formacion')
                    , DB::raw(' MIN(mm.archivo_pago) as archivo_pago_matricula, MIN(mm.archivo_pago_inscripcion) AS archivo_pago_inscripcion, MIN(mm.archivo_promocion) AS archivo_pago_promocion ')
                    /*,'mc.curso AS curso', 'mp.dia AS frecuencia'
                    , DB::raw('CONCAT(TIME(mp.fecha_inicio)," - ",TIME(mp.fecha_final)) AS horario')
                    , 'mp.turno', DB::raw('DATE(mp.fecha_inicio) AS inicio')*/
                    , DB::raw('GROUP_CONCAT( CONCAT(mc.curso, "|", IFNULL(mp.dia,""), "|", IFNULL(TIME(mp.fecha_inicio),""), " - ", IFNULL(TIME(mp.fecha_final),""), "|", IFNULL(mp.turno,""), "|", IFNULL(DATE(mp.fecha_inicio),""), "|", IFNULL(mp.sucursal_id,"") ) ORDER BY mmd.id SEPARATOR "^^" ) AS detalle')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.nro_pago_certificado ORDER BY mmd.id )
                                ) AS nro_pago')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.monto_pago_certificado ORDER BY mmd.id )
                                ) AS monto_pago')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( mmd.archivo_pago_certificado ORDER BY mmd.id )
                                ) AS archivo')
                    ,DB::raw(' IF(ep.tipo = 1, 
                                "",
                                GROUP_CONCAT( 
                                CASE 
                                    WHEN mmd.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mmd.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mmd.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mmd.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mmd.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mmd.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mmd.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mmd.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END ORDER BY mmd.id )
                                ) AS tipo_pago')
                    ,DB::raw(' IF(ep.tipo = 1, "", GROUP_CONCAT( mmd.tipo_pago ORDER BY mmd.id ) ) AS tipo_pago_id')
                    ,DB::raw(' IF(ep.tipo = 1, "", GROUP_CONCAT( mmd.id ORDER BY mmd.id ) ) AS matricula_detalle_id')
                    ,DB::raw('SUM(mmd.monto_pago_certificado) total')
                    ,'mm.nro_pago AS nro_pago_matricula','mm.monto_pago AS monto_pago_matricula'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_matricula="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_matricula="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_matricula="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_matricula="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago_matricula="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_matricula="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_matricula="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago_matricula="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_matricula')
                    ,'mm.tipo_pago_matricula AS tipo_pago_matricula_id'
                    ,'mm.nro_promocion','mm.monto_promocion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_promocion')
                    ,'mm.tipo_pago AS tipo_pago_promocion_id'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion'
                    ,DB::raw('CASE  WHEN mm.tipo_pago_inscripcion="1.1" THEN "Transferencia - BCP"
                                    WHEN mm.tipo_pago_inscripcion="1.2" THEN "Transferencia - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="1.3" THEN "Transferencia - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="1.4" THEN "Transferencia - Interbank"
                                    WHEN mm.tipo_pago_inscripcion="2.1" THEN "Depósito - BCP"
                                    WHEN mm.tipo_pago_inscripcion="2.2" THEN "Depósito - Scotiabank"
                                    WHEN mm.tipo_pago_inscripcion="2.3" THEN "Depósito - BBVA"
                                    WHEN mm.tipo_pago_inscripcion="2.4" THEN "Depósito - Interbank"
                                    ELSE "Caja"
                                END AS tipo_pago_inscripcion')
                    ,'mm.tipo_pago_inscripcion AS tipo_pago_inscripcion_id'
                    //,DB::raw('(SUM(mmd.monto_pago_certificado)+mm.monto_promocion) total')
                    ,DB::raw(' ( SELECT GROUP_CONCAT(mepc.monto_cronograma ORDER BY mepc.cuota) FROM mat_especialidades_programaciones_cronogramas mepc WHERE mepc.estado = 1 AND mepc.especialidad_programacion_id = mm.especialidad_programacion_id ) AS cronograma')
                    ,'s.sucursal','s2.sucursal AS recogo_certificado', 'mm.estado_mat', 'mm.fecha_estado', DB::raw('MIN(mm.observacion) AS obs, MIN(mm.observacion_mat) AS obs2')
                    ,DB::raw('MIN(mm.created_at) AS created_at, MIN(mm.updated_at) AS updated_at')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pcaj.paterno,pcaj.materno,pcaj.nombre))) as cajera')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmar.paterno,pmar.materno,pmar.nombre,"|",t.codigo))) as marketing')
                    ,'meca.medio_captacion','meca2.medio_captacion AS medio_captacion2'
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",pmat.paterno,pmat.materno,pmat.nombre))) as matricula')
                    ,DB::raw('GROUP_CONCAT(DISTINCT(CONCAT_WS(" ",psup.paterno,psup.materno,psup.nombre))) as supervisor')
                    )
            ->where( 
                function($query) use ($r){
                    if( $r->has('matricula_id') ){
                        $query->where('mm.id', $r->matricula_id);
                    }
                }
            )
            ->groupBy('mm.id','mtp.tipo_participante','p.dni','p.nombre','p.paterno','p.materno'
                    ,'p.telefono','p.celular','p.email','mm.validada'
                    ,'mm.fecha_matricula','e.empresa','ep.tipo'
                    ,'mm.especialidad_programacion_id'
                    ,'s.sucursal','s2.sucursal','mm.nro_promocion','mm.monto_promocion', 'mm.monto_pago', 'mm.nro_pago', 'mm.estado_mat', 'mm.fecha_estado'
                    ,'mm.nro_pago_inscripcion','mm.monto_pago_inscripcion','mm.tipo_pago','mm.tipo_pago_inscripcion','meca.medio_captacion','meca2.medio_captacion'
                    ,'mm.tipo_pago_matricula');
            
        $result = $sql->orderBy('mm.id','asc')->first();
        return $result;
    }

    public static function runBandejaValida($r)
    {
        $rsql= Matricula::BandejaValida($r);

        $length=array('A'=>5);
        $pos=array(
            5,15,35,35,35,35,35,20,20
        );

        $estatico='';
        $cab=0;
        $min=64;
        for ($i=0; $i < count($pos); $i++) { 
            if( $min==90 ){
                $min=64;
                $cab++;
                $estatico= chr($min+$cab);
            }
            $min++;
            $length[$estatico.chr($min)] = $pos[$i];
        }

        $cabeceraTit=array(
            'ESTADOS DE LAS MATRÍCULAS'
        );

        $valIni=66;
        $min2=64;
        $estatico='';
        $posTit=2; $posDet=3;
        $nrocabeceraTit=array(10,5,4);
        $colorTit=array('#FDE9D9');
        $lengthTit=array();
        $lengthDet=array();

        for( $i=0; $i<count($cabeceraTit); $i++ ){
            $cambio=false;
            $valFin=$valIni+$nrocabeceraTit[$i];
            $estaticoFin=$estatico;
            if( $valFin>90 ){
                $min2++;
                $estaticoFin= chr($min2);
                $valFin=64+$valFin-90;
                $cambio=true;
            }
            array_push( $lengthTit, $estatico.chr($valIni).$posTit.":".$estaticoFin.chr($valFin).$posTit );
            array_push( $lengthDet, $estatico.chr($valIni).$posDet.":".$estaticoFin.chr($valFin).$posDet );
            $valIni=$valFin+1;
            if( $cambio ){
                $estatico=$estaticoFin;
            }
            else{
                if($valIni>90){
                    $min2++;
                    $estatico= chr($min2);
                    $estaticoFin= $estatico;
                    $valIni=65;
                }
            }
        }

        $cabecera=array(
            'N°','Fecha de Matrícula','Persona Marketing','Alumno',
            'Carrera/Módulo', 'Curso/Inicio', 'Programación', 'Estado Matrícula', 'Fecha Estado');
        $campos=array('');

        $r['data']=$rsql;
        $r['campos']=$campos;
        $r['cabecera']=$cabecera;
        $r['length']=$length;
        $r['cabeceraTit']=$cabeceraTit;
        $r['lengthTit']=$lengthTit;
        $r['colorTit']=$colorTit;
        $r['lengthDet']=$lengthDet;
        $r['max']=$estatico.chr($min); // Max. Celda en LETRA
        return $r;
    }
}
