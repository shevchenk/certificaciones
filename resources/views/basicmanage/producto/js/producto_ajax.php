<script type="text/javascript">
var AjaxProducto={
    AgregarEditar:function(evento){
        var data=$("#ModalProductoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.ProductoBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.ProductoBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        data={};
        $("#ProductoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProductoBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProductoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProductoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProductoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProductoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProductoBM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
