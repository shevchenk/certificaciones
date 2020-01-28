<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var TipoLlamadaG={id:0,tipollamada:'',estado:1}; // Datos Globales
var TipoLlamadaSubG = {id:0};
var TipoLlamadaSubDetalleG = {id:0};
var IdTipoLlamadaG=0;
var IdTipoLlamadaSubG=0;

$(document).ready(function() {
    $("#TableTipoLlamada").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    $("#TipoLlamadaForm #TableTipoLlamada select").change(function(){ AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada); });
    $("#TipoLlamadaForm #TableTipoLlamada input").blur(function(){ AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada); });

    $('#ModalTipoLlamada').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalTipoLlamadaForm").append("<input type='hidden' value='"+TipoLlamadaG.id+"' name='id'>");
        }

        $('#ModalTipoLlamadaForm #txt_tipo_llamada').val( TipoLlamadaG.tipollamada );
        
        $('#ModalTipoLlamadaForm #slct_estado').selectpicker('val', TipoLlamadaG.estado );
        $("#ModalTipoLlamadaForm #slct_peso").selectpicker( 'val', TipoLlamadaG.peso );
        $("#ModalTipoLlamadaForm #slct_obs").selectpicker( 'val', TipoLlamadaG.obs );

        $('#ModalTipoLlamadaForm #txt_tipo_llamada').focus();
    });

    $('#ModalTipoLlamada').on('hidden.bs.modal', function (event) {
        $("#ModalTipoLlamadaForm input[type='hidden']").not('.mant').remove();
       // $("ModalTipoLlamadaForm input").val('');
    });

    /*****************************************************************************/
    $("#TipoLlamadaSubForm #TableTipoLlamadaSub select").change(function(){ AjaxTipoLlamadaSub.Cargar(HTMLCargarTipoLlamadaSub); });
    $("#TipoLlamadaSubForm #TableTipoLlamadaSub input").blur(function(){ AjaxTipoLlamadaSub.Cargar(HTMLCargarTipoLlamadaSub); });

    $('#ModalTipoLlamadaSub').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjaxSub();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjaxSub();');
            $("#ModalTipoLlamadaSubForm").append("<input type='hidden' value='"+TipoLlamadaSubG.id+"' name='id'>");
        }

        $('#ModalTipoLlamadaSubForm #txt_tipo_llamada').val( TipoLlamadaSubG.tipo_llamada );
        $('#ModalTipoLlamadaSubForm #txt_tipo_llamada_sub').val( TipoLlamadaSubG.tipo_llamada_sub );
        $('#ModalTipoLlamadaSubForm #txt_tipo_llamada_sub').focus();
    });

    $('#ModalTipoLlamadaSub').on('hidden.bs.modal', function (event) {
        $("#ModalTipoLlamadaSubForm input[type='hidden']").not('.mant').remove();
    });

    /*****************************************************************************/
    $("#TipoLlamadaSubDetalleForm #TableTipoLlamadaSubDetalle select").change(function(){ AjaxTipoLlamadaSubDetalle.Cargar(HTMLCargarTipoLlamadaSubDetalle); });
    $("#TipoLlamadaSubDetalleForm #TableTipoLlamadaSubDetalle input").blur(function(){ AjaxTipoLlamadaSubDetalle.Cargar(HTMLCargarTipoLlamadaSubDetalle); });

    $('#ModalTipoLlamadaSubDetalle').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjaxSubDetalle();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjaxSubDetalle();');
            $("#ModalTipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+TipoLlamadaSubDetalleG.id+"' name='id'>");
        }

        $('#ModalTipoLlamadaSubDetalleForm #txt_tipo_llamada_sub').val( TipoLlamadaSubDetalleG.tipo_llamada_sub );
        $('#ModalTipoLlamadaSubDetalleForm #txt_tipo_llamada_sub_detalle').val( TipoLlamadaSubDetalleG.tipo_llamada_sub_detalle );
        $('#ModalTipoLlamadaSubDetalleForm #txt_tipo_llamada_sub_detalle').focus();
    });

    $('#ModalTipoLlamadaSubDetalle').on('hidden.bs.modal', function (event) {
        $("#ModalTipoLlamadaSubDetalleForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalTipoLlamadaForm #txt_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo de Llamada',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    TipoLlamadaG.id='';
    TipoLlamadaG.tipollamada='';
    TipoLlamadaG.estado='1';
    TipoLlamadaG.peso='0';
    TipoLlamadaG.obs='0';
    if( val==0 ){
        TipoLlamadaG.id=id;
        TipoLlamadaG.tipollamada=$("#TableTipoLlamada #trid_"+id+" .tipollamada").text();
        TipoLlamadaG.estado=$("#TableTipoLlamada #trid_"+id+" .estado").val();
        TipoLlamadaG.peso=$("#TableTipoLlamada #trid_"+id+" .peso").val();
        TipoLlamadaG.obs=$("#TableTipoLlamada #trid_"+id+" .obs").val();
    }
    $('#ModalTipoLlamada').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxTipoLlamada.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxTipoLlamada.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTipoLlamada').modal('hide');
        AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarTipoLlamada=function(result){
    var html="";
    $('#TableTipoLlamada').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        sub='';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        if(r.obs==3){
            sub='<a style="margin-left:10px;" class="btn btn-info btn-sm" onClick="VerSub('+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tipollamada'>"+r.tipo_llamada+"</td>"+
            "<td>";
        html+="<input type='hidden' class='peso' value='"+r.peso+"'>";
        html+="<input type='hidden' class='obs' value='"+r.obs+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>'+
            sub+
            '</td>';
        html+="</tr>";
    });
    $("#TableTipoLlamada tbody").html(html); 
    $("#TableTipoLlamada").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTipoLlamada": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTipoLlamada_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTipoLlamada','AjaxTipoLlamada',result.data,'#TableTipoLlamada_paginate');
        }
    });
};

