<script type="text/javascript">
var AjaxProveedorDetalle={
    AgregarEditar:function(evento){
        var data=$("#ModalProveedorDetalleForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.ProveedorDetalleBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.ProveedorDetalleBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },

    Cargar:function(evento){  
        url='AjaxDinamic/BasicManage.ProveedorDetalleBM@Load';
       var id=$("#ProveedorDetalleForm #txt_proveedor_id").val();
     


        var data={'id':id};

        $("#ProveedorDetalleForm input[type='hidden']").not('.mant').remove();
        
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProveedorDetalleForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProveedorDetalleForm").append("<input type='hidden' value='"+id+"' name='id'>");
  
        var data=$("#ModalProveedorDetalleForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProveedorDetalleForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProveedorDetalleBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
