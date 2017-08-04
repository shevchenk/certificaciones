<script type="text/javascript">
var AjaxProducto={
    AgregarEditar:function(evento){
        var data=$("#ModalProductoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.ProductoEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.ProductoEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProductoForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ProductoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProductoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProductoEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProductoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProductoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProductoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProductoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProductoEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
