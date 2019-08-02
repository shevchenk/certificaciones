<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var EmpresaG={id:0,empresa:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    $("#EmpresaForm #TableEmpresa select").change(function(){ AjaxEmpresa.Cargar(HTMLCargarEmpresa); });
    $("#EmpresaForm #TableEmpresa input").blur(function(){ AjaxEmpresa.Cargar(HTMLCargarEmpresa); });

    $('#ModalEmpresa').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEmpresaForm").append("<input type='hidden' value='"+EmpresaG.id+"' name='id'>");
        }

        $('#ModalEmpresaForm #txt_empresa').val( EmpresaG.empresa );
        
        $('#ModalEmpresaForm #slct_estado').selectpicker('val', EmpresaG.estado );
        $('#ModalEmpresaForm #txt_empresa').focus();
    });

    $('#ModalEmpresa').on('hidden.bs.modal', function (event) {
        $("#ModalEmpresaForm input[type='hidden']").not('.mant').remove();
       // $("ModalEmpresaForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEmpresaForm #txt_empresa").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Empresa',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EmpresaG.id='';
    EmpresaG.empresa='';
    EmpresaG.estado='1';
    if( val==0 ){
        EmpresaG.id=id;
        EmpresaG.empresa=$("#TableEmpresa #trid_"+id+" .empresa").text();
        EmpresaG.estado=$("#TableEmpresa #trid_"+id+" .estado").val();
    }
    $('#ModalEmpresa').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxEmpresa.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEmpresa.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEmpresa').modal('hide');
        AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarEmpresa=function(result){
    var html="";
    $('#TableEmpresa').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='empresa'>"+r.empresa+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableEmpresa tbody").html(html); 
    $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthEmpresa": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableEmpresa_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEmpresa','AjaxEmpresa',result.data,'#TableEmpresa_paginate');
        }
    });
};

</script>
