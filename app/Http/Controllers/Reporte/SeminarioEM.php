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

    public function LoadSeminario2(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runLoadSeminario2($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadControlPago(Request $r )
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
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

    public function LoadPagos(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            $renturnModel = Seminario::runPagos($r);
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

    public function LoadRegistroNota(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runRegistroNota($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function LoadAsesoria(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
                $r['global']=1;
            }
            elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
                $r['vendedor']=1;
            }
            $renturnModel = Seminario::runAsesoria($r);
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
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportControlPago($r);

        
        Excel::create('Control de Pagos', function($excel) use($renturnModel) {

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
                $cell->setValue('CONTROL DE PAGOS');
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
                unset($data[$i]['matricula_detalle_id']);
                unset($data[$i]['archivo_certificado']);
                unset($data[$i]['archivo_pago_matricula']);
                unset($data[$i]['archivo_pago_inscripcion']);
                unset($data[$i]['archivo_pago_promocion']);

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
            $sheet->getStyle('O4:AG'.$count)->getAlignment()->setWrapText(true);
            
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

    public function ExportRegistroNota(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportRegistroNota($r);

        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('REGISTRO DE NOTAS');

        $excel->sheet('REGISTRO DE NOTAS', function($sheet) use($renturnModel) {
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
                $cell->setValue('REGISTRO DE NOTAS');
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

    public function ExportAsesoria(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportAsesoria($r);

        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Asesorias');

        $excel->sheet('Asesorias', function($sheet) use($renturnModel) {
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
                $cell->setValue('HERRAMIENTA DE APOYO A LA GESTIÓN DE ASESORÍA ACADÉMICA');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '24',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:M1');
            $sheet->cells('A1:M1', function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $sheet->setWidth($renturnModel['length']);
            $sheet->setHeight(2, 33.5);
            $sheet->fromArray(array(
                array(''),
                /*array(''),array(''),array(''),array(''),array(''),*/
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
                $data[$i]['id']=$i+1;
                foreach ($renturnModel['cursos'] as $key => $value) {
                    $pintado= "No Inscrito";
                    if( $data[$i]['c'.($key+1)]>0 ){
                        $pintado= "Inscrito";
                        if( $data[$i]['nf'.($key+1)]>0 ){
                            $pintado= "Aprobado";
                        }
                    }
                    $data[$i]['c'.($key+1)]=$pintado;
                    unset($data[$i]['nf'.($key+1)]);
                }
                $pos++;
                $sheet->row( $pos, $data[$i] );
            }

            $pos++;$pos++;$pos++;
            $titcursos=array('Cód','Unidad Académica','','','','Créditos','horas');
            $sheet->row( $pos, $titcursos );
            $sheet->mergeCells("B".$pos.":E".$pos);
            $sheet->cells("A".$pos.":G".$pos, function($cells) {
                $cells->setBorder('solid', 'none', 'none', 'solid');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setBackground("#FFF2CC");
            });

            $sheet->cell("A".$pos, function($cell) {
                    $cell->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '10',
                            'bold'       =>  true
                    ));
            });
            $sheet->cell("B".$pos, function($cell) {
                    $cell->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '10',
                            'bold'       =>  true
                    ));
            });
            $sheet->cell("F".$pos, function($cell) {
                    $cell->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '10',
                            'bold'       =>  true
                    ));
            });
            $sheet->cell("G".$pos, function($cell) {
                    $cell->setFont(array(
                            'family'     => 'Bookman Old Style',
                            'size'       => '10',
                            'bold'       =>  true
                    ));
            });
            $cursos=json_decode(json_encode($renturnModel['cursos']), true);
            for ($i=0; $i<count($cursos); $i++) {
                $pos++;
                $sheet->cell('A'.$pos, "C".($i+1));
                $sheet->cell('B'.$pos, $cursos[$i]['curso']);
                $sheet->mergeCells("B".$pos.":E".$pos);
                $sheet->cell('F'.$pos, "Nro:".$cursos[$i]['credito']);
                $sheet->cell('G'.$pos, "Nro:".$cursos[$i]['hora']);
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

    public function ExportFicha(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        if( $url[count($url)-1]=="reporte.inscrito.inscrito" ){
            $r['global']=1;
        }
        elseif( $url[count($url)-1]=="reporte.seminariot.seminariot" ){
            $r['vendedor']=1;
        }
        $renturnModel = Seminario::runExportFicha($r);
        
        Excel::create('Seminario', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Ficha')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Ficha');

        $excel->sheet('Ficha', function($sheet) use($renturnModel) {
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
                $cell->setValue('REPORTE PARA FICHA INSCRIPCIÓN');
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
            
            $sheet->setAutoSize(array(
                'O','P'
            ));

            $count = $sheet->getHighestRow();
            $sheet->getStyle('A2:'.$renturnModel['max']."2")->getAlignment()->setWrapText(true);
            $sheet->getStyle('O4:T'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A2:'.$renturnModel['max'].$count, 'thin');

        });
        
        })->export('xlsx');
    }

}
