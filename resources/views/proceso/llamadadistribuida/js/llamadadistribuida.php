<script type="text/javascript">
var PersonaIdG=0;
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var PersonaG={id:0};
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
    AjaxEspecialidad.ListarTipoLlamada(SlctListarTipoLlamada);
    AjaxEspecialidad.CargarLlamadaPendiente(HTMLCargarLlamadaPendiente);

    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalLlamada').on('shown.bs.modal', function (event) {
        AjaxEspecialidad.ObtenerHora(CargarHora);
        $('#ModalLlamadaForm #txt_fechas,#ModalLlamadaForm #txt_comentario,#ModalLlamadaForm #txt_objecion,#ModalLlamadaForm #txt_pregunta').val('');
        $('#ModalLlamadaForm #slct_tipo_llamada,#ModalLlamadaForm #slct_detalle_tipo_llamada').selectpicker('val','');
        ActivarComentario();
    });

    $('#ModalLlamada').on('hidden.bs.modal', function (event) {
        $("#ModalLlamadaForm input[type='hidden']").not('.mant').remove();
    });
});

HTMLCargarLlamadaPendiente=function(result){
    var html='';
    $.each(result.data,function(index,r){
        success='';
        if( r.fechas==r.hoy ){
            success='success';
        }
        html+="<tr id='llp_"+r.id+"' class='"+success+"'>";
        html+="<td>"+r.dni+"</td>";
        html+="<td class='persona'>"+r.persona+"</td>";

         html+="<td>"+r.fecha_llamada+"</td>";
        html+="<td>"+r.teleoperador+"</td>";
        html+="<td>"+$.trim(r.tipo_llamada)+"</td>";
        html+="<td>"+$.trim(r.fechas)+"</td>";
        html+="<td>"+$.trim(r.comentario)+"</td>";
        html+="<td>"+
                "<input type='hidden' class='telefono' value='"+$.trim(r.telefono)+"'>"+
                "<input type='hidden' class='celular' value='"+$.trim(r.celular)+"'>"+
                "<input type='hidden' class='carrera' value='"+r.carrera+"'>"+
                "<input type='hidden' class='fecha_distribucion' value='"+$.trim(r.fecha_distribucion)+"'>"+
                "<input type='hidden' class='empresa' value='"+r.empresa+"'>"+
                "<input type='hidden' class='fuente' value='"+r.fuente+"'>"+
                "<input type='hidden' class='region' value='"+r.region+"'>"+
                "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
                '<a class="btn btn-primary btn-sm" onClick="AbrirLlamadaPendiente('+r.id+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
        html+="</tr>";
    });

    $("#tb_llamada_pendiente").html(html); 
}

