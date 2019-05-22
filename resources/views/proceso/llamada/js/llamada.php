<script type="text/javascript">
var PersonaIdG=0;
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EspecialidadG={id:0,
    especialidad:"",
    certificado_especialidad:"",
    curso_id:"",
    estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableDatos").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd hh:ii:00",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:0,
        autoclose: true,
        todayBtn: false
    });
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });
    var fecha='<?php echo date("Y-m-d H:i:s"); ?>';
    $('#ModalLlamadaForm #txt_fecha').val(fecha);
    AjaxEspecialidad.Cargar(HTMLCargar);
    AjaxEspecialidad.ListarTeleoperadora(SlctListarTeleoperadora);
    AjaxEspecialidad.ListarTipoLlamada(SlctListarTipoLlamada);

    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalLlamada').on('shown.bs.modal', function (event) {
        AjaxEspecialidad.ObtenerHora(CargarHora);
        $('#ModalLlamadaForm #txt_fechas,#ModalLlamadaForm #txt_comentario').val('');
        $('#ModalLlamadaForm #slct_tipo_llamada,#ModalLlamadaForm #slct_detalle_tipo_llamada').selectpicker('val','');
        ActivarComentario();
    });

    $('#ModalLlamada').on('hidden.bs.modal', function (event) {
        $("#ModalLlamadaForm input[type='hidden']").not('.mant').remove();
    });
});

CargarHora=function(result){
    $('#ModalLlamadaForm #txt_fecha').val(result.hora);
}

SlctListarTeleoperadora=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.trabajador+"</option>";
    });
    $("#ModalLlamadaForm #slct_teleoperadora").html(html); 
    $("#ModalLlamadaForm #slct_teleoperadora").selectpicker('refresh');

};

SlctListarTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option data-obs='"+r.obs+"' value="+r.id+">"+r.tipo_llamada+"</option>";
    });
    $("#ModalLlamadaForm #slct_tipo_llamada").html(html); 
    $("#ModalLlamadaForm #slct_tipo_llamada").selectpicker('refresh');

};

SlctListarSubTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_llamada_sub+"</option>";
    });
    $("#ModalLlamadaForm #slct_sub_tipo_llamada").html(html); 
    $("#ModalLlamadaForm #slct_sub_tipo_llamada").selectpicker('refresh');
};

SlctListarDetalleTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_llamada_sub_detalle+"</option>";
    });
    $("#ModalLlamadaForm #slct_detalle_tipo_llamada").html(html); 
    $("#ModalLlamadaForm #slct_detalle_tipo_llamada").selectpicker('refresh');
};

ActivarComentario=function(){
    codigo=$("#slct_tipo_llamada option:selected").attr('data-obs');
    id=$("#slct_tipo_llamada").val();
    $('.tipo1, .tipo2').css('display','none');
    $('#ModalLlamadaForm #txt_comentario').removeAttr('disabled');
    if( codigo==0 ){
        $('#ModalLlamadaForm #txt_comentario').attr('disabled','true');
    }
    else if(codigo==1 || codigo==2){
        $('.tipo1').css('display','block');
        $('.fechadinamica').text('Fecha a Volver a Llamar');
        if( codigo==1 ){
            $('.fechadinamica').text('Fecha a Inscribirse');
        }
    }
    else if(codigo==3){
        $('.tipo2').css('display','block');
        AjaxEspecialidad.ListarSubTipoLlamada(SlctListarSubTipoLlamada,id);
    }
}

ActivarDetalle=function(){
    id=$("#slct_sub_tipo_llamada").val();
    AjaxEspecialidad.ListarDetalleTipoLlamada(SlctListarDetalleTipoLlamada,id*1);
}

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.persona_id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='email'>"+r.email+"</td>"+
                "<td class='telefono'>"+r.telefono+"</td>"+
                "<td class='celular'>"+r.celular+"</td>";
                //"<td>";
                //"<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+=""+
                '<td><a class="btn btn-primary btn-sm" onClick="AbrirLlamada('+r.persona_id+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
        html+="</tr>";
    });//FIN FUNCTION

    $("#TableDatos tbody").html(html); 
    $("#TableDatos").DataTable({ //INICIO DATATABLE
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthMenu": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableDatos_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargar','AjaxEspecialidad',result.data,'#TableDatos_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

AbrirLlamada=function(id){
    PersonaIdG=id;
    paterno=$("#trid_"+id+" .paterno").text();
    materno=$("#trid_"+id+" .materno").text();
    nombre=$("#trid_"+id+" .nombre").text();
    $("#ModalLlamadaForm #txt_persona_id").val( id );
    $("#ModalLlamadaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $('#ModalLlamada').modal('show');
}

ValidaForm=function(){
    var r=true;
    codigo=$("#slct_tipo_llamada option:selected").attr('data-obs');
    if( $.trim( $("#ModalLlamadaForm #slct_teleoperadora").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Teleoperador(a)',4000);
    }
    else if( $.trim( $("#ModalLlamadaForm #txt_fecha").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Fecha de Llamada',4000);
    }
    else if( $.trim( $("#ModalLlamadaForm #slct_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Tipo de Llamada',4000);
    }
    else if( (codigo==1 || codigo==2) && $.trim( $("#ModalLlamadaForm #txt_fechas").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione '+$('.fechadinamica').text(),4000);
    }
    else if( codigo==3 && $.trim( $("#ModalLlamadaForm #slct_sub_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sub Tipo de Llamada',4000);
    }
    else if( codigo==3 && $.trim( $("#ModalLlamadaForm #slct_detalle_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Detalle Tipo de Llamada',4000);
    }
    else if( codigo>0  && $.trim( $("#ModalLlamadaForm #txt_comentario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Comentario',4000);
    }
    return r;
}

RegistrarLlamada=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.RegistrarLlamada(HTMLRegistrarLlamada);
    }
}

HTMLRegistrarLlamada=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalLlamada').modal('hide');
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
</script>


