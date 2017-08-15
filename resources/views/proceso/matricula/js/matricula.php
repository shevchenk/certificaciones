<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProgramacionG={id:0,persona_id:0,persona:"",docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",estado:1}; // Datos Globales
$(document).ready(function() {

    CargarSlct(1);
    var responsable='<?php echo Auth::user()->paterno .' '. Auth::user()->materno .' '. Auth::user()->nombre ?>';
    var responsable_id='<?php echo Auth::user()->id ?>';
    var hoy='<?php echo date('Y-m-d') ?>';
    $("#ModalMatriculaForm #txt_responsable").val(responsable);
    $("#ModalMatriculaForm #txt_responsable_id").val(responsable_id);
    $("#ModalMatriculaForm #txt_fecha").val(hoy);
    
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalMatriculaForm #slct_sucursal_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sucursal',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_persona_id").val() )=='' ||  $.trim( $("#ModalMatriculaForm #txt_persona").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_region_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Región',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_provincia_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Provincia',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_distrito_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Distrito',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_programacion_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione al menos una programación',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    ProgramacionG.id='';
    ProgramacionG.persona='';
    ProgramacionG.persona_id='';
    ProgramacionG.docente_id='';
    ProgramacionG.sucursal_id='';
    ProgramacionG.curso_id='';
    ProgramacionG.aula='';
    ProgramacionG.fecha_inicio='';
    ProgramacionG.fecha_final='';
    ProgramacionG.estado='1';
    if( val==0 ){
        ProgramacionG.id=id;
        ProgramacionG.docente_id=$("#TableProgramacion #trid_"+id+" .docente_id").val();
        ProgramacionG.persona_id=$("#TableProgramacion #trid_"+id+" .persona_id").val();
        ProgramacionG.persona=$("#TableProgramacion #trid_"+id+" .persona").text();
        ProgramacionG.sucursal_id=$("#TableProgramacion #trid_"+id+" .sucursal_id").val();
        ProgramacionG.curso_id=$("#TableProgramacion #trid_"+id+" .curso_id").val();
        ProgramacionG.aula=$("#TableProgramacion #trid_"+id+" .aula").text();
        ProgramacionG.fecha_inicio=$("#TableProgramacion #trid_"+id+" .fecha_inicio").text();
        ProgramacionG.fecha_final=$("#TableProgramacion #trid_"+id+" .fecha_final").text();  
        ProgramacionG.estado=$("#TableProgramacion #trid_"+id+" .estado").val();
    }
    $('#ModalProgramacion').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProgramacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

CargarSlct=function(slct){
    if(slct==1){
    AjaxProgramacion.CargarSucursal(SlctCargarSucursal1);
    }
}

SlctCargarSucursal1=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalMatriculaForm #slct_sucursal_id").html(html); 
    $("#ModalMatriculaForm #slct_sucursal_id").selectpicker('refresh');

};

</script>
