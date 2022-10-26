<?php
namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class PDFRE extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //Esto debe activarse cuando estemos con sessión
    } 
    
    public function ExportPrueba(Request $r )
    {
        $data = [
            'titulo' => 'Styde.net',
            'id' => '1565',
            'empresa' => 'Intur Perú',
            'url_logo' => 'img/inscripcion/logo1.png'
        ];

        $pdf = PDF::loadView('reporte.pdf.prueba', $data);
        return $pdf->download('pruebapdf.pdf');
    }
    
}
