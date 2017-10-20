<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var BandejaG={  id:0,ode:"",dni:"",nombre:"",tramite:"",fecha_ingreso:'',fecha_tramite:''}; // Datos Globales
$(document).ready(function() {
    $("#TableBandeja").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(1);
    AjaxBandeja.Cargar(HTMLCargarBandeja);
    $("#BandejaForm #TableBandeja select").change(function(){ AjaxBandeja.Cargar(HTMLCargarBandeja); });
    $("#BandejaForm #TableBandeja input").blur(function(){ AjaxBandeja.Cargar(HTMLCargarBandeja); });

    $('#ModalVerpagoalumno').on('shown.bs.modal', function (event) {

        if( AddEdit==1 ){        
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalVerpagoalumnoForm").append("<input type='hidden' value='"+BandejaG.id+"' name='certificado_id'>");
        }
        $('#ModalVerpagoalumnoForm #txt_ode').val( BandejaG.ode );
        $('#ModalVerpagoalumnoForm #txt_dni').val( BandejaG.dni );
        $('#ModalVerpagoalumnoForm #txt_nombre').val( BandejaG.nombre );
        $('#ModalVerpagoalumnoForm #txt_tramite').val( BandejaG.tramite );
        $('#ModalVerpagoalumnoForm #txt_fecha_tramite').val( BandejaG.fecha_tramite );
        $('#ModalVerpagoalumnoForm #txt_fecha_ingreso').val( BandejaG.fecha_ingreso );
    });

    $('#ModalVerpagoalumno').on('hidden.bs.modal', function (event) {
        $("#ModalVerpagoalumnoForm input[type='hidden']").not('.mant').remove();
        $("#ModalVerpagoalumnoForm #slct_estado_contesto").selectpicker('val','');
        $("#ModalVerpagoalumnoForm #slct_contesta_id").selectpicker('val','0');
        $("#ModalVerpagoalumnoForm #txt_observacion").val('');
    });
});

AgregarEditar=function(val,id){
    AddEdit=val;
    BandejaG.id='';
    BandejaG.ode='';
    BandejaG.dni='';
    BandejaG.nombre='';
    BandejaG.fecha_tramite='';
    BandejaG.fecha_ingreso='';
    if( val==0 ){
        AjaxBandeja.CargarDetalle(HTMLCargarBandejaDetalle,id);
        BandejaG.id=id;
        BandejaG.ode=$("#TableBandeja #trid_"+id+" .sucursal").text();
        BandejaG.dni=$("#TableBandeja #trid_"+id+" .dni").text();
        var paterno=$("#TableBandeja #trid_"+id+" .paterno").text();
        var materno=$("#TableBandeja #trid_"+id+" .materno").text();
        var nombre=$("#TableBandeja #trid_"+id+" .nombre").text();
        BandejaG.nombre=paterno+' '+materno+' '+nombre;
        BandejaG.tramite=$("#TableBandeja #trid_"+id+" .tramite").text();
        BandejaG.fecha_tramite=$("#TableBandeja #trid_"+id+" .fecha_ingreso").val();
        BandejaG.fecha_ingreso=$("#TableBandeja #trid_"+id+" .fecha_tramite").val();
    }
    $('#ModalVerpagoalumno').modal('show');
}

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
    
    $("#ModalBoletaForm").append("<input type='hidden' value='"+id+"' id='id' name='id'>");
    AjaxBandeja.CargarContesta(SlctCargarContesta);
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
        boton='<td> <a class="btn btn-success btn-sm" onClick="AgregarEditar(0,'+r.id+')" ><i class="fa fa-check fa-lg">Ver</i></a> </td>';
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

CargarSlct=function(slct){
    if(slct==1){
    AjaxBandeja.CargarContesta(SlctCargarContesta);
    }
};

SlctCargarContesta=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.nombre+"</option>";
    });
    $("#ModalVerpagoalumno #slct_contesta_id").html(html); 
    $("#ModalVerpagoalumno #slct_contesta_id").selectpicker('refresh');

};
HTMLCargarBandejaDetalle=function(result){
    var html="";
    var pos=0;
    $('#TableVerpagoalumno').DataTable().destroy();
    
    $.each(result.data,function(index,r){
        pos++;
        html+="<tr id='trid_"+r.id+"'>"+
            "<td >"+pos+"</td>"+
            "<td class='estado_contesta'>"+r.estado_contesta+"</td>"+
            "<td class='detalle'>"+r.detalle+"</td>"+
            "<td class='observacion'>"+r.observacion+"</td>"+
            "<td class='created_at'>"+r.created_at+"</td>"+
            "<td class='usuario'>"+r.usuario+"</td>";
        html+="</tr>";
    });
    $("#TableVerpagoalumno tbody").html(html); 
    $("#TableVerpagoalumno").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

};

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxBandeja.AgregarEditar(HTMLAgregarEditar);
    }
};
HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalVerpagoalumno').modal('hide');
        AjaxBandeja.Cargar(HTMLCargarBandeja);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
};
ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalVerpagoalumnoForm #slct_estado_contesto").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione si Contestó el Alumno',4000);
    }
    else if( $.trim( $("#ModalVerpagoalumnoForm #slct_estado_contesto").val() )=='1' && $.trim( $("#ModalVerpagoalumnoForm #slct_contesta_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Respuesta',4000);
    }
    return r;
}
</script>
