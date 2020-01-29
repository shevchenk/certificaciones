<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reporte\Reporte;
use Excel;

class ReporteEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function Aprobados(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Reporte::Aprobados($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }
    
    public function LoadPAE(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Reporte::runLoadPAE($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadIndiceMat(Request $r )
    {
        if ( $r->ajax() ) {
            if( $r->has("fecha_ini") AND $r->has("fecha_fin")){
                $r->ult_dia=$r->fecha_fin;
                $r->penult_dia=date('Y-m-d',strtotime('-1 day',strtotime($r->fecha_fin)));
            }
            $renturnModel = Reporte::runLoadIndiceMat($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CalcularAsignados(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Reporte::CalcularAsignados($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function CalcularAsignadosDos(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Reporte::CalcularAsignadosDos($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadVisita(Request $r )
    {
        if ( $r->ajax() ) {
            $renturnModel = Reporte::runLoadVisita($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ExportPAECab(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportPAECab($r);
        
        Excel::create('Matricula', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Matriculas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Matricula', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE MATRICULAS PAE');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '20',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:H1');
            $sheet->cells('A1:'.$renturnModel['max'].'1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            //dd($renturnModel);exit(0);
            for ($i=0; $i < count($renturnModel['cabecera1']); $i++) { 
                $campoinicial= explode(":",$renturnModel['cabecantLetra'][$i]);
                $sheet->cell($campoinicial[0], function($cell) use($renturnModel,$i) {
                    $cell->setValue($renturnModel['cabecera1'][$i]);
                });
                $sheet->mergeCells($renturnModel['cabecantLetra'][$i]);
                $sheet->cells($renturnModel['cabecantLetra'][$i], function($cells) {
                    $cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
            }

            $sheet->row( 4, $renturnModel['cabecera2'] );
            $sheet->setWidth($renturnModel['length']);

            $data=json_decode(json_encode($renturnModel['data']), true);
            //$sheet->rows($data);
            $pos=4;
            $desglosar=array(array(),array(),array(),array(),array(),array());
            for ($i=0; $i<count($data); $i++) {
                $ndet=$data[$i]['ndet'];
                $pos++;
                unset($data[$i]['ndet']);
                $data[$i]['id']=$i+1;
                if($ndet>1){
                    $desglosar[0]=explode("\n",$data[$i]['curso1']);
                    $desglosar[1]=explode("\n",$data[$i]['nro_pago_c1']);
                    $desglosar[2]=explode("\n",$data[$i]['monto_pago_c1']);
                    $desglosar[3]=explode("\n",$data[$i]['nro_pago_certificado1']);
                    $desglosar[4]=explode("\n",$data[$i]['monto_pago_certificado1']);
                    $desglosar[5]=explode("\n",$data[$i]['modalidad1']);
                    $data[$i]['curso1']=$desglosar[0][0];
                    $data[$i]['nro_pago_c1']=$desglosar[1][0];
                    $data[$i]['monto_pago_c1']=$desglosar[2][0];
                    $data[$i]['nro_pago_certificado1']=$desglosar[3][0];
                    $data[$i]['monto_pago_certificado1']=$desglosar[4][0];
                    $data[$i]['modalidad1']=$desglosar[5][0];
                }

                for ($j=2; $j <= $renturnModel['total']; $j++) { 
                    if($ndet>1 AND $j<=$ndet){
                        $data[$i]['curso'.$j]=$desglosar[0][($j-1)];
                        $data[$i]['nro_pago_c'.$j]=$desglosar[1][($j-1)];
                        $data[$i]['monto_pago_c'.$j]=$desglosar[2][($j-1)];
                        $data[$i]['nro_pago_certificado'.$j]=$desglosar[3][($j-1)];
                        $data[$i]['monto_pago_certificado'.$j]=$desglosar[4][($j-1)];
                        $data[$i]['modalidad'.$j]=$desglosar[5][($j-1)];
                    }
                    else{
                        $data[$i]['curso'.$j]='';
                        $data[$i]['nro_pago_c'.$j]='';
                        $data[$i]['monto_pago_c'.$j]='';
                        $data[$i]['nro_pago_certificado'.$j]='';
                        $data[$i]['monto_pago_certificado'.$j]='';
                        $data[$i]['modalidad'.$j]='';
                    }
                }


                $sheet->row( $pos, $data[$i] );

            }

            $sheet->cells('A3:'.$renturnModel['max'].'4', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
            });
            
            /*$sheet->setAutoSize(array(
                'Q', 'R','S','T','U'
            ));*/

            $count = $sheet->getHighestRow();

            //$sheet->getStyle('Q4:U'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }
    
    public function ExportPAE(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportPAE($r);
        
        Excel::create('Matricula', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Matriculas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Matricula', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE MATRICULAS');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '20',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:'.$renturnModel['max'].'1');
            $sheet->cells('A1:'.$renturnModel['max'].'1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->fromArray(array(
                array(''),
                $renturnModel['cabecera2']
            ));

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);

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
            
            $sheet->setAutoSize(array(
                'Q', 'R','S','T','U','V'
            ));

            $count = $sheet->getHighestRow();

            $sheet->getStyle('Q4:V'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportPAEDesglose(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportPAE($r);
        
        Excel::create('Matricula', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Matriculas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Matricula', function($sheet) use($renturnModel) {
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

            $valores=array(
                        'data' => json_decode(json_encode($renturnModel['data']), true),
                        'campos'=>$renturnModel['campos'],
                        'cabecera1'=>$renturnModel['cabecera1'],
                        'cabecant'=>$renturnModel['cabecantNro'],
                        'cabecera2'=>$renturnModel['cabecera2']
                    );
            
            $sheet->loadView('reporte.exportar.exppae', $valores);
            $sheet->setAutoSize(array(
                'R','S','T','U'
            ));

            $count = $sheet->getHighestRow();

            $sheet->getStyle('R5:U'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportPAEDes(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportPAE($r);
        
        Excel::create('Matricula', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Matriculas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Matricula', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE MATRICULAS PAE');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '20',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:H1');
            $sheet->cells('A1:'.$renturnModel['max'].'1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            //$sheet->setWidth($renturnModel['length']);

            for ($i=0; $i < count($renturnModel['cabecera1']); $i++) { 
                $campoinicial= explode(":",$renturnModel['cabecantLetra'][$i]);
                $sheet->cell($campoinicial[0], function($cell) use($renturnModel,$i) {
                    $cell->setValue($renturnModel['cabecera1'][$i]);
                });
                $sheet->mergeCells($renturnModel['cabecantLetra'][$i]);
                $sheet->cells($renturnModel['cabecantLetra'][$i], function($cells) {
                    $cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
            }

            $sheet->row( 4, $renturnModel['cabecera2'] );

            $data=json_decode(json_encode($renturnModel['data']), true);
            //$sheet->rows($data);
            $pos=4;
            $desglosar=array(array(),array(),array(),array(),array(),array());
            for ($i=0; $i<count($data); $i++) {
                $ndet=$data[$i]['ndet'];
                $pos++;
                unset($data[$i]['ndet']);
                $data[$i]['id']=$i+1;
                if($ndet>1){
                    $desglosar[0]=explode("\n",$data[$i]['cursos']);
                    $desglosar[1]=explode("\n",$data[$i]['nro_pago_c']);
                    $desglosar[2]=explode("\n",$data[$i]['monto_pago_c']);
                    $desglosar[3]=explode("\n",$data[$i]['nro_pago_certificado']);
                    $desglosar[4]=explode("\n",$data[$i]['monto_pago_certificado']);
                    $desglosar[5]=explode("\n",$data[$i]['modalidad']);
                    $data[$i]['cursos']=$desglosar[0][0];
                    $data[$i]['nro_pago_c']=$desglosar[1][0];
                    $data[$i]['monto_pago_c']=$desglosar[2][0];
                    $data[$i]['nro_pago_certificado']=$desglosar[3][0];
                    $data[$i]['monto_pago_certificado']=$desglosar[4][0];
                    $data[$i]['modalidad']=$desglosar[5][0];
                }
                $sheet->row( $pos, $data[$i] );
                if($ndet>1){
                    for ($j=1; $j < count($desglosar[0]); $j++) { 
                        $pos++;
                        $sheet->cell("Q".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[5][$j]);
                        });
                        $sheet->cell("R".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[0][$j]);
                        });
                        $sheet->cell("S".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[1][$j]);
                        });
                        $sheet->cell("T".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[2][$j]);
                        });
                        $sheet->cell("U".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[3][$j]);
                        });
                        $sheet->cell("V".$pos, function($cell) use($desglosar,$j) {
                                $cell->setValue($desglosar[4][$j]);
                        });
                    }
                }

            }

            $sheet->cells('A3:'.$renturnModel['max'].'4', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
            });
            
            $sheet->setAutoSize(array(
                'Q', 'R','S','T','U'
            ));

            $count = $sheet->getHighestRow();

            $sheet->getStyle('Q4:U'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportIndiceMat(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportIndiceMat($r);
        
        Excel::create('Matricula', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Matriculas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Indice_Mat', function($sheet) use($renturnModel,$r) {
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
                $cell->setValue('REPORTE DE INDICE DE INSCRIPCIÓN');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '20',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:H1');
            $sheet->cells('A1:'.$renturnModel['max'].'1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(3, 79);


            $sheet->row( 3, $renturnModel['cabecera'] );

            $sheet->cells('M3:R3', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            //$sheet->rows($data);
            $pos=3;
            $contador=0;
            $auxode="";
            $subtotal=array(
                '','','','','',
                '',0,0,0,
                0,0,
                '','',
                0,'',0,0,
                0,''
            );
            $totales=array(
                '','','','','',
                'Totales',0,0,0,
                0,0,
                '','',
                0,'',0,0,
                0,''
            );
            for ($i=0; $i<count($data); $i++) {
                $pos++;
                $contador++;

                if( $data[$i]['fecha_final']!='' ){
                    $fini=substr($data[$i]['fecha_inicio'], 0,10);
                    $data[$i]['fecha_inicio']=substr($data[$i]['fecha_inicio'],11,5)." a ".substr($data[$i]['fecha_final'],11,5);
                    $data[$i]['fecha_final']=$fini;
                }
                unset($data[$i]['fecha_final']);

                $indice_x_dia=0;
                $mat_prog_x_dia=0;
                $proy_fin_cam=0;
                $dias_falta=$data[$i]['dias_falta'];
                if($data[$i]['ndias']>0){
                    $indice_x_dia = round( ($data[$i]['mat']/$data[$i]['ndias']),2 );
                    $mat_prog_x_dia=round( ($indice_x_dia*$dias_falta),2 );
                }
                $proy_fin_cam=round( ($data[$i]['mat']+$mat_prog_x_dia),2 );
                $color="FF4848";
                if( $proy_fin_cam>=$data[$i]['meta_max'] ){
                    $color="35FF35";
                }
                else if( $proy_fin_cam>=$data[$i]['meta_min'] ){
                    $color="FFFF48";
                }
                $mat_falt_meta = round( ($data[$i]['meta_max'] - $proy_fin_cam),2 );
                unset($data[$i]['dias_falta']);
                array_push($data[$i], $indice_x_dia);
                array_push($data[$i], $dias_falta);
                array_push($data[$i], $mat_prog_x_dia);
                array_push($data[$i], $proy_fin_cam);
                array_push($data[$i], $mat_falt_meta);

                if( $auxode!=$data[$i]['empresa'] ){
                    if( $auxode!='' ){
                        $contador=1;
                        $sheet->row( $pos, $subtotal );

                        $sheet->cells('A'.$pos.':'.$renturnModel['max'].$pos, function($cells) {
                            $cells->setBorder('solid', 'none', 'none', 'solid');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                            $cells->setFont(array(
                                'family'     => 'Bookman Old Style',
                                'size'       => '10',
                                'bold'       =>  true
                            ));
                            $cells->setBackground('#DCE6F1');
                        });

                        $subtotal=array(
                            '','','','','','',
                            '',0,0,0,
                            0,0,
                            '','',
                            0,'',0,0,
                            0,''
                        );
                        $pos++;
                    }

                    $auxode=$data[$i]['empresa'];
                }
                $subtotal[6]+=$data[$i]['ult_dia'];
                $subtotal[7]+=$data[$i]['penult_dia'];
                $subtotal[8]+=$data[$i]['mat'];
                $subtotal[9]+=$data[$i]['meta_max'];
                $subtotal[10]+=$data[$i]['meta_min'];
                $subtotal[13]+=$indice_x_dia;
                $subtotal[15]+=$mat_prog_x_dia;
                $subtotal[16]+=$proy_fin_cam;
                $subtotal[17]+=$mat_falt_meta;

                $totales[6]+=$data[$i]['ult_dia'];
                $totales[7]+=$data[$i]['penult_dia'];
                $totales[8]+=$data[$i]['mat'];
                $totales[9]+=$data[$i]['meta_max'];
                $totales[10]+=$data[$i]['meta_min'];
                $totales[13]+=$indice_x_dia;
                $totales[15]+=$mat_prog_x_dia;
                $totales[16]+=$proy_fin_cam;
                $totales[17]+=$mat_falt_meta;
                $data[$i]['id']=$contador;

                $sheet->row( $pos, $data[$i] );
                $sheet->cells('R'.$pos.':R'.$pos, function($cells) use($color){
                    $cells->setBackground('#'.$color);
                });
            }

            $pos++;
            $sheet->row( $pos, $subtotal );
            $sheet->cells('A'.$pos.':'.$renturnModel['max'].$pos, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $count = $sheet->getHighestRow();
            $pos++;
            $pos++;
            $sheet->row( $pos, $totales );
            $sheet->cells('F'.$pos.':'.$renturnModel['max'].$pos, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->mergeCells('G3:H3');

            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->cells('I4:I'.$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            
            $sheet->getStyle('A3:'.$renturnModel['max'].$count)->getAlignment()->setWrapText(true);
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportLlamadas(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportLlamadas($r);
        
        Excel::create('Llamadas', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Llamadas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Resumen estadístico de llamadas realizadas');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE GENERAL DE LLAMADAS DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'2', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->setHeight(3, 68.5);
            $sheet->row( 3, $renturnModel['cabecera2'] );
            $sheet->cells('C3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);
            //$pos=3;
            $contador=0;
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->cell('A'.$count,'Total General');
            $sheet->cells('A'.$count.':'.$renturnModel['max'].$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $sheet->cells('B4:B'.($count-1), function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->getStyle('A2:'.$renturnModel['max'].'2')->getAlignment()->setWrapText(true);
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportNoInteresados(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportNoInteresados($r);
        
        Excel::create('No Interesados', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de No Interesados')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Resumen estadístico de llamadas realizadas');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE DE LLAMADAS NO INTERESADOS DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'2', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->setHeight(3, 68.5);
            $sheet->row( 3, $renturnModel['cabecera2'] );
            $sheet->cells('C3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);
            //$pos=3;
            $contador=0;
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->cell('A'.$count,'Total General');
            $sheet->cells('A'.$count.':'.$renturnModel['max'].$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $sheet->cells('B4:B'.($count-1), function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->getStyle('A2:'.$renturnModel['max'].'2')->getAlignment()->setWrapText(true);
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportNoInteresadosDetalle(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportNoInteresadosDetalle($r);
        
        Excel::create('No Interesados Detalle', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de No Interesados Detalle')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Resumen estadístico de llamadas realizadas');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE DE LLAMADAS NO INTERESADOS DETALLE DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'2', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->setHeight(3, 68.5);
            $sheet->row( 3, $renturnModel['cabecera2'] );
            $sheet->cells('C3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            //$sheet->rows($data);
            $pos=3;
            $posaux=0;
            for ($i=0; $i<count($data); $i++) {
                if( $data[$i]['tipo_llamada_sub_detalle']!='' OR ($i+1)==count($data) ){
                    $pos++;
                    if($posaux==0){
                        $posaux=$pos;
                    }
                    $data[$i]['tipo_llamada_sub']='';
                    $sheet->row( $pos, $data[$i] );
                }
                else{
                    $sheet->mergeCells('A'.$posaux.':A'.$pos);
                    $sheet->cells('A'.$posaux.':A'.$pos, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '8',
                            'bold'       =>  true
                        ));
                    });
                    $sheet->cell('A'.$posaux, $data[$i]['tipo_llamada_sub']);
                    $posaux=0;
                }
            }
                $sheet->cells('A1:'.$renturnModel['max'].'1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setTextRotation(90);
                });
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->cell('A'.$count,'Total General');
            $sheet->cells('A'.$count.':'.$renturnModel['max'].$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $sheet->cells('C4:C'.($count-1), function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->setAutoSize(array(
                'A'
            ));
            $sheet->getStyle('A2:'.$renturnModel['max'].'2')->getAlignment()->setWrapText(true);
            $sheet->getStyle('A2:A'.$count)->getAlignment()->setWrapText(true);
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportInteresados(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportInteresados($r);
        
        Excel::create('Interesados', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Interesados')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Resumen estadístico de llamadas realizadas');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE DE LLAMADAS DE INTERESADOS DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'2', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->setHeight(3, 68.5);
            $sheet->row( 3, $renturnModel['cabecera2'] );
            $sheet->cells('C3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);
            //$pos=3;
            $contador=0;
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->cell('A'.$count,'Total General');
            $sheet->cells('A'.$count.':'.$renturnModel['max'].$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $sheet->cells('B4:B'.($count-1), function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });

            $sheet->getStyle('A2:'.$renturnModel['max'].'2')->getAlignment()->setWrapText(true);
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportLlamadasDetalle(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportLlamadasDetalle($r);
        
        Excel::create('Llamadas', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Llamadas')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Detalle de llamadas realizadas');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE DETALLADO DE LLAMADAS DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->row( 3, $renturnModel['cabecera2'] );

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);
            //$pos=3;
            $contador=0;
            
            $sheet->cells('A3:'.$renturnModel['max'].'3', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->getStyle('A2:'.$renturnModel['max'].'2')->getAlignment()->setWrapText(true);
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportClientePotencial(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Reporte::runExportClientePotencial($r);
        
        Excel::create('Cliente Potencial', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Clientes Potenciales')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Clientes Potenciales');

        $excel->sheet('DATA', function($sheet) use($renturnModel,$r) {
            $sheet->setOrientation('landscape'); //portrait
            $sheet->setPageMargin(array(
                0.25, 0.50, 0.25, 0.30
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Bookman Old Style',
                    'size'      =>  10,
                    'bold'      =>  false
                )
            ));

            $fecha_ini=$r->fecha_ini;
            $fecha_fin=$r->fecha_fin;

            $sheet->cell('A2', function($cell) use ($fecha_ini,$fecha_fin) {
                $cell->setValue('REPORTE DE LLAMADAS DE CLIENTES POTENCIALES DEL '.$fecha_ini.' AL '.$fecha_fin);
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '14',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A2:'.$renturnModel['max'].'2');
            $sheet->cells('A2:'.$renturnModel['max'].'2', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            for ($i=0; $i < count($renturnModel['cabecera1']); $i++) { 
                $campoinicial= explode(":",$renturnModel['cabecantLetra'][$i]);
                $sheet->cell($campoinicial[0], function($cell) use($renturnModel,$i) {
                    $cell->setValue($renturnModel['cabecera1'][$i]);
                });
                $sheet->mergeCells($renturnModel['cabecantLetra'][$i]);
                $sheet->cells($renturnModel['cabecantLetra'][$i], function($cells) {
                    $cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
            }

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 54.5);
            $sheet->setHeight(4, 83.5);
            $sheet->row( 4, $renturnModel['cabecera2'] );
            $sheet->cells('J4:'.$renturnModel['max'].'4', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
            });

            $data=json_decode(json_encode($renturnModel['data']), true);
            $sheet->rows($data);
            //$pos=3;
            $contador=0;
            
            $sheet->cells('A3:'.$renturnModel['max'].'4', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            $count = $sheet->getHighestRow();
            $sheet->cell('A'.($count+1),'Total General');
            $sheet->cells('A'.($count+1).':'.$renturnModel['max'].$count, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#95B3D7');
            });

            /*$sheet->cells('B4:B'.($count-1), function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
                $cells->setBackground('#DCE6F1');
            });*/

            $sheet->getStyle('A2:'.$renturnModel['max'].'4')->getAlignment()->setWrapText(true);
            //$sheet->getStyle('A3:'.$renturnModel['max'].'4')->getAlignment()->setWrapText(true);            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');
        });
        
        })->export('xlsx');
    }

    public function ExportVisita(Request $r )
    {
        $renturnModel = Reporte::runExportVisita($r);
        
        Excel::create('Visita', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Visita')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Visita');

        $excel->sheet('Visita', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE VISITA A PLATAFORMA');
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
                //unset($data[$i]['ndet']);
                $data[$i]['id']=$i+1;
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
