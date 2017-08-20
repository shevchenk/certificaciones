<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var OpcionG={id:0,
menu:0,
opcion:"",
ruta:"",
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableOpcion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxOpcion.Cargar(HTMLCargarOpcion);
    $("#CursoForm #TableOpcion select").change(function(){ AjaxOpcion.Cargar(HTMLCargarOpcion); });
    $("#CursoForm #TableOpcion input").blur(function(){ AjaxOpcion.Cargar(HTMLCargarOpcion); });

    $('#ModalOpcion').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalOpcionForm").append("<input type='hidden' value='"+OpcionG.id+"' name='id'>");
        }

        $('#ModalOpcionForm #txt_menu').val( OpcionG.menu );
        $('#ModalOpcionForm #txt_opcion').val( OpcionG.opcion );
        $('#ModalOpcionForm #slct_ruta').val( OpcionG.ruta );
        $('#ModalOpcionForm #slct_estado').val( OpcionG.estado );
        $("#ModalOpcion select").selectpicker('refresh');
        //$('#ModalOpcionForm #txt_menu').focus();
    });

    $('#ModalOpcion').on('hidden.bs.modal', function (event) {
        $("ModalOpcionForm input[type='hidden']").not('.mant').remove();

       // $("ModalOpcionForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalOpcionForm #txt_menu").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Menu',4000);
    }
    else if( $.trim( $("#ModalOpcionForm #txt_opcion").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Nombre',4000);
    }
    else if( $.trim( $("#ModalOpcionForm #slct_ruta").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione ruta',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    OpcionG.id='';
    OpcionG.menu='';
    OpcionG.opcion='';
    OpcionG.ruta='0';
    OpcionG.estado='1';
    if( val==0 ){
        OpcionG.id=id;
        OpcionG.menu=$("#TableOpcion #trid_"+id+" .menu").text();
        OpcionG.opcion=$("#TableOpcion #trid_"+id+" .opcion").text();
        OpcionG.ruta=$("#TableOpcion #trid_"+id+" .ruta").val();
        OpcionG.estado=$("#TableOpcion #trid_"+id+" .estado").val();
    }
    $('#ModalOpcion').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxOpcion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxOpcion.Cargar(HTMLCargarOpcion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxOpcion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalOpcion').modal('hide');
        AjaxOpcion.Cargar(HTMLCargarOpcion);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarOpcion=function(result){ //INICIO HTML
    var html="";
    $('#TableOpcion').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='menu'>"+r.menu+"</td>"+
            "<td class='opcion'>"+r.opcion+"</td>"+
            "<td class='rutaFORM'>"+r.ruta+"</td>"+
         

            "<td>"+
            "<input type='hidden' class='ruta' value='"+r.ruta+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";

    });//FIN FUNCTION

    $("#TableOpcion tbody").html(html); 
    $("#TableOpcion").DataTable({ //INICIO DATATABLE
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
            $('#TableOpcion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarOpcion','AjaxOpcion',result.data,'#TableOpcion_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