CargarHora=function(result){
    $('#ModalLlamadaForm #txt_fecha').val(result.hora);
}

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
    /*if( codigo==0 ){
        $('#ModalLlamadaForm #txt_comentario').attr('disabled','true');
    }
    else */
    if(codigo==1 || codigo==2){
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
    bgcolor=["","#9BFF04","#F0D720","#FF8D10","#FF8D10","#FF4242"];
    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"' ondblclick='AbrirLlamada("+r.id+");' style='cursor: pointer;'>";
        color=bgcolor[0];
        if($.trim(r.peso)*1>0 && $.trim(r.peso)*1<6){
            color=bgcolor[r.peso];
        }
        else if($.trim(r.peso)*1>5){
            color=bgcolor[5];
        }
        html+="</td>"+
                "<td bgcolor='"+color+"' >"+'<a class="btn btn-primary btn-sm" onClick="AbrirLlamada('+r.id+')"><i class="fa fa-phone fa-lg"></i> </a>'+"</td>"+
                "<td bgcolor='"+color+"' class='tipo_llamada'>"+$.trim(r.tipo_llamada)+"</td>"+
                "<td bgcolor='"+color+"' class='fecha_llamada'>"+$.trim(r.fecha_llamada)+"</td>"+
                "<td class='vendedor'>"+$.trim(r.vendedor)+"</td>"+
                "<td class='fecha_registro'>"+$.trim(r.fecha_registro)+"</td>"+
                "<td class='fecha_distribucion'>"+r.fecha_distribucion+"</td>"+
                "<td bgcolor='"+color+"' class='nombre'>"+ $.trim(r.nombre +" "+ r.paterno +" "+ r.materno) +"</td>"+
                "<td class='celular'>"+$.trim(r.celular)+"</td>"+
                "<td class='email'>"+$.trim(r.email)+"</td>"+
                "<td class='carrera'>"+r.carrera+"</td>"+
                "<td class='fuente'>"+$.trim(r.fuente)+"</td>"+
                "<td bgcolor='"+color+"' class='campaña'>"+r.campana+"</td>"+
                "<td class='region'>"+$.trim(r.region)+"</td>";
                //"<td bgcolor='"+color+"' class='nombre'>"+"</td>"+
                /*"<td class='tipo'>"+$.trim(r.tipo)+"</td>"+
                "<td class='empresa'>"+$.trim(r.empresa)+"</td>"+*/
                //"<td>";
                //"<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+="<td>"+
                "<input type='hidden' class='empresa' value='"+$.trim(r.empresa)+"'>"+
                "<input type='hidden' class='fuente' value='"+$.trim(r.fuente)+"'>"+
                "<input type='hidden' class='dni' value='"+r.dni+"'>"+
                "<input type='hidden' class='matricula' value='"+$.trim(r.matricula)+"'>"+
                "<input type='hidden' class='sexo' value='"+$.trim(r.sexo)+"'>"+
                '<a class="btn btn-primary btn-sm" onClick="AbrirLlamada('+r.id+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
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
    PersonaG.id=id;
    paterno=$("#trid_"+id+" .paterno").text();
    materno=$("#trid_"+id+" .materno").text();
    nombre=$("#trid_"+id+" .nombre").text();
    celular=$("#trid_"+id+" .celular").text();
    region=$("#trid_"+id+" .region").text();
    carrera=$("#trid_"+id+" .carrera").text();
    fecha_distribucion=$("#trid_"+id+" .fecha_distribucion").text();
    empresa=$("#trid_"+id+" .empresa").val();
    fuente=$("#trid_"+id+" .fuente").text();
    matricula=$("#trid_"+id+" .matricula").val();

    $("#ModalLlamadaForm #btnEditPersona").css('display','block');
    if( matricula==1 ){
        $("#ModalLlamadaForm #btnEditPersona").css('display','none');
    }

    $("#ModalLlamadaForm #txt_persona_id").val( id );
    $("#ModalLlamadaForm #txt_alumno").val( nombre +' '+paterno+' '+materno );
    $("#ModalLlamadaForm #txt_celular").val( celular );
    $("#ModalLlamadaForm #txt_carrera").val( carrera );
    $("#ModalLlamadaForm #txt_fecha_distribucion").val( fecha_distribucion );
    $("#ModalLlamadaForm #txt_region").val( region );
    $("#ModalLlamadaForm #txt_fuente").val( fuente );
    AjaxEspecialidad.CargarLlamada(HTMLCargarLlamada);
    AjaxEspecialidad.CargarInfo(HTMLCargarInfo);
    AjaxEspecialidad.CargarMatricula(HTMLCargarMatricula);
    $('#ModalLlamada').modal('show');
}

EditarPersona=function(){
    id= $('#ModalLlamadaForm #txt_persona_id').val();
    paterno=$("#trid_"+id+" .paterno").text();
    materno=$("#trid_"+id+" .materno").text();
    nombre=$("#trid_"+id+" .nombre").text();
    celular=$("#trid_"+id+" .celular").text();
    carrera=$("#trid_"+id+" .carrera").text();
    dni=$("#trid_"+id+" .dni").val();
    sexo=$("#trid_"+id+" .sexo").val();
    email=$("#trid_"+id+" .email").text();

    $('#ModalPersonaForm #txt_paterno').val(paterno);
    $('#ModalPersonaForm #txt_materno').val(materno);
    $('#ModalPersonaForm #txt_nombre').val(nombre);
    $('#ModalPersonaForm #txt_dni').val(dni);
    $('#ModalPersonaForm #slct_sexo').selectpicker('val',sexo);
    $('#ModalPersonaForm #txt_email').val(email);
    $('#ModalPersonaForm #txt_celular').val(celular);
    $('#ModalPersonaForm #txt_carrera').val(carrera);
    $('#ModalPersona').modal('show');
}

