<script type="text/javascript">
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var PersonaIdG=0;

$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });
    $("#TableDatos").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxEspecialidad.Cargar(HTMLCargar);
    AjaxEspecialidad.CargarLlamadaPendiente(HTMLCargarLlamadaPendiente);
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalEntrega').on('shown.bs.modal', function (event) {
        $('#ModalEntregaForm #txt_comentario').val('');
    });

    $('#ModalEntrega').on('hidden.bs.modal', function (event) {
        $("#ModalEntregaForm input[type='hidden']").not('.mant').remove();
        $("#ModalEntregaForm .seminario").show();
    });
});

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='carrera'>"+r.carrera+"</td>"+
                "<td class='email'>"+$.trim(r.email)+"</td>"+
                "<td class='telefono'>"+$.trim(r.telefono)+"</td>"+
                "<td class='celular'>"+$.trim(r.celular)+"</td>";
        html+="<td>"+
                "<input type='hidden' class='empresa' value='"+r.empresa+"'>"+
                "<input type='hidden' class='fuente' value='"+r.fuente+"'>"+
                '<a id="btn_'+r.id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verCursos(HTMLCargaCurso, '+r.id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
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

ConfirmarEntrega=function(id,persona_id){
    paterno=$("#trid_"+persona_id+" .paterno").text();
    materno=$("#trid_"+persona_id+" .materno").text();
    nombre=$("#trid_"+persona_id+" .nombre").text();
    telefono=$("#trid_"+persona_id+" .telefono").text();
    celular=$("#trid_"+persona_id+" .celular").text();
    seminario=$("#tr"+id+" .curso").text();
    fecha_seminario=$("#tr"+id+" .fecha").text();


    $("#ModalEntregaForm #txt_seminario").val( seminario );
    $("#ModalEntregaForm #txt_fecha_seminario").val( fecha_seminario );
    $("#ModalEntregaForm #txt_persona_id").val( persona_id );
    $("#ModalEntregaForm #txt_matricula_detalle_id").val( id );
    $("#ModalEntregaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $("#ModalEntregaForm #txt_celular").val( telefono +' / '+celular );
    AjaxEspecialidad.CargarLlamada(HTMLCargarLlamada);
    $('#ModalEntrega').modal('show');
}

ConfirmarEntregaPersonal=function(persona_id){
    paterno=$("#trid_"+persona_id+" .paterno").text();
    materno=$("#trid_"+persona_id+" .materno").text();
    nombre=$("#trid_"+persona_id+" .nombre").text();
    telefono=$("#trid_"+persona_id+" .telefono").text();
    celular=$("#trid_"+persona_id+" .celular").text();
    $("#ModalEntregaForm .seminario").hide();

    $("#ModalEntregaForm #txt_persona_id").val( persona_id );
    $("#ModalEntregaForm #txt_matricula_detalle_id").val( '' );
    $("#ModalEntregaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $("#ModalEntregaForm #txt_celular").val( telefono +' / '+celular );
    AjaxEspecialidad.CargarLlamada(HTMLCargarLlamada);
    $('#ModalEntrega').modal('show');
}

ConfirmarEntregaPendiente=function(id,matricula_detalle_id,persona_id){
    persona=$("#trp_"+id+" .persona").text();
    telefono=$("#trp_"+id+" .telefono").val();
    celular=$("#trp_"+id+" .celular").val();

    if( matricula_detalle_id>0 ){
        seminario=$("#trp_"+id+" .curso").text();
        fecha_seminario=$("#trp_"+id+" .fecha").val();
        $("#ModalEntregaForm #txt_seminario").val( seminario );
        $("#ModalEntregaForm #txt_fecha_seminario").val( fecha_seminario );
    }
    else{
        $("#ModalEntregaForm .seminario").hide();
    }

    $("#ModalEntregaForm #txt_persona_id").val( persona_id );
    $("#ModalEntregaForm #txt_matricula_detalle_id").val( matricula_detalle_id );
    $("#ModalEntregaForm #txt_alumno").val( persona );
    $("#ModalEntregaForm #txt_celular").val( telefono +' / '+celular );
    AjaxEspecialidad.CargarLlamada(HTMLCargarLlamada);
    $('#ModalEntrega').modal('show');
}

HTMLCargarLlamada=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+="<tr>";
        html+="<td>"+r.fecha_registro+"</td>";
        html+="<td>"+r.comentario+"</td>";
        html+="<td>"+$.trim(r.fecha_respuesta)+"</td>";
        html+="<td>"+$.trim(r.respuesta)+"</td>";
        html+="</tr>";
    });

    $("#tb_llamada").html(html); 
}

HTMLCargarLlamadaPendiente=function(result){
    var html='';
    $.each(result.data,function(index,r){
        tipo_registro=r.seminario;
        if( $.trim(r.matricula_detalle_id)=='' ){
            tipo_registro='Personal';
            r.matricula_detalle_id='';
        }

        html+="<tr id='trp_"+r.id+"'>";
        html+="<td>"+r.dni+"</td>";
        html+="<td class='persona'>"+r.paterno+' '+r.materno+', '+r.nombre+"</td>";
        html+="<td class='curso'>"+tipo_registro+"</td>";
        html+="<td>"+r.fecha_registro+"</td>";
        html+="<td>"+r.comentario+"</td>";
        html+="<td>"+$.trim(r.fecha_respuesta)+"</td>";
        html+="<td>"+$.trim(r.respuesta)+
            "<input type='hidden' class='fecha' value='"+$.trim(r.fecha_seminario)+"'>"+
            "<input type='hidden' class='telefono' value='"+$.trim(r.telefono)+"'>"+
            "<input type='hidden' class='celular' value='"+$.trim(r.celular)+"'>"+
            "</td>";
        html+='<td><a class="btn btn-success btn-lg" onClick="ConfirmarEntregaPendiente('+r.id+',\''+r.matricula_detalle_id+'\','+r.persona_id+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
        html+="</tr>";
    });

    $("#tb_llamada_pendiente").html(html); 
}

// PROCESOS NUEVOS
HTMLCargaCurso=function(result){ //INICIO HTML
    var html="";
    //$('#TableDatos').DataTable().destroy();

    $.each(result.data,function(index,r){
        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.modalidad+"</td>";
        html+="<td class='curso'>"+r.curso+"</td>";
        html+="<td>"+r.profesor+"</td>";
        html+="<td class='fecha'>"+r.fecha+"</td>";
        html+="<td>"+r.horario+"</td>";
        html+="<td>"+r.sucursal+"</td>";
        html+='<td><a class="btn btn-success btn-lg" onClick="ConfirmarEntrega('+r.id+','+PersonaIdG+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
        html+="</tr>";
    });

    $("#tb_matricula").html(html); 
};

btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEntregaForm #txt_comentario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese comentario de la llamada',4000);
    }
    return r;
}

RegistrarEntrega=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.RegistrarLlamada(HTMLRegistrarEntrega);
    }
}

CerrarLlamada=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.CerrarLlamada(HTMLRegistrarEntrega);
    }
}

HTMLRegistrarEntrega=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,5500);
        $('#ModalEntrega').modal('hide');
        AjaxEspecialidad.CargarLlamadaPendiente(HTMLCargarLlamadaPendiente);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
