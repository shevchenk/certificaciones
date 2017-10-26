<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var BandejaG={  id:0,
                curso:"",
                certificado_curso:"",
                nro_pago:"",
                monto_pago:"",
                tipo_curso:0,
                estado:1}; // Datos Globales
$(document).ready(function() {
    $("#ModalBoletaForm #txt_fecha_pago").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        startView:2,
        autoclose: true,
        todayBtn: false
    });

    $('#ModalBoletaForm #spn_fecha_pago').on('click', function(){
        $('#ModalBoletaForm #txt_fecha_pago').focus();
    });

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

});


CambiarEstado=function(id){
    sweetalertG.confirm("Confirmación!", "Confirme su envio a Validacion del Pago del Alumno", function(){
        AjaxBandeja.CambiarEstado(HTMLCambiarEstado,id);
    });
}


HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxBandeja.Cargar(HTMLCargarBandeja);
    }
}


Abrir_archivoboleta=function(id){
    $('#ModalBoletaForm #slct_tipo_pago').val( '1' );
    $('#ModalBoletaForm #txt_monto_pago,#ModalBoletaForm #txt_fecha_pago,#ModalBoletaForm #txt_nro_pago').val( '' );

    $("#ModalBoletaForm").append("<input type='hidden' value='"+id+"' id='id' name='id'>");
}


CambiarEstado_PagoAlumno=function(){ //atendido a solucion
    //alert($('#ModalBoletaForm #id').val());
    CambiarEstado($('#ModalBoletaForm #id').val());
    $("#btnclose").click();
};

HTMLCargarBandeja=function(result){ //INICIO HTML
    var html="";
    $('#TableBandeja').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        boton='<td> <a class="btn btn-success btn-sm" id="'+r.id+'" onClick="Abrir_archivoboleta('+r.id+')" data-estado="'+r.estado+'" class="btn btn-warning" data-toggle="modal" data-target="#ModalBoleta" return false;><i class="fa fa-check fa-lg">Iniciar<br>Pago Alumno</i></a> </td>';
        html+="<tr id='trid_"+r.id+"'>"+
            boton+
            "<td class='ultima_llamada'>"+r.ultima_llamada+"</td>"+
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
