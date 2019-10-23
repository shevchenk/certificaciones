<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Alumno;
use App\Models\Proceso\LlamadaAtencionCliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class AlumnoPR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 

    public function EditStatus(Request $r )
    {
        if ( $r->ajax() ) {
            Alumno::runEditStatus($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function DescargarCertificado(Request $r )
    {
        $miSeminario=Alumno::MiSeminario($r);
        $fecha= explode("-",$miSeminario->fecha);
        $empresa_id = Auth::user()->empresa_id;
        $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('certificado/Formato Certificado'.$empresa_id.'.docx');
        $mes=['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];

        // --- Asignamos valores
        $templateWord->setValue('persona', $miSeminario->persona);
        $templateWord->setValue('seminario', $miSeminario->curso);
        $templateWord->setValue('dia', $fecha[2]);
        $templateWord->setValue('mes', $mes[ $fecha[1]*1 ]);
        $templateWord->setValue('año', $fecha[0]);
        $templateWord->setValue('diahoy', date("d")); 
        $templateWord->setValue('meshoy', $mes[ date("m")*1 ]); 
        $templateWord->setValue('añohoy', date("Y")); 

        // --- Guardamos el documento
        $filename='certificado/Doc'.$r->id.'.docx';
        $templateWord->saveAs($filename);
        
        header("Content-Disposition: attachment; filename=$filename"); // Vamos a dar la opcion para descargar el archivo
        readfile($filename);  // leemos el archivo para que se "descargue"
        @unlink($filename);
    }

    public function ValidarVideo(Request $r )
    {
        if ( $r->ajax() ) {
            Alumno::ValidarVideo($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function ValidarDescarga(Request $r )
    {
        if ( $r->ajax() ) {
            Alumno::ValidarDescarga($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function ObtenerHora(Request $r )
    {
        if ( $r->ajax() ) {
            $hora= date('Y-m-d H:i:s');
            $return['rst'] = 1;
            $return['hora'] = $hora;
            return response()->json($return);
        }
    }

    public function ValidarComentario(Request $r )
    {
        if ( $r->ajax() ) {
            Alumno::ValidarComentario($r);
            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
            return response()->json($return);
        }
    }

    public function BuscarPersona(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::BuscarPersona($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function MisSeminarios(Request $r )
    {
        if ( $r->ajax() ) {
            $r['tipo_curso']=2;
            $renturnModel = Alumno::MisSeminarios($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function MisCursos(Request $r )
    {
        if ( $r->ajax() ) {
            $r['tipo_curso']=1;
            $renturnModel = Alumno::MisSeminarios($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ListarSeminarios(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::ListarSeminarios($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function RegistrarEntrega(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::RegistrarEntrega($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function RegistrarLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = LlamadaAtencionCliente::RegistrarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "Registro realizado";
            return response()->json($return);
        }
    }

    public function CerrarLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            $return = LlamadaAtencionCliente::CerrarLlamada($r);
            return response()->json($return);
        }
    }

    public function ResponderLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = LlamadaAtencionCliente::ResponderLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "Registro realizado";
            return response()->json($return);
        }
    }

    public function CargarLlamada(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = LlamadaAtencionCliente::CargarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CargarLlamadaPendiente(Request $r )
    {
        if ( $r->ajax() ) {
            $r['pendiente']=1;
            $renturnModel = LlamadaAtencionCliente::CargarLlamada($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function Load(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::runLoad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function verCursos(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::verCursos($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function guardarNotas(Request $r )
    {
        if ( $r->ajax() ) {
            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'id_mat' => 
                       ['required',
                        ],
            );

            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                Alumno::editarNotaAlum($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            return response()->json($return);
        }
    }

    /*
    public function ListEspecialidad (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::ListEspecialidad($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    */
    
    public function ListEspecialidadDisponible (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Especialidad::ListEspecialidadDisponible($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

}
