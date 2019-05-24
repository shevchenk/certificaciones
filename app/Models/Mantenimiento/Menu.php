<?php

namespace App\Models\Mantenimiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class Menu extends Model
{
    protected   $table = 'menus';

    public static function runEditStatus($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = Menu::find($r->id);
        $certificadoestado->estado = trim( $r->estadof );
        $certificadoestado->persona_id_updated_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runNew($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = new Menu;
        $certificadoestado->menu = trim( $r->menu );
        $certificadoestado->class_icono = trim( $r->class_icono );
        $certificadoestado->estado = trim( $r->estado );
        $certificadoestado->persona_id_created_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runEdit($r)
    {
        $certificadoestadoe = Auth::user()->id;
        $certificadoestado = Menu::find($r->id);
        $certificadoestado->menu = trim( $r->menu );
        $certificadoestado->class_icono = trim( $r->class_icono );
        $certificadoestado->estado = trim( $r->estado );
        $certificadoestado->persona_id_updated_at=$certificadoestadoe;
        $certificadoestado->save();
    }

    public static function runLoad($r)
    {

        $sql=Menu::select('id','menu','class_icono','estado')
            ->where( 
                function($query) use ($r){
                    if( $r->has("menu") ){
                        $menu=trim($r->menu);
                        if( $menu !='' ){
                            $query->where('menu','like','%'.$menu.'%');
                        }
                    }
                    if( $r->has("class_icono") ){
                        $class_icono=trim($r->class_icono);
                        if( $class_icono !='' ){
                            $query->where('class_icono','like','%'.$class_icono.'%');
                        }
                    }
                    if( $r->has("estado") ){
                        $estado=trim($r->estado);
                        if( $estado !='' ){
                            $query->where('estado','=',$estado);
                        }
                    }
                }
            );
        $result = $sql->orderBy('menu','asc')->paginate(10);
        return $result;
    }
    
    public static function ListMenu($r)
    {
        $sql=Menu::select('id','menu','class_icono','estado')
            ->where('estado','=','1');
        $result = $sql->orderBy('menu','asc')->get();
        return $result;
    }
    
    public static function fileToFile($file, $url)
    {
        $urld=explode("/",$url);
        $urlt=array();
        for ($i=0; $i < (count($urld)-1) ; $i++) { 
            array_push($urlt, $urld[$i]);
            $urltexto=implode("/",$urlt);
            if ( !is_dir($urltexto) ) {
                mkdir($urltexto,0777);
            }
        }
        
        list($type, $file) = explode(';', $file);
        list(, $type) = explode('/', $type);
        if ($type=='jpeg') $type='jpg';
        if ($type=='x-icon') $type='ico';
        if (strpos($type,'document')!==False) $type='docx';
        if (strpos($type,'msword')!==False) $type='doc';
        if (strpos($type,'presentation')!==False) $type='pptx';
        if (strpos($type,'powerpoint')!==False) $type='ppt';
        if (strpos($type, 'sheet') !== False) $type='xlsx';
        if (strpos($type, 'excel') !== False) $type='xls';
        if (strpos($type, 'pdf') !== False) $type='pdf';
        if ($type=='plain') $type='txt';
        list(, $file)      = explode(',', $file);
        $file = base64_decode($file);
        file_put_contents($url , $file);
        return $url.$type;
    }

}
