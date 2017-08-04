<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CategoriaG={id:0,
categoria:"",
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCategoria").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    $('#ModalCategoria').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax4();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax4();');
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

ValidaForm4=function(){
    var r=true;
    if( $.trim( $("#ModalCategoriaForm #txt_categoria").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Categoria',4000);
    }
    return r;
}

AgregarEditar4=function(val,id){
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



AgregarEditarAjax4=function(){
    if( ValidaForm4() ){
        AjaxAgregar_Editar_Categoria.AgregarEditar4(HTMLAgregarEditar4);
    }
}

HTMLAgregarEditar4=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCategoria').modal('hide');
        AjaxListacategoria.Cargar(HTMLCargarCategoria);
       // CargarSlct(2);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}



</script>
