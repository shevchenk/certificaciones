<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CertificadoEstadoG={id:0,
                        estado_certificado:"",
                        detalle:"",
                        tiempo_espera:"",
                        estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCertificadoEstado").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxCertificadoEstado.Cargar(HTMLCargarCertificadoEstado);
    $("#CertificadoEstadoForm #TableCertificadoEstado select").change(function(){ AjaxCertificadoEstado.Cargar(HTMLCargarCertificadoEstado); });
    $("#CertificadoEstadoForm #TableCertificadoEstado input").blur(function(){ AjaxCertificadoEstado.Cargar(HTMLCargarCertificadoEstado); });

    $('#ModalCertificadoEstado').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalCertificadoEstadoForm").append("<input type='hidden' value='"+CertificadoEstadoG.id+"' name='id'>");
        }

        $('#ModalCertificadoEstadoForm #txt_estado_certificado').val( CertificadoEstadoG.estado_certificado );
        $('#ModalCertificadoEstadoForm #txt_detalle').val( CertificadoEstadoG.detalle );
        $('#ModalCertificadoEstadoForm #txt_tiempo_espera').val( CertificadoEstadoG.tiempo_espera );
        
        $('#ModalCertificadoEstadoForm #slct_estado').val( CertificadoEstadoG.estado );
        $('#ModalCertificadoEstadoForm #txt_estado_certificado').focus();
    });

    $('#ModalCertificadoEstado').on('hidden.bs.modal', function (event) {
        $("ModalCertificadoEstadoForm input[type='hidden']").not('.mant').remove();
       // $("ModalCertificadoEstadoForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalCertificadoEstadoForm #txt_estado_certificado").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Estado Certificado',4000);
    }
    else if( $.trim( $("#ModalCertificadoEstadoForm #txt_detalle").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Detalle',4000);
    }
    else if( $.trim( $("#ModalCertificadoEstadoForm #txt_tiempo_espera").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tiempo de espera',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    CertificadoEstadoG.id='';
    CertificadoEstadoG.estado_certificado='';
    CertificadoEstadoG.detalle='';
    CertificadoEstadoG.tiempo_espera='';
    CertificadoEstadoG.estado='1';
    if( val==0 ){
        CertificadoEstadoG.id=id;
        CertificadoEstadoG.estado_certificado=$("#TableCertificadoEstado #trid_"+id+" .estado_certificado").text();
        CertificadoEstadoG.detalle=$("#TableCertificadoEstado #trid_"+id+" .detalle").text();
        CertificadoEstadoG.tiempo_espera=$("#TableCertificadoEstado #trid_"+id+" .tiempo_espera").text();
        
        CertificadoEstadoG.estado=$("#TableCertificadoEstado #trid_"+id+" .estado").val();
    }
    $('#ModalCertificadoEstado').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxCertificadoEstado.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCertificadoEstado.Cargar(HTMLCargarCertificadoEstado);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxCertificadoEstado.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCertificadoEstado').modal('hide');
        AjaxCertificadoEstado.Cargar(HTMLCargarCertificadoEstado);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarCertificadoEstado=function(result){
    var html="";
    $('#TableCertificadoEstado').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='estado_certificado'>"+r.estado_certificado+"</td>"+
            "<td class='detalle'>"+r.detalle+"</td>"+
            "<td class='tiempo_espera'>"+r.tiempo_espera+"</td>"+
            "<td>";

        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableCertificadoEstado tbody").html(html); 
    $("#TableCertificadoEstado").DataTable({
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
            $('#TableCertificadoEstado_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarCertificadoEstado','AjaxCertificadoEstado',result.data,'#TableCertificadoEstado_paginate');
        }
    });
};
</script>
