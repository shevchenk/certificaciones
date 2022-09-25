<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Models\Mantenimiento\Persona;
use App\Mail\EmailSend;
use DB;
use Mail;

class ApiPro extends Model
{
    protected   $table = 'personas';

    public static function ObtenerPersona($r)
    {
        $key=   DB::table('personas')
                ->select('paterno','materno','nombre','sexo','email','telefono'
                    ,'celular',DB::raw('IFNULL(fecha_nacimiento,"") AS fecha_nacimiento')
                )
                ->where('dni',$r['dni'])
                ->first();
        return $key;
    }

    public static function curl($url, $data=array(), $tipo = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        if( $tipo != 'GET' ){
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    public static function getIPCliente()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
            return $_SERVER["HTTP_X_FORWARDED"];
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
            return $_SERVER["HTTP_FORWARDED_FOR"];
        elseif (isset($_SERVER["HTTP_FORWARDED"]))
            return $_SERVER["HTTP_FORWARDED"];
        else
            return $_SERVER["REMOTE_ADDR"];
    }
}
