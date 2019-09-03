<?php
// Ruta para Servicio Rest de carga de Alumnos.
//Route::post('alumnosws', 'Proceso\ServicioCargaPR@index');
//Route::match(['post'], 'input', 'Proceso\ServicioCargaPR@index');
Route::resource('alumnosws', 'Proceso\ServicioCargaPR',
                ['only' => ['index', 'store', 'update', 'destroy', 'show']]);
Route::resource('apiaula', 'Proceso\ApiPR',
    ['only' => ['index', 'store', 'update', 'destroy', 'show']]
);
Route::resource('apifc', 'Proceso\ApiPR',
    ['only' => ['index', 'store', 'update', 'destroy', 'show']]
);


//Route::get('/email/{ruta}','SecureAccess\PersonaSA@Menu');
use App\Mail\EmailSend;
Route::get('/mail/{email}', function($email){
    //$email='jorgeshevchenk@gmail.com';
        $emailseguimiento='jorgeshevchenk1988@gmail.com';
        $texto='.::Inscripción de Seminarios::.';
        $parametros=array(
            'id'=>'123',
            'subject'=>$texto,
        );
        //try{
        

	Mail::to($email)
        ->cc([$emailseguimiento])
        ->send( new EmailSend($parametros) );

	var_dump(Mail::failures());

    //var_dump($r);
    echo "Mensaje Enviado :V";
});

// --

Route::get('/', function () {
    return view('secureaccess.login');
});

Route::get('/salir','SecureAccess\PersonaSA@logout');

Route::get(
    '/{ruta}', function ($ruta) {
        if( session()->has('dni') && session()->has('menu') 
            && session()->has('opciones') 
        ){
            if( $ruta=='secureaccess.inicio' AND Auth::user()->privilegio_id==14 ){
                return redirect('proceso.alumnocurso.alumnocurso');
            }
        
            $valores['valida_ruta_url'] = $ruta;
            $valores['menu'] = session('menu');
            $valores['dni'] = session('dni');
            $valores['aula'] = session('aula');
            $valores['privilegios'] = session('privilegios');
            $valores['empresas'] = session('empresas');
            $valores['activaraula'] = session('activaraula');
            
            if( strpos( session('opciones'),$ruta )!==false 
                || $ruta=='secureaccess.inicio'
                || $ruta=='secureaccess.myself' ){
                return view($ruta)->with($valores);
            }
            else{
                return redirect('secureaccess.inicio');
            }
        }
        else{
            return redirect('/');
        }
    }
);

Route::get('/ReportDinamic/{ruta}','SecureAccess\PersonaSA@Menu');
Route::post('/AjaxDinamic/{ruta}','SecureAccess\PersonaSA@Menu');
