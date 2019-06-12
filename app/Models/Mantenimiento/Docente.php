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
        DB::beginTransaction();
        if( $r->has('id') AND trim($r->id)>'0' ){
            $docente = Docente::find($r->id);
            $docente->persona_id_updated_at=Auth::user()->id;
        }
        else{
            $docente = new Docente;
            $docente->persona_id= $r->persona_id;
            $docente->persona_id_created_at=Auth::user()->id;
        }
        $docente->estado = trim( $r->estadof );
        $docente->save();

        $privilegio=DB::table('personas_privilegios_sucursales AS pps')
                    ->where('persona_id',$docente->persona_id)
                    ->where('privilegio_id','20')
                    ->first();
        if( isset($privilegio->id) ){
            $pps=PersonaPrivilegioSucursal::find($privilegio->id);
            $pps->estado= trim($r->estadof);
            $pps->persona_id_updated_at=Auth::user()->id;
            $pps->save();
        }
        elseif( !isset($privilegio->id) AND trim($r->estadof)==1 ){
            $pps=new PersonaPrivilegioSucursal;
            $pps->persona_id=$docente->persona_id;
            $pps->privilegio_id='20';
            $pps->estado= '1';
            $pps->persona_id_created_at=Auth::user()->id;
            $pps->save();
        }
        DB::commit();
    }

    public static function runNew($r)
    {

        $docente = new Docente;
        $docente->persona_id = trim( $r->persona_id );
        $docente->estado = trim( $r->estado );
        $docente->persona_id_created_at=Auth::user()->id;
        $docente->save();
    }

    public static function runNewPersona($r)
    {
        $return['rst'] = 1;
        $return['msj'] = 'Registro realizado';
        $valpersona= DB::table('personas')
                    ->where('dni',trim($r->dni))
                    ->first();
        if( !isset($valpersona->id) ){
            DB::beginTransaction();
            $persona_id = Auth::user()->id;
            $persona = new Persona;
            $persona->paterno = trim( $r->paterno );
            $persona->materno = trim( $r->materno );
            $persona->nombre = trim( $r->nombre );
            $persona->dni = trim( $r->dni );
            $persona->sexo = trim( $r->sexo );
            $persona->email = trim( $r->email );
            if(trim( $r->password )!=''){
            $persona->password=bcrypt($r->password);}
            
            $persona->telefono = trim( $r->telefono );
            $persona->celular = trim( $r->celular );

            if(trim( $r->fecha_nacimiento )!='') 
            {
            $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
            }
            else
            {
            $persona->fecha_nacimiento = null;
            }

            $persona->estado = '1';
            $persona->persona_id_created_at=$persona_id;
            $persona->save();

            $docente = new Docente;
            $docente->persona_id = trim( $persona->id );
            $docente->estado = trim( $r->estado );
            $docente->persona_id_created_at=Auth::user()->id;
            $docente->save();

            $pps=new PersonaPrivilegioSucursal;
            $pps->persona_id=$docente->persona_id;
            $pps->privilegio_id='20';
            $pps->estado= '1';
            $pps->persona_id_created_at=Auth::user()->id;
            $pps->save();

            DB::commit();
        }
        else{
            $return['rst'] = '2';
            $return['msj'] = 'DNI existente, busque la persona y activar como docente';
        }
        return $return;
    }

    public static function runEdit($r)
    {
        $docente = Docente::find($r->id);
        $docente->persona_id = trim( $r->persona_id );
        $docente->estado = trim( $r->estado );
        $docente->persona_id_updated_at=Auth::user()->id;
        $docente->save();
    }

    public static function runEditPersona($r)
    {
        $return['rst'] = 1;
        $return['msj'] = 'Registro Actualizado';
        $valpersona= DB::table('personas')
                    ->where('dni',trim($r->dni))
                    ->where('id','!=',$r->persona_id)
                    ->first();
        if( !isset($valpersona->id) ){
            $persona_id = Auth::user()->id;
            $persona = Persona::find($r->persona_id);
            $persona->paterno = trim( $r->paterno );
            $persona->materno = trim( $r->materno );
            $persona->nombre = trim( $r->nombre );
            $persona->dni = trim( $r->dni );
            $persona->sexo = trim( $r->sexo );
            $persona->email = trim( $r->email );
            if(trim( $r->password )!=''){
            $persona->password=bcrypt($r->password);}
            
            $persona->telefono = trim( $r->telefono );
            $persona->celular = trim( $r->celular );

            if(trim( $r->fecha_nacimiento )!='') 
            {
            $persona->fecha_nacimiento = trim( $r->fecha_nacimiento );
            }
            else
            {
            $persona->fecha_nacimiento = null;
            }

            $persona->persona_id_updated_at=$persona_id;
            $persona->save();

            $return['rst'] = 1;
            $return['msj'] = 'Registro actualizado';
        }
        else{
            $return['rst'] = '2';
            $return['msj'] = 'DNI existe en otra persona';
        }
        return $return;
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
        $result = $sql->orderBy('docente','asc')->paginate(10);
        return $result;
    }

    public static function runLoadDocente($r)
    {
        $sql=DB::table('personas AS p')
            ->leftJoin('mat_docentes as d','d.persona_id','=','p.id')
            ->select('d.id','p.id AS persona_id','p.dni','p.paterno','p.materno','p.nombre',
                'd.estado','p.email','p.celular','p.sexo','p.telefono',
                DB::raw('IFNULL(p.fecha_nacimiento,"") as fecha_nacimiento')
            )
            ->where( 
                function($query) use ($r){
                    if( $r->has("dni") ){
                        $dni=trim($r->dni);
                        if( $dni !='' ){
                            $query->where('p.dni','like','%'.$dni.'%');
                        }
                    }
                    if( $r->has("paterno") ){
                        $paterno=trim($r->paterno);
                        if( $paterno !='' ){
                            $query->where('p.paterno','like','%'.$paterno.'%');
                        }
                    }
                    if( $r->has("materno") ){
                        $materno=trim($r->materno);
                        if( $materno !='' ){
                            $query->where('p.materno','like','%'.$materno.'%');
                        }
                    }
                    if( $r->has("nombre") ){
                        $nombre=trim($r->nombre);
                        if( $nombre !='' ){
                            $query->where('p.nombre','like','%'.$nombre.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !=''  AND $estado==0 ){
                            $query->whereNull('d.estado');
                        }
                        elseif( $estado !=''  AND $estado==1 ){
                            $query->where('d.estado','1');
                        }
                    }
                }
            );
        $result = $sql->orderBy('d.estado','desc')->orderBy('p.paterno','asc')->paginate(10);
        return $result;
    }
    

}
