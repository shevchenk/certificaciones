<?php

namespace App\Models\BasicManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class Empresa extends Model
{
    protected   $table = 'empresas';

    public static function runEditStatus($r)
    {
        $empresas = Auth::user()->id;
        $empresa = Empresa::find($r->id);
        $empresa->estado = trim( $r->estadof );
        $empresa->persona_id_updated_at=$empresas;
        $empresa->save();
    }

    public static function runNew($r)
    {
        $empresas = Auth::user()->id;
        $empresa = new Empresa;
        $empresa->persona_id = trim( $r->persona_id );
        $empresa->razon_social = trim( $r->razon_social );
        $empresa->ruc = trim( $r->ruc );
        $empresa->nombre_comercial = trim( $r->nombre_comercial );
        $empresa->direccion_fiscal = trim( $r->direccion_fiscal );
        $empresa->telefono = trim( $r->telefono );
        $empresa->celular = trim( $r->celular );
        $empresa->email = trim( $r->email );
        $empresa->estado = trim( $r->estado );
        $empresa->persona_id_created_at=$empresas;
        if(trim($r->imagen_nombre)!='')
        {
            $empresa->foto=$r->imagen_nombre;
            $este = new Empresa;
            $url = "img/empres/".$r->imagen_nombre; 
            $este->fileToFile($r->imagen_archivo, $url);
        }   
        else {
            $empresa->foto=null;    
        }
        $empresa->save();
    }

    public static function runEdit($r)
    {
        $empresas = Auth::user()->id;
        $empresa = Empresa::find($r->id);
        $empresa->persona_id = trim( $r->persona_id );
        $empresa->razon_social = trim( $r->razon_social );
        $empresa->ruc = trim( $r->ruc );
        $empresa->nombre_comercial = trim( $r->nombre_comercial );
        $empresa->direccion_fiscal = trim( $r->direccion_fiscal );
        $empresa->telefono = trim( $r->telefono );
        $empresa->celular = trim( $r->celular );
        $empresa->email = trim( $r->email );
        $empresa->estado = trim( $r->estado );
        $empresa->persona_id_updated_at=$empresas;
        if(trim($r->imagen_nombre)!=''){
            $empresa->foto=$r->imagen_nombre;
        }else {
            $empresa->foto=null;    
        }
        if(trim($r->imagen_archivo)!=''){
            $este = new Empresa;
            $url = "img/empres/".$r->imagen_nombre; 
            $este->fileToFile($r->imagen_archivo, $url);
        }
        $empresa->save();
    }

    public static function runLoad($r)
    {
        $sql=Empresa::select('empresas.id','empresas.persona_id','empresas.ruc','empresas.razon_social','empresas.nombre_comercial',
                             'empresas.direccion_fiscal','empresas.telefono','empresas.celular','empresas.email','empresas.foto',
                            'empresas.estado','personas.paterno','personas.materno','personas.nombre')
             ->join('personas','empresas.persona_id','=','personas.id')
             ->where( 
                function($query) use ($r){
                      if( $r->has("persona") ){
                        $persona=trim($r->persona);
                        if( $persona !='' ){
                            $query->whereRaw('CONCAT_WS(" ",personas.paterno,personas.materno,personas.nombre ) like "%'.$persona.'%"');
                        }
                    }
                    if( $r->has("razon_social") ){
                        $razon_social=trim($r->razon_social);
                        if( $razon_social !='' ){
                            $query->where('empresas.razon_social','like','%'.$razon_social.'%');
                        }
                    }
                    if( $r->has("ruc") ){
                        $ruc=trim($r->ruc);
                        if( $ruc !='' ){
                            $query->where('empresas.ruc','like','%'.$ruc.'%');
                        }
                    }
                    if( $r->has("nombre_comercial") ){
                        $nombre_comercial=trim($r->nombre_comercial);
                        if( $nombre_comercial !='' ){
                            $query->where('empresas.nombre_comercial','like','%'.$nombre_comercial.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('empresas.estado','like','%'.$estado.'%');
                        }
                    }
                }
            );
        $result = $sql->orderBy('persona_id','asc')->get();
        return $result;
    }

     public function fileToFile($file, $url)
    {
        if ( !is_dir('img') ) {
            mkdir('img',0777);
        }
        if ( !is_dir('img/empres') ) {
            mkdir('img/empres',0777);
        }
        list($type, $file) = explode(';', $file);
        list(, $type) = explode('/', $type);
        if ($type=='jpeg') $type='jpg';
        if (strpos($type,'document')!==False) $type='docx';
        if (strpos($type, 'sheet') !== False) $type='xlsx';
        if (strpos($type, 'pdf') !== False) $type='pdf';
        if ($type=='plain') $type='txt';
        list(, $file)      = explode(',', $file);
        $file = base64_decode($file);
        file_put_contents($url , $file);
        return $url. $type;
    }
 
}
