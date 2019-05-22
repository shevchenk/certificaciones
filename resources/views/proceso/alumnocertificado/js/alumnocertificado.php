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
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalEntrega').on('shown.bs.modal', function (event) {
        $('#ModalEntregaForm #txt_fecha_entrega,#ModalEntregaForm #txt_comentario_entrega').val('');
    });

    $('#ModalEntrega').on('hidden.bs.modal', function (event) {
        $("#ModalEntregaForm input[type='hidden']").not('.mant').remove();
    });
});

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

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
                //'<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
                '<td><a id="btn_'+r.persona_id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verCursos(HTMLCargaCurso, '+r.persona_id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
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
    seminario=$("#tr"+id+" .curso").text();
    fecha_seminario=$("#tr"+id+" .fecha").text();


    $("#ModalEntregaForm #txt_seminario").val( seminario );
    $("#ModalEntregaForm #txt_fecha_seminario").val( fecha_seminario );
    $("#ModalEntregaForm #txt_persona_id").val( persona_id );
    $("#ModalEntregaForm #txt_id").val( id );
    $("#ModalEntregaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $('#ModalEntrega').modal('show');
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

        html+="<td><a class='btn btn-lg btn-dropbox' href='ReportDinamic/Proceso.AlumnoPR@DescargarCertificado?id="+r.id+"' target='__blank'>( "+r.validadescarga+" )<i class='fa fa-download'></i></a></td>";
        if( $.trim(r.fecha_entrega) !='' ){
            html+='<td><textarea rows=4 class="form-control" disabled>'+r.comentario_entrega+'</textarea></td>';
        }
        else{
            html+='<td><a class="btn btn-success btn-lg" onClick="ConfirmarEntrega('+r.id+','+PersonaIdG+')"><i class="fa fa-save fa-lg"></i> </a></td>';
        }
        html+="</tr>";
    });

    $("#tb_matricula").html(html); 
};

btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
}

DescargarCertificado=function(id){
    AjaxEspecialidad.DescargarCertificado();
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEntregaForm #txt_fecha_entrega").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Fecha que se entregó el certificado',4000);
    }
    /*else if( $.trim( $("#ModalEntregaForm #txt_comentario_entrega").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese comentario de la entrega del certificado',4000);
    }*/
    return r;
}

RegistrarEntrega=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.RegistrarEntrega(HTMLRegistrarEntrega);
    }
}

HTMLRegistrarEntrega=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEntrega').modal('hide');
        AjaxEspecialidad.verCursos(HTMLCargaCurso, PersonaIdG);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
