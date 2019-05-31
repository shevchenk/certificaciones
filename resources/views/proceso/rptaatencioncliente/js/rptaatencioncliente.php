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
        $('#ModalEntregaForm #txt_respuesta').val('');
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
   
        html+=""+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='seminario'>"+$.trim(r.seminario)+"</td>"+
                "<td class='fecha_seminario'>"+$.trim(r.fecha_seminario)+"</td>"+
                "<td class='fecha_registro'>"+r.fecha_registro+"</td>"+
                "<td class='comentario'>"+r.comentario+"</td>";
        html+='<td>'+
                "<input type='hidden' class='telefono' value='"+$.trim(r.telefono)+"'>"+
                "<input type='hidden' class='celular' value='"+$.trim(r.celular)+"'>"+
                '<a class="btn btn-success btn-lg" onClick="ConfirmarEntrega('+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
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

ConfirmarEntrega=function(id){
    paterno=$("#trid_"+id+" .paterno").text();
    materno=$("#trid_"+id+" .materno").text();
    nombre=$("#trid_"+id+" .nombre").text();
    seminario=$("#trid_"+id+" .seminario").text();
    telefono=$("#trid_"+id+" .telefono").val();
    celular=$("#trid_"+id+" .celular").val();
    fecha_seminario=$("#trid_"+id+" .fecha_seminario").text();
    fecha_registro=$("#trid_"+id+" .fecha_registro").text();
    comentario=$("#trid_"+id+" .comentario").text();

    if( seminario=='' ){
        $("#ModalEntregaForm .seminario").hide();
    }

    $("#ModalEntregaForm #txt_seminario").val( seminario );
    $("#ModalEntregaForm #txt_fecha_seminario").val( fecha_seminario );
    $("#ModalEntregaForm #txt_fecha_registro").val( fecha_registro );
    $("#ModalEntregaForm #txt_comentario").val( comentario );
    $("#ModalEntregaForm #txt_celular").val( telefono +' / '+celular );
    $("#ModalEntregaForm #txt_id").val( id );
    $("#ModalEntregaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $('#ModalEntrega').modal('show');
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEntregaForm #txt_respuesta").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese respuesta de la llamada',4000);
    }
    return r;
}

RegistrarEntrega=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.ResponderLlamada(HTMLRegistrarEntrega);
    }
}

HTMLRegistrarEntrega=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEntrega').modal('hide');
        AjaxEspecialidad.Cargar(HTMLCargar);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
