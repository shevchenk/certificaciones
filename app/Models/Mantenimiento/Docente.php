<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Docente extends Model
{
    protected   $table = 'mat_docentes';

    
    public static function runEditStatus($r)
    {
        $docente = Docente::find($r->id);
        $docente->estado = trim( $r->estadof );
        $docente->persona_id_updated_at=Auth::user()->id;
        $docente->save();
    }

    public static function runNew($r)
    {

        $docente = new Docente;
        $docente->persona_id = trim( $r->persona_id );
        $docente->estado = trim( $r->estado );
        $docente->persona_id_created_at=Auth::user()->id;
        $docente->save();
    }
    public static function runEdit($r)
    {

        $docente = Docente::find($r->id);
        $docente->persona_id = trim( $r->persona_id );
        $docente->estado = trim( $r->estado );
        $docente->persona_id_updated_at=Auth::user()->id;
        $docente->save();
    }

    public static function runLoad($r)
    {
        $sql=Docente::select('mat_docentes.id','mat_docentes.persona_id','p.dni',DB::raw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) as docente'),'mat_docentes.estado')
             ->join('personas as p','p.id','=','mat_docentes.persona_id')
             ->where( 
                function($query) use ($r){
                    if( $r->has("docente") ){
                        $docente=trim($r->docente);
                        if( $docente !='' ){
                            $query->whereRaw('CONCAT_WS(" ",p.paterno,p.materno,p.nombre ) like "%'.$docente.'%"');
                        }
                    }
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('mat_docentes.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('mat_docentes.id','asc')->paginate(10);
        return $result;
    }
    

}
