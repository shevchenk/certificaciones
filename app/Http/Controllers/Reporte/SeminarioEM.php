<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reporte\Seminario;
use Excel;

class SeminarioEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function LoadSeminario(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runLoadSeminario($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadControlPago(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runControlPago($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadEvaluaciones(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runEvaluaciones($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ExportSeminario(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportSeminario($r);
        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Seminarios', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE INSCRITOS DE FORMACIÓN CONTINUA');
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
                if( $data[$i]['validada']==0 ){
                    $data[$i]['validada']='Falta Validar';
                }
                else{
                    $data[$i]['validada']='Validó';
                }
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
            
            $sheet->setAutoSize(array(
                'O','P','Q','R','S','T'
            ));

            $count = $sheet->getHighestRow();
            $sheet->getStyle('A2:'.$renturnModel['max']."2")->getAlignment()->setWrapText(true);
            $sheet->getStyle('O4:T'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportSeminarioDetalle(Request $r )
    {
        $renturnModel = Seminario::runExportSeminarioDetalle($r);
        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Matrícula PAE o Seminarios');

        $excel->sheet('Seminarios', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE DE SEMINARIOS');
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
                $renturnModel['cabecera']
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
                'M', 'N','O','P','Q'
            ));

            $count = $sheet->getHighestRow();

            //$sheet->getStyle('M4:Q'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportAlumnosInscritos(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $renturnModel = Seminario::runExportAlumnosInscritos($r);
        
        Excel::create('Alumnos Inscritos', function($excel) use($renturnModel,$r) {

        $excel->setTitle('Reporte de Alumnos Inscritos')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Detalle de Alumnos Inscritos');

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
                $cell->setValue('ALUMNOS INSCRITOS DEL '.$fecha_ini.' AL '.$fecha_fin);
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

    public function ExportControlPago(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportControlPago($r);

        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Control de Pagos y Notas');

        $excel->sheet('Seminarios', function($sheet) use($renturnModel) {
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
                $cell->setValue('CONTROL DE PAGOS Y NOTAS');
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
            $sheet->getStyle('A2:'.$renturnModel['max']."3")->getAlignment()->setWrapText(true);
            $sheet->getStyle('O4:T'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

    public function ExportEvaluaciones(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportEvaluaciones($r);

        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('EVALUACIONES');

        $excel->sheet('Seminarios', function($sheet) use($renturnModel) {
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
                $cell->setValue('DETALLE DE LAS EVALUACIONES');
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
            $sheet->getStyle('A2:'.$renturnModel['max']."3")->getAlignment()->setWrapText(true);
            $sheet->getStyle('O4:T'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

}
