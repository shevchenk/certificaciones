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
    
    public function ExportPAE(Request $r )
    {
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
        $renturnModel = Reporte::runExportIndiceMat($r);
        
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
}
