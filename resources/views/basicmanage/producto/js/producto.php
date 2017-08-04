<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProductoG={id:0,articulo_id:0,unidad_medida:0,producto:"",imagen_nombre:"",foto:"",imagen_archivo:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#TableProducto").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $('#ModalProducto').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax3();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax3();');
            $("#ModalProductoForm").append("<input type='hidden' value='"+ProductoG.id+"' name='id'>");
        }

        $('#ModalProductoForm #txt_producto').val( ProductoG.producto );
        $('#ModalProductoForm #slct_articulo').val( ProductoG.articulo_id );
        $('#ModalProductoForm #slct_unidad_medida').val( ProductoG.unidad_medida );
        $('#ModalProductoForm #slct_estado').val( ProductoG.estado );
        $('#ModalProductoForm #txt_imagen_nombre').val(ProductoG.imagen_nombre);
        $('#ModalProductoForm #txt_imagen_archivo').val('');
        $('#ModalProductoForm .img-circle').attr('src',ProductoG.imagen_archivo);
        $("#ModalProducto select").selectpicker('refresh');
        $('#ModalProductoForm #txt_producto').focus();
    });

    $('#ModalProducto').on('hidden.bs.modal', function (event) {
        $("#ModalProductoForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm3=function(){
    var r=true;
    if( $.trim( $("#ModalProductoForm #txt_producto").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Producto',4000);
    }
    else if( $.trim( $("#ModalProductoForm #slct_articulo").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Artículo',4000);
    }
    else if( $.trim( $("#ModalProductoForm #slct_unidad_medida").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Unidad de Medida',4000);
    }
    return r;
}

AgregarEditar3=function(val,id){
    AddEdit=val;
    ProductoG.id='';
    ProductoG.articulo_id='0';
    ProductoG.producto='';
    ProductoG.unidad_medida='0';
    ProductoG.imagen_nombre='';
    ProductoG.imagen_archivo='';
    ProductoG.estado='1';
    if( val==0 ){
        ProductoG.id=id;
        ProductoG.articulo_id=$("#TableProducto #trid_"+id+" .articulo_id").val();
        ProductoG.producto=$("#TableProducto #trid_"+id+" .producto").text();
        ProductoG.unidad_medida=$("#TableProducto #trid_"+id+" .unidad_medida").val();
        ProductoG.foto=$("#TableProducto #trid_"+id+" .foto").val();
        if(ProductoG.foto!='undefined'){
            ProductoG.imagen_archivo='img/product/'+ProductoG.foto;
            ProductoG.imagen_nombre=ProductoG.foto;
        }else {
            ProductoG.imagen_archivo='';
            ProductoG.imagen_nombre='';
        }      
        ProductoG.estado=$("#TableProducto #trid_"+id+" .estado").val();
    }
    $('#ModalProducto').modal('show');
}

CambiarEstado3=function(estado,id){
    AjaxProducto.CambiarEstado(HTMLCambiarEstado3,estado,id);
}

HTMLCambiarEstado3=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProducto.Cargar(HTMLCargarProducto3);
    }
}

AgregarEditarAjax3=function(){
    if( ValidaForm3() ){
        AjaxProducto.AgregarEditar(HTMLAgregarEditar3);
    }
}

HTMLAgregarEditar3=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProducto').modal('hide');
        AjaxProducto.Cargar(HTMLCargarProducto3);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarProducto3=function(result){
    var html="";
    $('#TableProducto').DataTable().destroy();
    
    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado3(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado3(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>";
            if(r.foto!=null){    
            html+="<a  target='_blank' href='img/product/"+r.foto+"'><img src='img/product/"+r.foto+"' style='height: 40px;width: 40px;'></a>";}
            html+="</td>"+
            "<td class='producto'>"+r.producto+"</td>"+
            "<td class='articulo'>"+r.articulo+"</td>"+
            "<td>"+
            "<input type='hidden' class='articulo_id' value='"+r.articulo_id+"'>"+
            "<input type='hidden' class='unidad_medida' value='"+r.unidad_medida+"'>";
        if(r.foto!=null){
        html+="<input type='hidden' class='foto' value='"+r.foto+"'>";}

        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar3(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableProducto tbody").html(html); 
    $("#TableProducto").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

};

onImagen = function (event) {
        var files = event.target.files || event.dataTransfer.files;
        if (!files.length)
            return;
        var image = new Image();
        var reader = new FileReader();
        reader.onload = (e) => {
            $('#ModalProductoForm #txt_imagen_archivo').val(e.target.result);
            $('#ModalProductoForm .img-circle').attr('src',e.target.result);
        };
        reader.readAsDataURL(files[0]);
        $('#ModalProductoForm #txt_imagen_nombre').val(files[0].name);
        console.log(files[0].name);
    };

</script>
