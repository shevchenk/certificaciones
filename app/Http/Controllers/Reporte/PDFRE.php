<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Mantenimiento\EspecialidadProgramacion;
use App\Models\Mantenimiento\Persona;
use App\Models\Proceso\Matricula;
use App\Models\Reporte\Seminario;
use PDF;

class PDFRE extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function ExportMatricula(Request $r )
    {
        ini_set('memory_limit', '128M');
        $id=Auth::user()->id;
        $matricula= Matricula::find($r->matricula_id);
        $persona = Persona::find($matricula->persona_id);
        $adicional = array('','');
        if( trim($matricula->adicional) != '' ){
            $adicional = explode("|",$matricula->adicional);
        }
        if( !isset($adicional[1]) ){ //En caso no exista dicho valor.
            $adicional[1] = "";
        }
        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
        $fecha_matricula = explode("-", $matricula->fecha_matricula);
        $fecha = $fecha_matricula[2]." de ".$meses[$fecha_matricula[1]*1]." del ".$fecha_matricula[0];

        $sexo = array("M"=> "Masculino", "F"=> "Femenino");
        $bandeja = Matricula::InformacionMatricula($r);
        
        $detalle = explode("^^", $bandeja->detalle);
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

        /*$nro_cuota = "";
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
        }*/

        $nombre_pago = array();
        $nro_pago = array();
        $monto_pago = array();
        $tipo_pago = array();

        if( isset( $especialidadProgramacion->tipo ) AND $especialidadProgramacion->tipo == 1 ){ //Deterinar si cargo pago de cuotas o pagos de cursos
            foreach ($cuotas as $key => $value) {
                array_push( $nombre_pago, "Cuota ".$value->cuota);
                array_push( $nro_pago, $value->nro_cuota);
                array_push( $monto_pago, $value->monto_cuota);
                array_push( $tipo_pago, $value->tipo_pago_cuota);
            }
        }
        elseif( trim($bandeja->nro_promocion) == '' ){
            $nro_pago = explode(",", $bandeja->nro_pago);
            $monto_pago = explode(",", $bandeja->monto_pago);
            $tipo_pago = explode(",", $bandeja->tipo_pago);
            foreach( $monto_pago as $key => $value ){
                if( $value*1 > 0 ){
                    $nombre = explode("|", $detalle[$key]);
                    array_push( $nombre_pago, $nombre[0]);
                    array_push( $nro_pago, $nro_pago[$key]);
                    array_push( $monto_pago, $monto_pago[$key]);
                    array_push( $tipo_pago, $tipo_pago[$key]);
                }
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
        $data = [
            'titulo' => 'Styde.net',
            'id' => $bandeja->id,
            'empresa' => 'Intur Perú',
            'url_logo' => 'img/inscripcion/logo1.png',
            'fecha' => $fecha,
            'persona' => trim($persona->nombre." ".$persona->paterno." ".$persona->materno),
            'dni' => $persona->dni,
            'formacion' => $bandeja->formacion,
            'detalle' => $detalle,
            'lugar_estudio' => $bandeja->lugar_estudio,
            'adicional' => $adicional,
            'medio_captacion2' => $bandeja->medio_captacion2,
            'marketing' => $bandeja->marketing,
            'nombre_pago' => $nombre_pago,
            'nro_pago' => $nro_pago,
            'monto_pago' => $monto_pago,
            'tipo_pago' => $tipo_pago,
            'pagos' => $pagos,
            'nro_pago_inscripcion' => $bandeja->nro_pago_inscripcion,
            'monto_pago_inscripcion' => $bandeja->monto_pago_inscripcion,
            'tipo_pago_inscripcion' => $bandeja->tipo_pago_inscripcion,
            'nro_pago_matricula' => $bandeja->nro_pago_matricula,
            'monto_pago_matricula' => $bandeja->monto_pago_matricula,
            'tipo_pago_matricula' => $bandeja->tipo_pago_matricula,
            'nro_pago_promocion' => $bandeja->nro_promocion,
            'monto_pago_promocion' => $bandeja->monto_promocion,
            'tipo_pago_promocion' => $bandeja->tipo_pago_promocion,
            'estado_mat' => $bandeja->estado_mat,
        ];

        $pdf = PDF::loadView('reporte.pdf.matricula', $data);

        $ficha = 'FICHA DE MATRÍCULA.pdf';
        if( $bandeja->estado_mat == 'Pre Aprobado' ){
            $ficha = 'FICHA DE INSCRIPCIÓN.pdf';
        }
        return $pdf->download($ficha);
    }
    
}