VerSub=function(id){
    IdTipoLlamadaG=id;
    AjaxTipoLlamadaSub.Cargar(HTMLCargarTipoLlamadaSub);
}

HTMLCargarTipoLlamadaSub=function(result){
    var html="";
    $('#TableTipoLlamadaSub').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstadoSub(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        sub='<a style="margin-left:10px;" class="btn btn-info btn-sm" onClick="VerSubDetalle('+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstadoSub(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tipo_llamada'>"+r.tipo_llamada+"</td>"+
            "<td class='tipo_llamada_sub'>"+r.tipo_llamada_sub+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditarSub(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>'+
            sub+
            '</td>';
        html+="</tr>";
    });
    $("#TableTipoLlamadaSub tbody").html(html); 
    $("#TableTipoLlamadaSub").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTipoLlamada": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTipoLlamadaSub_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTipoLlamadaSub','AjaxTipoLlamadaSub',result.data,'#TableTipoLlamadaSub_paginate');
        }
    });
};

ValidaFormSub=function(){
    var r=true;
    if( $.trim( $("#ModalTipoLlamadaSubForm #txt_tipo_llamada_sub").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo de Llamada Sub',4000);
    }

    return r;
}

AgregarEditarSub=function(val,id){
    AddEdit=val;
    TipoLlamadaSubG.id='';
    TipoLlamadaSubG.tipo_llamada_sub='';
    TipoLlamadaSubG.estado='1';
    TipoLlamadaSubG.tipo_llamada=$("#TableTipoLlamada #trid_"+IdTipoLlamadaG+" .tipollamada").text();
    if( val==0 ){
        TipoLlamadaSubG.id=id;
        TipoLlamadaSubG.tipo_llamada_sub=$("#TableTipoLlamadaSub #trid_"+id+" .tipo_llamada_sub").text();
        TipoLlamadaSubG.estado=$("#TableTipoLlamadaSub #trid_"+id+" .estado").val();
    }

    if( IdTipoLlamadaG=='' ){
        msjG.mensaje('success','Seleccione un tipo de llamada',4000);
    }
    else{
        $('#ModalTipoLlamadaSub').modal('show');
    }
}

CambiarEstadoSub=function(estado,id){
    AjaxTipoLlamadaSub.CambiarEstado(HTMLCambiarEstadoSub,estado,id);
}

HTMLCambiarEstadoSub=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTipoLlamadaSub.Cargar(HTMLCargarTipoLlamadaSub);
    }
}

