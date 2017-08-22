<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class TipoParticipante extends Model
{
    protected   $table = 'mat_tipos_participantes';
     
    
        public static function ListTipoParticipante($r)
    {
        $sql=TipoParticipante::select('id','tipo_participante','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('tipo_participante','asc')->get();
        return $result;
    }
}
