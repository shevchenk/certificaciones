<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProductosucursalG={id:0,sucursal_id:0,producto_id:0,producto:"",precio_venta:"",precio_compra:"",moneda:0,stock:"",
               stock_minimo:"",dias_alerta:"",fecha_vencimiento:"",dias_vencimiento:"",estado:1}; // Datos Globales
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

    $("#TableProductosucursal").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(1);
    AjaxProductosucursal.Cargar(HTMLCargarProductosucursal);
    
    $('#ModalProductosucursal').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalProductosucursalForm").append("<input type='hidden' value='"+ProductosucursalG.id+"' name='id'>");
        }

        $('#ModalProductosucursalForm #txt_producto').val( ProductosucursalG.producto );
        $('#ModalProductosucursalForm #txt_producto_id').val( ProductosucursalG.producto_id );
        $('#ModalProductosucursalForm #slct_sucursal').val( ProductosucursalG.sucursal_id );
        $('#ModalProductosucursalForm #txt_precio_venta').val( ProductosucursalG.precio_venta );
        $('#ModalProductosucursalForm #txt_precio_compra').val( ProductosucursalG.precio_compra );
        $('#ModalProductosucursalForm #slct_moneda').val( ProductosucursalG.moneda );
        $('#ModalProductosucursalForm #txt_stock').val( ProductosucursalG.stock );
        $('#ModalProductosucursalForm #txt_stock_minimo').val( ProductosucursalG.stock_minimo );
        $('#ModalProductosucursalForm #txt_dias_alerta').val( ProductosucursalG.dias_alerta );
        $('#ModalProductosucursalForm #txt_fecha_vencimiento').val( ProductosucursalG.fecha_vencimiento );
        $('#ModalProductosucursalForm #txt_dias_vencimiento').val( ProductosucursalG.dias_vencimiento );
        $('#ModalProductosucursalForm #slct_estado').val( ProductosucursalG.estado );
        $("#ModalProductosucursal select").selectpicker('refresh');
        $('#ModalProductosucursalForm #txt_producto').focus();
    });

    $('#ModalProductosucursal').on('hidden.bs.modal', function (event) {
        $("#ModalProductosucursalForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalProductosucursalForm #txt_producto_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Producto',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #slct_sucursal").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sucursal',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_precio_venta").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Precio de Venta',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_precio_compra").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Precio de Compra',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #slct_moneda").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Moneda',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_stock").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Stock',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_stock_minimo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Stock Mínimo',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_dias_alerta").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese días de alerta',4000);
    }
    else if( $.trim( $("#ModalProductosucursalForm #txt_fecha_vencimiento").val() )=='' && 
            $.trim( $("#ModalProductosucursalForm #txt_dias_vencimiento").val() )==''){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha o días de vencimiento',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    ProductosucursalG.id='';
    ProductosucursalG.sucursal_id='0';
    ProductosucursalG.moneda='0';
    ProductosucursalG.stock='';
    ProductosucursalG.stock_minimo='';
    ProductosucursalG.dias_alerta='';
    ProductosucursalG.fecha_vencimiento='';
    ProductosucursalG.dias_vencimiento='';
    ProductosucursalG.producto='';
    ProductosucursalG.producto_id='';
    ProductosucursalG.precio_venta='';
    ProductosucursalG.estado='1';
    if( val==0 ){
        ProductosucursalG.id=id;
        ProductosucursalG.sucursal_id=$("#TableProductosucursal #trid_"+id+" .sucursal_id").val();
        ProductosucursalG.moneda=$("#TableProductosucursal #trid_"+id+" .moneda").val();
        ProductosucursalG.stock=$("#TableProductosucursal #trid_"+id+" .stock").text();
        ProductosucursalG.stock_minimo=$("#TableProductosucursal #trid_"+id+" .stock_minimo").text();
        ProductosucursalG.dias_alerta=$("#TableProductosucursal #trid_"+id+" .dias_alerta").val();
        ProductosucursalG.fecha_vencimiento=$("#TableProductosucursal #trid_"+id+" .fecha_vencimiento").val();
        ProductosucursalG.dias_vencimiento=$("#TableProductosucursal #trid_"+id+" .dias_vencimiento").val();
        ProductosucursalG.producto=$("#TableProductosucursal #trid_"+id+" .producto").text();
        ProductosucursalG.producto_id=$("#TableProductosucursal #trid_"+id+" .producto_id").val();
        ProductosucursalG.precio_venta=$("#TableProductosucursal #trid_"+id+" .precio_venta").text();
        ProductosucursalG.precio_compra=$("#TableProductosucursal #trid_"+id+" .precio_compra").text();   
        ProductosucursalG.estado=$("#TableProductosucursal #trid_"+id+" .estado").val();
    }
    $('#ModalProductosucursal').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxProductosucursal.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProductosucursal.Cargar(HTMLCargarProductosucursal);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProductosucursal.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProductosucursal').modal('hide');
        AjaxProductosucursal.Cargar(HTMLCargarProductosucursal);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarProductosucursal=function(result){
    var html="";
    $('#TableProductosucursal').DataTable().destroy();
    
    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>";
            if(r.foto!=null){    
            html+="<a  target='_blank' href='img/product/"+r.foto+"'><img src='img/product/"+r.foto+"' style='height: 40px;width: 40px;'></a>";}
            html+="</td>"+
            "<td class='producto'>"+r.producto+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='precio_venta'>"+r.precio_venta+"</td>"+
            "<td class='precio_compra'>"+r.precio_compra+"</td>"+
            "<td class='stock'>"+r.stock+"</td>"+
            "<td class='stock_minimo'>"+r.stock_minimo+"</td>"+
            "<td>"+
            "<input type='hidden' class='producto_id' value='"+r.producto_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='moneda' value='"+r.moneda+"'>"+
            "<input type='hidden' class='dias_alerta' value='"+r.dias_alerta+"'>"+
            "<input type='hidden' class='fecha_vencimiento' value='"+r.fecha_vencimiento+"'>"+
            "<input type='hidden' class='dias_vencimiento' value='"+r.dias_vencimiento+"'>";
        if(r.foto!=null){
        html+="<input type='hidden' class='foto' value='"+r.foto+"'>";}

        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableProductosucursal tbody").html(html); 
    $("#TableProductosucursal").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

};

CargarSlct=function(slct){
    if(slct==1){
    AjaxProductosucursal.CargarSucursal(SlctCargarSucursal);
    }
    if(slct==2){
    AjaxProductosucursal.CargarCategoria(SlctCargarCategoria);
    }
    if(slct==3){
    AjaxProductosucursal.CargarArticulo(SlctCargarArticulo);
    }
}

SlctCargarSucursal=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalProductosucursal #slct_sucursal").html(html); 
    $("#ModalProductosucursal #slct_sucursal").selectpicker('refresh');

};

SlctCargarArticulo=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.articulo+"</option>";
    });
    $("#ModalProducto #slct_articulo").html(html); 
    $("#ModalProducto #slct_articulo").selectpicker('refresh');

};

Cargar=function(tipo){
    
    if(tipo==2){
    AjaxCategoria.Cargar(HTMLCargarCategoria1);
    $("#CategoriaForm").css("display","");
    $("#ProductoForm").css("display","none");
    $("#ArticuloForm").css("display","none");
    }
    if(tipo==1){
    AjaxArticulo.Cargar(HTMLCargarArticulo2);
    CargarSlct(2);
    $("#ArticuloForm").css("display","");
    $("#ProductoForm").css("display","none");
    $("#CategoriaForm").css("display","none");
    }
    if(tipo==3){
    AjaxProducto.Cargar(HTMLCargarProducto3);
    CargarSlct(3);
    $("#ArticuloForm").css("display","none");
    $("#CategoriaForm").css("display","none");
    $("#ProductoForm").css("display","");
    }
 
};


</script>
