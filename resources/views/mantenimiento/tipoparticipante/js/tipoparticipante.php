<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var TipoParticipanteG={id:0,tipo_participante:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableTipoParticipante").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxTipoParticipante.Cargar(HTMLCargarTipoParticipante);
    $("#TipoParticipanteForm #TableTipoParticipante select").change(function(){ AjaxTipoParticipante.Cargar(HTMLCargarTipoParticipante); });
    $("#TipoParticipanteForm #TableTipoParticipante input").blur(function(){ AjaxTipoParticipante.Cargar(HTMLCargarTipoParticipante); });

    $('#ModalTipoParticipante').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalTipoParticipanteForm").append("<input type='hidden' value='"+TipoParticipanteG.id+"' name='id'>");
        }

        $('#ModalTipoParticipanteForm #txt_tipo_participante').val( TipoParticipanteG.tipo_participante );
        
        $('#ModalTipoParticipanteForm #slct_estado').selectpicker('val', TipoParticipanteG.estado );
        $('#ModalTipoParticipanteForm #txt_trabajador').focus();
    });

    $('#ModalTipoParticipante').on('hidden.bs.modal', function (event) {
        $("ModalTipoParticipanteForm input[type='hidden']").not('.mant').remove();
       // $("ModalTipoParticipanteForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalTipoParticipanteForm #txt_tipo_participante").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo Participante',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    TipoParticipanteG.id='';
    TipoParticipanteG.tipo_participante='';
    TipoParticipanteG.estado='1';
    if( val==0 ){
        TipoParticipanteG.id=id;
        TipoParticipanteG.tipo_participante=$("#TableTipoParticipante #trid_"+id+" .tipo_participante").text();
        TipoParticipanteG.estado=$("#TableTipoParticipante #trid_"+id+" .estado").val();
    }
    $('#ModalTipoParticipante').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxTipoParticipante.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTipoParticipante.Cargar(HTMLCargarTipoParticipante);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxTipoParticipante.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTipoParticipante').modal('hide');
        AjaxTipoParticipante.Cargar(HTMLCargarTipoParticipante);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarTipoParticipante=function(result){
    var html="";
    $('#TableTipoParticipante').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tipo_participante'>"+r.tipo_participante+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableTipoParticipante tbody").html(html); 
    $("#TableTipoParticipante").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTipoParticipante": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTipoParticipante_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTipoParticipante','AjaxTipoParticipante',result.data,'#TableTipoParticipante_paginate');
        }
    });
};

</script>
