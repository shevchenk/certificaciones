<?php
namespace App\Models\Proceso;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB; //BD

class Visita extends Model
{
    protected   $table = 'visitas';

    public static function CargarVisita($r)
    {
        $result=array();

        return $result;
    }
    // --
}
