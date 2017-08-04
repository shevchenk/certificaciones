<script type="text/javascript">
var AjaxProductosucursal={
    AgregarEditar:function(evento){
        var data=$("#ModalProductosucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.ProductoSucursalBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.ProductoSucursalBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        data={};
        $("#ProductosucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProductoSucursalBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProductosucursalForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProductosucursalForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProductosucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProductosucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProductoSucursalBM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/BasicManage.SucursalBM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCategoria:function(evento){
        url='AjaxDinamic/BasicManage.CategoriaBM@ListCategoria';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarArticulo:function(evento){
        url='AjaxDinamic/BasicManage.ArticuloBM@ListArticulo';
        data={};
        masterG.postAjax(url,data,evento);
    }
};
</script>
