<?php
namespace App\Http\Controllers\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proceso\Matricula;
use App\Models\Proceso\Alumno;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Excel;

class MatriculaPR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 


   public function New(Request $r )
    {
        if ( $r->ajax() ) {

            $mensaje= array(
                'required'    => ':attribute es requerido',
                'unique'        => ':attribute solo debe ser único',
            );

            $rules = array(
                'persona_id' => 
                       ['required'
                        ],
            );

            
            $validator=Validator::make($r->all(), $rules,$mensaje);

            if ( !$validator->fails() ) {
                if( trim($r->nro_promocion)!=''){
                    $nro_p = trim( $r->nro_promocion);
                    $monto_p = trim( $r->monto_promocion);
                    $tipo_p = trim( $r->tipo_pago);
                    $sql="
                    SELECT id
                    FROM mat_matriculas
                    WHERE nro_promocion='$nro_p'
                    AND monto_promocion='$monto_p'
                    AND tipo_pago='$tipo_p'
                    UNION 
                    SELECT id
                    FROM mat_matriculas_detalles
                    WHERE nro_pago_certificado='$nro_p'
                    AND monto_pago_certificado='$monto_p'
                    AND tipo_pago='$tipo_p'
                    ";
                    $datos = DB::select($sql);
                    if( isset($datos[0]->id) ){
                        $return['rst'] = 2;
                        $return['msj'] = 'El nro:'.$nro_p.'| monto:'.$monto_p.' ya fue registrado anteriormente';
                        return response()->json($return);
                    }
                }
                else{
                    $curso_id = $r->curso_id;
                    $programacion_id=$r->programacion_id;
                    for($i=0;$i<count($curso_id);$i++){
                        
                        if( isset($r->nro_pago_certificado[$i]) ){
                            $nro_p = $r->nro_pago_certificado[$i];
                            $monto_p = $r->monto_pago_certificado[$i];
                            $tipo_p = $r->tipo_pago_detalle[$i];
                            if( $nro_p!='' AND $monto_p!='' ){
                                $sql="
                                SELECT id
                                FROM mat_matriculas
                                WHERE nro_promocion='$nro_p'
                                AND monto_promocion='$monto_p'
                                AND tipo_pago='$tipo_p'
                                UNION 
                                SELECT id
                                FROM mat_matriculas_detalles
                                WHERE nro_pago_certificado='$nro_p'
                                AND monto_pago_certificado='$monto_p'
                                AND tipo_pago='$tipo_p'
                                ";
                                $datos = DB::select($sql);
                                if( isset($datos[0]->id) ){
                                    $return['rst'] = 2;
                                    $return['msj'] = 'El nro:'.$nro_p.'| monto:'.$monto_p.' ya fue registrado anteriormente';
                                    return response()->json($return);
                                }
                            }
                        }

                        if( isset($programacion_id[$i]) ){
                            //Validamos si ya tiene el curso inscrito con esa programación
                            $sql = "SELECT m.id 
                                    FROM mat_matriculas m
                                    INNER JOIN mat_matriculas_detalles md ON md.matricula_id = m.id AND md.estado = 1 
                                    WHERE m.persona_id = $r->persona_id
                                    AND md.programacion_id = $programacion_id[$i]
                                    AND m.estado = 1";

                            $datos = DB::select($sql);
                            if( isset($datos[0]->id) ){
                                $return['rst'] = 2;
                                $return['msj'] = 'La programación del curso seleccionada, ya fue registrado anteriormente';
                                return response()->json($return);
                            }
                        }
                    }
                }


                $id=Matricula::runNew($r);
                $return['rst'] = 1;
                $return['matricula_id'] = $id;
                $return['msj'] = 'Registro creado';
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = $validator->errors()->all()[0];
            }
            return response()->json($return);
        }
    }
    
    public function BuscarAlumno (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Alumno::BuscarAlumno($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function BandejaValida (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Matricula::BandejaValida($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function BandejaHistorica (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Matricula::BandejaValida($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ActualizaEstadoMat (Request $r)
    {
        if ( $r->ajax() ) {
            $return = Matricula::ActualizaEstadoMat($r);
            //dd($return);
            return response()->json($return);
        }
    }

    public function ActualizaMat (Request $r)
    {
        if ( $r->ajax() ) {
            Matricula::ActualizaMat($r);
            $return['rst'] = 1;
            return response()->json($return);
        }
    }

    public function LoadCuotas (Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Matricula::LoadCuotas($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            return response()->json($return);
        }
    }
    
    public function ExportBandejaValida (Request $r)
    {
        $renturnModel = Matricula::runBandejaValida($r);
        
        Excel::create('Matrículas', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Matrículas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrículas');

        $excel->sheet('Matrículas', function($sheet) use($renturnModel) {
            $sheet->setOrientation('landscape');
            $sheet->setPageMargin(array(
                0.25, 0.30, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  8,
                    'bold'      =>  false
                )
            ));

            $sheet->cell('A1', function($cell) {
                $cell->setValue('REPORTE DE ESTADOS DE MATRÍCULA');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '24',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:J1');
            $sheet->cells('A1:J1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 33.5);
            $sheet->fromArray(array(
                array(''),
                $renturnModel['cabecera']
            ));

            for( $i=0; $i<COUNT($renturnModel['cabeceraTit']); $i++ ){
                $posicion= explode(":",$renturnModel['lengthTit'][$i]);
                $sheet->cell($posicion[0], function($cell) use( $renturnModel,$i ){
                    $cell->setValue($renturnModel['cabeceraTit'][$i]);
                    $cell->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '12',
                            'bold'       =>  true
                    ));
                });
                $sheet->mergeCells($renturnModel['lengthTit'][$i]);
                $sheet->cells($renturnModel['lengthTit'][$i], function($cells) use( $renturnModel,$i) {
                    $cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($renturnModel['colorTit'][$i]);
                });

                $sheet->cells($renturnModel['lengthDet'][$i], function($cells) use( $renturnModel,$i) {
                    $cells->setBackground($renturnModel['colorTit'][$i]);
                });
            }

            $data=json_decode(json_encode($renturnModel['data']), true);
            $pos=3;
            for ($i=0; $i<count($data); $i++) {
                $data[$i]['id'] = $i+1;
                //$data[$i]['nombre'] = trim($data[$i]['nombre']." ".$data[$i]['paterno']." ".$data[$i]['materno']);
                $data[$i]['nombre'] = $data[$i]['paterno'] ." ".$data[$i]['materno']." ".$data[$i]['nombre'];
                $auxcurso = array(); $auxprogramacion = array();
                $rdet = explode("^^", $data[$i]['detalle']);
                foreach( $rdet as $rdet2 ){
                    $auxredt = explode("|", $rdet2);
                    array_push($auxcurso, $auxredt[0]);
                    array_push($auxprogramacion, $auxredt[1]." / ".$auxredt[4]." / ".$auxredt[2]);
                }
                $data[$i]['paterno'] = implode(" | ", $auxcurso);
                $data[$i]['materno'] = implode(" | ", $auxprogramacion);;

                unset($data[$i]['PLATAFORMA']);
                unset($data[$i]['tipo_participante']);
                unset($data[$i]['dni']);
                unset($data[$i]['telefono']);
                unset($data[$i]['detalle']);
                unset($data[$i]['celular']);
                unset($data[$i]['email']);
                unset($data[$i]['validada']);
                unset($data[$i]['tipo_mat']);
                unset($data[$i]['lugar_estudio']);
                unset($data[$i]['empresa_inscripcion']);
                unset($data[$i]['tipo_formacion']);
                unset($data[$i]['archivo_pago_matricula']);
                unset($data[$i]['archivo_pago_inscripcion']);
                unset($data[$i]['archivo_pago_promocion']);
                unset($data[$i]['nro_pago']);
                unset($data[$i]['monto_pago']);
                unset($data[$i]['archivo']);
                unset($data[$i]['tipo_pago']);
                unset($data[$i]['tipo_pago_id']);
                unset($data[$i]['matricula_detalle_id']);
                unset($data[$i]['total']);
                unset($data[$i]['nro_pago_matricula']);
                unset($data[$i]['monto_pago_matricula']);
                unset($data[$i]['tipo_pago_matricula']);
                unset($data[$i]['tipo_pago_matricula_id']);
                unset($data[$i]['nro_promocion']);
                unset($data[$i]['monto_promocion']);
                unset($data[$i]['tipo_pago_promocion']);
                unset($data[$i]['tipo_pago_promocion_id']);
                unset($data[$i]['nro_pago_inscripcion']);
                unset($data[$i]['monto_pago_inscripcion']);
                unset($data[$i]['tipo_pago_inscripcion']);
                unset($data[$i]['tipo_pago_inscripcion_id']);
                unset($data[$i]['sucursal']);
                unset($data[$i]['recogo_certificado']);
                unset($data[$i]['obs']);
                unset($data[$i]['obs2']);
                unset($data[$i]['cajera']);
                unset($data[$i]['medio_captacion']);
                unset($data[$i]['medio_captacion2']);
                unset($data[$i]['matricula']);
                unset($data[$i]['supervisor']);
                $pos++;
                $sheet->row( $pos, $data[$i] );
            }
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
            });

            $count = $sheet->getHighestRow();
            $sheet->getStyle('A2:'.$renturnModel['max']."2")->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

}
