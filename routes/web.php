<?php
Route::get('/', function () {
    return view('secureaccess.login');
});

Route::get('/salir','SecureAccess\PersonaSA@logout');

Route::get(
    '/{ruta}', function ($ruta) {
        if( session()->has('dni') && session()->has('menu') 
            && session()->has('opciones') 
        ){
            $valores['valida_ruta_url'] = $ruta;
            $valores['menu'] = session('menu');

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

Route::post('/AjaxDinamic/{ruta}','SecureAccess\PersonaSA@Menu');

