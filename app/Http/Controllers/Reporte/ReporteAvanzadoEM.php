<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reporte\ReporteAvanzado;
use Excel;

class ReporteAvanzadoEM extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    }

    public function LoadIndiceMatriculacion(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            $renturnModel = ReporteAvanzado::runIndiceMatriculacion($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ExportIndiceMatriculacion(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        $renturnModel = ReporteAvanzado::runExportIndiceMatriculacion($r);

        Excel::create('Indice Matriculacion', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('INDICE MATRICULACION');

        $excel->sheet('INDICE MATRICULACION', function($sheet) use($renturnModel) {
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
                $cell->setValue('ÍNDICE DE MATRICULACIÓN PARA GERENCIA');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '24',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:AI1');
            $sheet->cells('A1:AI1', function($cells) {
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
                $data[$i]['ind_sa'] = round($data[$i]['sa'] / 7,2);
                $data[$i]['ind_ud'] = round($data[$i]['ud'] / 7,2);
                $data[$i]['pro_df'] = round($data[$i]['dias_falta'] * $data[$i]['ind_ud'],0);
                $data[$i]['pro_fin'] = $data[$i]['total']*1 + $data[$i]['pro_df']*1;
                $data[$i]['falta_meta'] = $data[$i]['meta_max'] - $data[$i]['pro_fin'];
                if( $data[$i]['falta_meta'] < 0 ){
                    $data[$i]['falta_meta'] = 0;
                }
                $color="#FF4848";
                //color = 'danger';
                if( $data[$i]['pro_fin'] >= $data[$i]['meta_max'] ){
                    $color="#35FF35";
                    //color = 'success';
                }
                else if( $data[$i]['pro_fin'] >= $data[$i]['meta_min'] ){
                    $color="#FFFF48";
                    //color = 'warning';
                }
                $data[$i]['id']=$i+1;
                $pos++;
                $sheet->row( $pos, $data[$i] );
                $sheet->cells('AH'.$pos, function($cells) use ($color) {
                    $cells->setBackground($color);
                });
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

            $totales= array('','','','','','','','Totales:','=SUM(I4:I'.$pos.')','=SUM(J4:J'.$pos.')','=SUM(K4:K'.$pos.')','=SUM(L4:L'.$pos.')'
            ,'=SUM(M4:M'.$pos.')','=SUM(N4:N'.$pos.')','=SUM(O4:O'.$pos.')','=SUM(P4:P'.$pos.')','=SUM(Q4:Q'.$pos.')','=SUM(R4:R'.$pos.')'
            ,'=SUM(S4:S'.$pos.')','=SUM(T4:T'.$pos.')','=SUM(U4:U'.$pos.')','=SUM(V4:V'.$pos.')','=SUM(W4:W'.$pos.')','=SUM(X4:X'.$pos.')'
            ,'=SUM(Y4:Y'.$pos.')','=SUM(Z4:Z'.$pos.')','=SUM(AA4:AA'.$pos.')','','',''
            ,'=SUM(AE4:AE'.$pos.')','=SUM(AF4:AF'.$pos.')','=SUM(AG4:AG'.$pos.')','=SUM(AH4:AH'.$pos.')','=SUM(AI4:AI'.$pos.')');
            $pos = $count+1;
            $sheet->row( $pos, $totales );

            $sheet->cells( 'H'.$pos.':AI'.$pos, function($cells) {
                $cells->setBorder('thin','thin','thin','thin');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setBackground("#E2EFDA");
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
            });
        });
        
        })->export('xlsx');
    }

    public function LoadMedioCaptacion(Request $r )
    {
        if ( $r->ajax() ) {
            $url=explode("/",$_SERVER['HTTP_REFERER']);
            $renturnModel = ReporteAvanzado::runMedioCaptacion($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ExportMedioCaptacion(Request $r )
    {
        $url=explode("/",$_SERVER['HTTP_REFERER']);
        $renturnModel = ReporteAvanzado::runExportMedioCaptacion($r);

        Excel::create('Medio Captación', function($excel) use($renturnModel) {

        $excel->setTitle('Reporte de Seminarios')
              ->setCreator('Jorge Salcedo')
              ->setCompany('JS Soluciones')
              ->setDescription('Medio Captación');

        $excel->sheet('Medio Captación', function($sheet) use($renturnModel) {
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
                $cell->setValue('INSCRIPCIONES DE MEDIOS DE CAPTACIÓN');
                $cell->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '21',
                    'bold'       =>  true
                ));
            });
            $sheet->mergeCells('A1:I1');
            $sheet->cells('A1:I1', function($cells) {
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
            $ode_aux='';
            for ($i=0; $i<count($data); $i++) {
                //unset($data[$i]['ndet']);
                if( $ode_aux!=$data[$i]['ode'] ){
                    $ode_aux=$data[$i]['ode'];
                    $contador=0;
                }
                $contador++;
                $data[$i]['nro'] = $contador;
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

            $totales = $renturnModel['cantempresas'];
            $pos = $count+1;
            $sheet->row( $pos, $totales );

            $sheet->cells( 'C'.$pos.':'.$renturnModel['max'].$pos, function($cells) {
                $cells->setBorder('thin','thin','thin','thin');
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setBackground("#DBDBDB");
                $cells->setFont(array(
                    'family'     => 'Bookman Old Style',
                    'size'       => '10',
                    'bold'       =>  true
                ));
            });
        });
        
        })->export('xlsx');
    }

}