ActualizarPersona=function(){
    AjaxEspecialidad.ActualizarPersona(HTMLActualizarPersona);
}

HTMLActualizarPersona=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.Cargar(HTMLCargar);
        paterno=$('#ModalPersonaForm #txt_paterno').val();
        materno=$('#ModalPersonaForm #txt_materno').val();
        nombre=$('#ModalPersonaForm #txt_nombre').val();
        celular=$('#ModalPersonaForm #txt_celular').val()
        carrera=$('#ModalPersonaForm #txt_carrera').val()
        $("#ModalLlamadaForm #txt_alumno").val( nombre +' '+paterno+', '+materno );
        $("#ModalLlamadaForm #txt_celular").val( celular );
        $("#ModalLlamadaForm #txt_carrera").val( carrera );
        $('#ModalPersona').modal('hide');
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

AbrirLlamadaPendiente=function(id){
    PersonaIdG=$("#llp_"+id+" .persona_id").val();
    persona=$("#llp_"+id+" .persona").text();
    celular=$("#llp_"+id+" .celular").val();
    carrera=$("#llp_"+id+" .carrera").val();
    fecha_distribucion=$("#llp_"+id+" .fecha_distribucion").val();
    empresa=$("#llp_"+id+" .empresa").val();
    fuente=$("#llp_"+id+" .fuente").val();
    region=$("#llp_"+id+" .region").val();

    $("#ModalLlamadaForm #txt_persona_id").val( PersonaIdG );
    $("#ModalLlamadaForm #txt_alumno").val( persona );
    $("#ModalLlamadaForm #txt_celular").val( celular );
    $("#ModalLlamadaForm #txt_carrera").val( carrera );
    $("#ModalLlamadaForm #txt_fecha_distribucion").val( fecha_distribucion );
    $("#ModalLlamadaForm #txt_empresa").val( empresa );
    $("#ModalLlamadaForm #txt_fuente").val( fuente );
    $("#ModalLlamadaForm #txt_region").val( region );
    AjaxEspecialidad.CargarLlamada(HTMLCargarLlamada);
    AjaxEspecialidad.CargarInfo(HTMLCargarInfo);
    AjaxEspecialidad.CargarMatricula(HTMLCargarMatricula);
    $('#ModalLlamada').modal('show');
}

HTMLCargarLlamada=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.id+"</td>";
        html+="<td>"+r.fecha_llamada+"</td>";
        html+="<td>"+r.teleoperador+"</td>";
        html+="<td>"+$.trim(r.tipo_llamada)+"</td>";
        html+="<td>"+$.trim(r.fechas)+"</td>";
        html+="<td>"+$.trim(r.comentario)+"</td>";
        html+="</tr>";
    });

    $("#tb_llamada").html(html); 
}

HTMLCargarInfo=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.fecha_info+"</td>";
        html+="<td>"+r.info+"</td>";
        html+="</tr>";
    });

    $("#tb_info").html(html); 
}

HTMLCargarMatricula=function(result){
    var html='';
    var deuda = 'Incompleto';
    
    $.each(result.data,function(index,r){
        deuda = 'Incompleto';
        if( result.deuda[index] == 0 ){
            deuda = "Completo";
        }
        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.id+"</td>";
        html+="<td>"+r.estado_mat+"</td>";
        html+="<td>"+$.trim(r.fecha_estado)+"</td>";
        html+="<td>"+ deuda +"</td>";
        html+="<td>"+r.expediente+"</td>";
        html+="<td>"+$.trim(r.fecha_expediente)+"</td>";
        html+="</tr>";
    });

    $("#tb_matricula").html(html); 
}

ValidaForm=function(){
    var r=true;
    codigo=$("#slct_tipo_llamada option:selected").attr('data-obs');
    if( $.trim( $("#ModalLlamadaForm #txt_fecha").val() )=='' ){
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
    /*else if( codigo>0  && $.trim( $("#ModalLlamadaForm #txt_comentario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Comentario',4000);
    }*/
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
        AjaxEspecialidad.Cargar(HTMLCargar);
        AjaxEspecialidad.CargarLlamadaPendiente(HTMLCargarLlamadaPendiente);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
</script>


