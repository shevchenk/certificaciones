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
            $renturnModel = Seminario::runLoadSeminario($r);
            $return['rst'] = 1;
            $return['data'] = $renturnModel;
            $return['msj'] = "No hay registros aún";
            return response()->json($return);
        }
    }

    public function ExportSeminario(Request $r )
    {
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

            $sheet->getStyle('M4:Q'.$count)->getAlignment()->setWrapText(true);
            
            $sheet->setBorder('A3:'.$renturnModel['max'].$count, 'thin');

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

}
