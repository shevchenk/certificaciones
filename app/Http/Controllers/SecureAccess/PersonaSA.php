<?php
namespace App\Http\Controllers\SecureAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\SecureAccess\Persona;
use Auth;
use DB;

class PersonaSA extends Controller
{
    use AuthenticatesUsers;

    protected $loginView = 'secureaccess.login';

    public function authenticated(Request $r)
    {
        $result['rst']=1;
        $privilegios= Persona::Privilegios();
        $empresas = Persona::ActivarEmpresa();
        $menu = Persona::Menu();
        $valida='m';
        if($menu[0]->cargo==2){
            $valida='p';
        }
        $activaraula = Persona::ValidaActivarAula($valida);
        $aula=Persona::ValidarAula();
        $opciones=array();
        foreach ($menu as $key => $value) {
            array_push($opciones, $value->opciones);
        }
        $opciones=implode("||", $opciones);
        $session= array(
            'menu'=>$menu,
            'opciones'=>$opciones,
            'privilegios'=>$privilegios,
            'empresas'=>$empresas,
            'dni'=>$r->dni,
            'activaraula'=>$activaraula,
            'aula'=>$aula,
        );
        session($session);
        return response()->json($result);
    }

    public function username()
    {
        return "dni";
    }

    public function EditPassword(Request $r)
    {
        if ( $r->ajax() ) {
            if( $r->password == $r->password_confirm ){
                $rs=Persona::runEditPassword($r);
                $return['rst'] = 1;
                $return['msj'] = 'Registro actualizado';
                if( $rs==2 ){
                    $return['rst'] = 2;
                    $return['msj'] = 'Contraseña Actual no válida';
                }
            }
            else{
                $return['rst'] = 2;
                $return['msj'] = 'Contraseña y Contraseña de confirmación no '.
                'son iguales';
            }
            return response()->json($return);
        }
    }

    public function ActualizarEmpresa (Request $r)
    {
        $persona_id= Auth::user()->id;
        $empresa_id= $r->empresa_id;
        DB::table('personas')
        ->where('id', $persona_id)
        ->update(array('empresa_id' => $empresa_id));
        Auth::loginUsingId($persona_id);
        return redirect('secureaccess.inicio');
    }

    public function ActualizarPrivilegio (Request $r)
    {
        $persona_id= Auth::user()->id;
        $privilegio_id= $r->privilegio_id;
        DB::table('personas')
        ->where('id', $persona_id)
        ->update(array('privilegio_id' => $privilegio_id));
        Auth::loginUsingId($persona_id);
        $menu = Persona::Menu();
        $valida='m';
        if($menu[0]->cargo==2){
            $valida='p';
        }
        $activaraula = Persona::ValidaActivarAula($valida);
        $aula=Persona::ValidarAula();
        $opciones=array();
        foreach ($menu as $key => $value) {
            array_push($opciones, $value->opciones);
        }
        $opciones=implode("||", $opciones);
        session([
            'menu' => $menu,
            'opciones' => $opciones,
            'activaraula'=>$activaraula,
            'aula'=>$aula
        ]);
        return redirect('secureaccess.inicio');
    }
}
