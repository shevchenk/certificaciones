<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProveedorDetalleG={id:0,
proveedor_id:0,
categoria_id:0,
categoria:'',
articulo_id:0,
articulo:'',
estado:1}; // Datos Globales
$(document).ready(function() {
     $("#TableProveedorDetalle").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
     

    $('#ModalProveedorDetalle').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax3();');
        }
        else{
            
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax3();');
            $("#ModalProveedorDetalleForm").append("<input type='hidden' value='"+ProveedorDetalleG.id+"' name='id'>");
        }

        $('#ModalProveedorDetalleForm #txt_categoria').val( ProveedorDetalleG.categoria );
        $('#ModalProveedorDetalleForm #txt_categoria_id').val( ProveedorDetalleG.categoria_id );
        $('#ModalProveedorDetalleForm #txt_articulo').val( ProveedorDetalleG.articulo );
        $('#ModalProveedorDetalleForm #txt_articulo_id').val( ProveedorDetalleG.articulo_id );
        $('#ModalProveedorDetalleForm #slct_estado').val( ProveedorDetalleG.estado );
        $("#ModalProveedorDetalleForm select").selectpicker('refresh');
        //$('#ModalProveedorDetalleForm #txt_razon_social').focus();
    });

    $('#ModalProveedorDetalle').on('hidden.bs.modal', function (event) {
        $("#ModalProveedorDetalleForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm3=function(){
    var r=true;

    if( $.trim( $("#ModalProveedorDetalleForm #txt_categoria_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Categoria',4000);
    }

    
    return r;
}

AgregarEditar3=function(val,id){
    AddEdit=val;
    ProveedorDetalleG.id='';
    ProveedorDetalleG.categoria_id='';
    ProveedorDetalleG.categoria='';
    ProveedorDetalleG.articulo_id='';
    ProveedorDetalleG.articulo='';
    ProveedorDetalleG.estado='1';

    if( val==0 ){

        ProveedorDetalleG.id=id;
        ProveedorDetalleG.proveedor_id=$("#TableProveedorDetalle #trid_"+id+" .proveedor_id").val();
        ProveedorDetalleG.categoria_id=$("#TableProveedorDetalle #trid_"+id+" .categoria_id").val();
        ProveedorDetalleG.categoria=$("#TableProveedorDetalle #trid_"+id+" .categoria").text();
        ProveedorDetalleG.articulo_id=$("#TableProveedorDetalle #trid_"+id+" .articulo_id").val();
        ProveedorDetalleG.articulo=$("#TableProveedorDetalle #trid_"+id+" .articulo").text();
        ProveedorDetalleG.estado=$("#TableProveedorDetalle #trid_"+id+" .estado").val();


    }
    $('#ModalProveedorDetalle').modal('show');
}

CambiarEstado3=function(estado,id){
    AjaxProveedorDetalle.CambiarEstado(HTMLCambiarEstado3,estado,id);
}

HTMLCambiarEstado3=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProveedorDetalle.Cargar(HTMLCargarProveedorDetalle);
    }
}

AgregarEditarAjax3=function(){
    if( ValidaForm3() ){
        AjaxProveedorDetalle.AgregarEditar(HTMLAgregarEditar3);
    }
}

HTMLAgregarEditar3=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProveedorDetalle').modal('hide');
        AjaxProveedorDetalle.Cargar(HTMLCargarProveedorDetalle);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarProveedorDetalle=function(result){
    var html="";
    $('#TableProveedorDetalle').DataTable().destroy();

    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado3(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado3(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='categoria'>"+r.categoria+"</td>"+
            "<td class='articulo'>"+r.articulo+"</td>"+
                   
            "<input type='hidden' class='proveedor_id' value='"+r.proveedor_id+"'>"+
            "<input type='hidden' class='categoria_id' value='"+r.categoria_id+"'>"+
            "<input type='hidden' class='articulo_id' value='"+r.articulo_id+"'>"+
            "<td>";
            html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar3(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableProveedorDetalle tbody").html(html); 
    $("#TableProveedorDetalle").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
        
    });
};
</script>
