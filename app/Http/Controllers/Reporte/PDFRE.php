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
    
    public function ExportPrueba(Request $r )
    {
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
        $fecha = $fecha_matricula[0]." de ".$meses[$fecha_matricula[1]*1]." del ".$fecha_matricula[2];

        $sexo = array("M"=> "Masculino", "F"=> "Femenino");
        $bandeja = Matricula::InformacionMatricula($r);
        
        $detalle = explode("^^", $bandeja->detalle);
        
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
            }
        }
        elseif( trim($bandeja->nro_promocion) == '' ){
            $nro_pago = $bandeja->nro_pago;
            $monto_pago = $bandeja->monto_pago;
            $tipo_pago = $bandeja->tipo_pago;
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
        $data = [
            'titulo' => 'Styde.net',
            'id' => '1565',
            'empresa' => 'Intur Perú',
            'url_logo' => 'img/inscripcion/logo1.png',
            'fecha' => $fecha,
            'persona' => trim($persona->nombre." ".$persona->paterno." ".$persona->materno),
            'dni' => $persona->dni,
            'formacion' => $bandeja->formacion,
            'detalle' => $detalle,
            'lugar_estudio' => $bandeja->lugar_estudio,
        ];

        $pdf = PDF::loadView('reporte.pdf.prueba', $data);
        return $pdf->download('pruebapdf.pdf');
    }
    
}
