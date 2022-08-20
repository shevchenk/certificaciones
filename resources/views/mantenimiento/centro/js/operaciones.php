<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CentroOperacionG={id:0,
centro_operacion:'',
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCentroOperacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#ModalCentroOperacionForm #txt_distrito").easyAutocomplete(DistritoOpciones);

    AjaxCentroOperacion.Cargar(HTMLCargarCentroOperacion);
    $("#CentroOperacionForm #TableCentroOperacion select").change(function(){ AjaxCentroOperacion.Cargar(HTMLCargarCentroOperacion); });
    $("#CentroOperacionForm #TableCentroOperacion input").blur(function(){ AjaxCentroOperacion.Cargar(HTMLCargarCentroOperacion); });

    $('#ModalCentroOperacion').on('shown.bs.modal', function (event) {
        $("#ModalCentroOperacionForm #txt_distrito_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalCentroOperacionForm").append("<input type='hidden' value='"+CentroOperacionG.id+"' name='id'>");
            if( $.trim(CentroOperacionG.distrito_id) != '' ){
                $("#ModalCentroOperacionForm #txt_distrito_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
            }
        }

        $('#ModalCentroOperacionForm #txt_centro_operacion').val( CentroOperacionG.centro_operacion );
        $('#ModalCentroOperacionForm #txt_direccion').val( CentroOperacionG.direccion );
        $('#ModalCentroOperacionForm #txt_provincia_id').val( CentroOperacionG.provincia_id );
        $('#ModalCentroOperacionForm #txt_distrito_id').val( CentroOperacionG.distrito_id );
        $('#ModalCentroOperacionForm #txt_region_id').val( CentroOperacionG.region_id );
        $('#ModalCentroOperacionForm #txt_provincia').val( CentroOperacionG.provincia );
        $('#ModalCentroOperacionForm #txt_distrito').val( CentroOperacionG.distrito );
        $('#ModalCentroOperacionForm #txt_region').val( CentroOperacionG.region );
        $('#ModalCentroOperacionForm #slct_estado').selectpicker( 'val',CentroOperacionG.estado );
        
        $('#ModalCentroOperacionForm #txt_curso').focus();
    });

    $('#ModalCentroOperacion').on('hidden.bs.modal', function (event) {
        $("#ModalCentroOperacionForm input[type='hidden']").not('.mant').remove();

       // $("ModalCentroOperacionForm input").val('');
    });
});

Limpiar=function(limpiar){
    $("#"+limpiar).val('');
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalCentroOperacionForm #txt_centro_operacion").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Centro de Operación',4000);
    }
    else if( $.trim( $("#ModalCentroOperacionForm #txt_direccion").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese dirección',4000);
    }
    else if( !$("#ModalCentroOperacionForm #txt_distrito_ico").hasClass('has-success') ){
        r=false;
        msjG.mensaje('warning','Busque y seleccione distrito',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    CentroOperacionG.id='';
    CentroOperacionG.centro_operacion='';
    CentroOperacionG.direccion='';
    CentroOperacionG.region_id='';
    CentroOperacionG.provincia_id='';
    CentroOperacionG.distrito_id='';
    CentroOperacionG.region='';
    CentroOperacionG.provincia='';
    CentroOperacionG.distrito='';
    CentroOperacionG.estado='1';
    if( val==0 ){
        CentroOperacionG.id=id;
        CentroOperacionG.centro_operacion=$("#TableCentroOperacion #trid_"+id+" .centro_operacion").text();
        CentroOperacionG.direccion=$("#TableCentroOperacion #trid_"+id+" .direccion").text();
        CentroOperacionG.region_id=$("#TableCentroOperacion #trid_"+id+" .region_id").val();
        CentroOperacionG.provincia_id=$("#TableCentroOperacion #trid_"+id+" .provincia_id").val();
        CentroOperacionG.distrito_id=$("#TableCentroOperacion #trid_"+id+" .distrito_id").val();
        CentroOperacionG.region=$("#TableCentroOperacion #trid_"+id+" .region").text();
        CentroOperacionG.provincia=$("#TableCentroOperacion #trid_"+id+" .provincia").text();
        CentroOperacionG.distrito=$("#TableCentroOperacion #trid_"+id+" .distrito").text();
        CentroOperacionG.estado=$("#TableCentroOperacion #trid_"+id+" .estado").val();
    }
    $('#ModalCentroOperacion').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxCentroOperacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCentroOperacion.Cargar(HTMLCargarCentroOperacion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxCentroOperacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCentroOperacion').modal('hide');
        AjaxCentroOperacion.Cargar(HTMLCargarCentroOperacion);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarCentroOperacion=function(result){ //INICIO HTML
    var html="";
    $('#TableCentroOperacion').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='centro_operacion'>"+r.centro_operacion+"</td>"+
            "<td class='direccion'>"+$.trim(r.direccion)+"</td>"+
            "<td class='region'>"+$.trim(r.region)+"</td>"+
            "<td class='provincia'>"+$.trim(r.provincia)+"</td>"+
            "<td class='distrito'>"+$.trim(r.distrito)+"</td>"+
            "<td>"+
            "<input type='hidden' class='region_id' value='"+ $.trim(r.region_id) +"'>"+
            "<input type='hidden' class='provincia_id' value='"+ $.trim(r.provincia_id) +"'>"+
            "<input type='hidden' class='distrito_id' value='"+ $.trim(r.distrito_id) +"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";

    });//FIN FUNCTION

    $("#TableCentroOperacion tbody").html(html); 
    $("#TableCentroOperacion").DataTable({ //INICIO DATATABLE
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
            $('#TableCentroOperacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarCentroOperacion','AjaxCentroOperacion',result.data,'#TableCentroOperacion_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
