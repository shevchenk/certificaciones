<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class Especialidad extends Model
{
    protected   $table = 'mat_especialidades';

    public static function runLoad($r)
    {

        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("especialidad") ){
                        $especialidad=trim($r->especialidad);
                        if( $especialidad !='' ){
                            $query->where('especialidad','like','%'.$especialidad.'%');
                        }
                    }
                    if( $r->has("certificado_especialidad") ){
                        $certificado_especialidad=trim($r->certificado_especialidad);
                        if( $certificado_especialidad !='' ){
                            $query->where('certificado_especialidad','like','%'.$certificado_especialidad.'%');
                        }
                    }

                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('especialidad','asc')->paginate(10);
        return $result;
    }


    public static function runEditStatus($r)
    {
        $especialidade = Auth::user()->id;
        $especialidad = Especialidad::find($r->id);
        $especialidad->estado = trim( $r->estadof );
        $especialidad->persona_id_updated_at=$especialidade;
        $especialidad->save();
    }

    public static function runNew($r)
    {
        $especialidade = Auth::user()->id;
        $especialidad = new Especialidad;
        $especialidad->especialidad = trim( $r->especialidad );
        $especialidad->certificado_especialidad = trim( $r->certificado_especialidad );      
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_created_at=$especialidade;
        $especialidad->save();
    }

    public static function runEdit($r)
    {
        $especialidade = Auth::user()->id;
        $especialidad = Especialidad::find($r->id);
        $especialidad->especialidad = trim( $r->especialidad );
        $especialidad->certificado_especialidad = trim( $r->certificado_especialidad );
        $especialidad->estado = trim( $r->estado );
        $especialidad->persona_id_updated_at=$especialidade;
        $especialidad->save();
    }    

    
        public static function ListEspecialidad($r)
    {
        $sql=Especialidad::select('id','especialidad','certificado_especialidad','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('especialidad','asc')->get();
        return $result;
    }
    

}