AgregarEditarAjaxSub=function(){
    if( ValidaFormSub() ){
        AjaxTipoLlamadaSub.AgregarEditar(HTMLAgregarEditarSub);
    }
}

HTMLAgregarEditarSub=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTipoLlamadaSub').modal('hide');
        AjaxTipoLlamadaSub.Cargar(HTMLCargarTipoLlamadaSub);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

/***************************************************************************/
VerSubDetalle=function(id){
    IdTipoLlamadaSubG=id;
    AjaxTipoLlamadaSubDetalle.Cargar(HTMLCargarTipoLlamadaSubDetalle);
}

HTMLCargarTipoLlamadaSubDetalle=function(result){
    var html="";
    $('#TableTipoLlamadaSubDetalle').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstadoSubDetalle(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        sub='';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstadoSubDetalle(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tipo_llamada_sub'>"+r.tipo_llamada_sub+"</td>"+
            "<td class='tipo_llamada_sub_detalle'>"+r.tipo_llamada_sub_detalle+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditarSubDetalle(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>'+
            '</td>';
        html+="</tr>";
    });
    $("#TableTipoLlamadaSubDetalle tbody").html(html); 
    $("#TableTipoLlamadaSubDetalle").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTipoLlamada": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTipoLlamadaSubDetalle_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTipoLlamadaSubDetalle','AjaxTipoLlamadaSubDetalle',result.data,'#TableTipoLlamadaSubDetalle_paginate');
        }
    });
};

ValidaFormSubDetalle=function(){
    var r=true;
    if( $.trim( $("#ModalTipoLlamadaSubDetalleForm #txt_tipo_llamada_sub").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo de Llamada Sub Detalle',4000);
    }

    return r;
}

AgregarEditarSubDetalle=function(val,id){
    AddEdit=val;
    TipoLlamadaSubDetalleG.id='';
    TipoLlamadaSubDetalleG.tipo_llamada_sub_detalle='';
    TipoLlamadaSubDetalleG.estado='1';
    TipoLlamadaSubDetalleG.tipo_llamada_sub=$("#TableTipoLlamadaSub #trid_"+IdTipoLlamadaSubG+" .tipo_llamada_sub").text();
    if( val==0 ){
        TipoLlamadaSubDetalleG.id=id;
        TipoLlamadaSubDetalleG.tipo_llamada_sub_detalle=$("#TableTipoLlamadaSubDetalle #trid_"+id+" .tipo_llamada_sub_detalle").text();
        TipoLlamadaSubDetalleG.estado=$("#TableTipoLlamadaSubDetalle #trid_"+id+" .estado").val();
    }

    if( IdTipoLlamadaSubG=='' ){
        msjG.mensaje('success','Seleccione un tipo de llamada sub',4000);
    }
    else{
        $('#ModalTipoLlamadaSubDetalle').modal('show');
    }
}

CambiarEstadoSubDetalle=function(estado,id){
    AjaxTipoLlamadaSubDetalle.CambiarEstado(HTMLCambiarEstadoSubDetalle,estado,id);
}

HTMLCambiarEstadoSubDetalle=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTipoLlamadaSubDetalle.Cargar(HTMLCargarTipoLlamadaSubDetalle);
    }
}

AgregarEditarAjaxSubDetalle=function(){
    if( ValidaFormSubDetalle() ){
        AjaxTipoLlamadaSubDetalle.AgregarEditar(HTMLAgregarEditarSubDetalle);
    }
}

HTMLAgregarEditarSubDetalle=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTipoLlamadaSubDetalle').modal('hide');
        AjaxTipoLlamadaSubDetalle.Cargar(HTMLCargarTipoLlamadaSubDetalle);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}


</script>
