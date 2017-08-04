<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CategoriaG={id:0,categoria:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCategoria").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $("#CategoriaForm #TableCategoria select").change(function(){ AjaxCategoria.Cargar(HTMLCargarCategoria1); });
    $("#CategoriaForm #TableCategoria input").blur(function(){ AjaxCategoria.Cargar(HTMLCargarCategoria1); });

    $('#ModalCategoria').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax1();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax1();');
            $("#ModalCategoriaForm").append("<input type='hidden' value='"+CategoriaG.id+"' name='id'>");
        }

        $('#ModalCategoriaForm #txt_categoria').val( CategoriaG.categoria );
        $('#ModalCategoriaForm #slct_estado').val( CategoriaG.estado );
        $("#ModalCategoriaForm select").selectpicker('refresh');
        $('#ModalCategoriaForm #txt_categoria').focus();
    });

    $('#ModalCategoria').on('hidden.bs.modal', function (event) {
        $("#ModalCategoriaForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm1=function(){
    var r=true;
    if( $.trim( $("#ModalCategoriaForm #txt_categoria").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Categoria',4000);
    }
    return r;
}

AgregarEditar1=function(val,id){
    AddEdit=val;
    CategoriaG.id='';
    CategoriaG.categoria='';
    CategoriaG.estado='1';
    if( val==0 ){
        CategoriaG.id=id;
        CategoriaG.categoria=$("#TableCategoria #trid_"+id+" .categoria").text();
        CategoriaG.estado=$("#TableCategoria #trid_"+id+" .estado").val();
    }
    $('#ModalCategoria').modal('show');
}

CambiarEstado1=function(estado,id){
    AjaxCategoria.CambiarEstado(HTMLCambiarEstado1,estado,id);
}

HTMLCambiarEstado1=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCategoria.Cargar(HTMLCargarCategoria1);
        CargarSlct(2);
    }
}

AgregarEditarAjax1=function(){
    if( ValidaForm1() ){
        AjaxCategoria.AgregarEditar(HTMLAgregarEditar1);
    }
}

HTMLAgregarEditar1=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCategoria').modal('hide');
        AjaxCategoria.Cargar(HTMLCargarCategoria1);
        CargarSlct(2);
    } else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarCategoria1=function(result){
    var html="";
    $('#TableCategoria').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado1(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado1(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='categoria'>"+r.categoria+"</td>"+
            "<td><input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar1(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableCategoria tbody").html(html); 
    $("#TableCategoria").DataTable({
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
            $('#TableCategoria_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarCategoria1','AjaxCategoria',result.data,'#TableCategoria_paginate');
        }
    });
};
</script>
