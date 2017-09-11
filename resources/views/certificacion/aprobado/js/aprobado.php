<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var BandejaG={  id:0,
                curso:"",
                certificado_curso:"",
                tipo_curso:0,
                estado:1}; // Datos Globales
$(document).ready(function() {

    $("#TableBandeja").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxBandeja.Cargar(HTMLCargarBandeja);
    $("#BandejaForm #TableBandeja select").change(function(){ AjaxBandeja.Cargar(HTMLCargarBandeja); });
    $("#BandejaForm #TableBandeja input").blur(function(){ AjaxBandeja.Cargar(HTMLCargarBandeja); });

    /*$('#ModalBandeja').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalBandejaForm").append("<input type='hidden' value='"+BandejaG.id+"' name='id'>");
        }

        $('#ModalBandejaForm #txt_curso').val( BandejaG.curso );
        $('#ModalBandejaForm #txt_certificado_curso').val( BandejaG.certificado_curso );
        $('#ModalBandejaForm #slct_tipo_curso').val( BandejaG.tipo_curso );
        $('#ModalBandejaForm #slct_estado').val( BandejaG.estado );
        $("#ModalBandeja select").selectpicker('refresh');
        $('#ModalBandejaForm #txt_curso').focus();
    });

    $('#ModalBandeja').on('hidden.bs.modal', function (event) {
        $("ModalBandejaForm input[type='hidden']").not('.mant').remove();
    });*/
});

CambiarEstado=function(id){
    sweetalertG.confirm("Confirmación!", "Confirme su envio a emisión de trámite", function(){
        AjaxBandeja.CambiarEstado(HTMLCambiarEstado,id);
    });
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxBandeja.Cargar(HTMLCargarBandeja);
    }
}

HTMLCargarBandeja=function(result){ //INICIO HTML
    var html="";
    $('#TableBandeja').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        boton='<td> <a class="btn btn-success btn-sm" onClick="CambiarEstado('+r.id+')"><i class="fa fa-check fa-lg">Iniciar<br>Emisión</i></a> </td>';
        html+="<tr id='trid_"+r.id+"'>"+
            boton+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
            "<td class='paterno'>"+r.paterno+"</td>"+
            "<td class='materno'>"+r.materno+"</td>"+
            "<td class='nombre'>"+r.nombre+"</td>"+
            "<td class='tramite'>"+r.tramite+"</td>"+
            "<td class='fecha_ingreso'>"+r.fecha_ingreso+"</td>"+
            "<td class='fecha_tramite'>"+r.fecha_tramite+"</td>"+
            boton;
        html+="</tr>";

    });//FIN FUNCTION

    $("#TableBandeja tbody").html(html); 
    $("#TableBandeja").DataTable({ //INICIO DATATABLE
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
            $('#TableBandeja_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarBandeja','AjaxBandeja',result.data,'#TableBandeja_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
