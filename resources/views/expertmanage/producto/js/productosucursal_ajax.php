<script type="text/javascript">
var AjaxProductosucursal={
    AgregarEditar:function(evento){
        var data=$("#ModalProductosucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.ProductosucursalEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.ProductoSucursalEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProductosucursalForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ProductosucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProductosucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProductoSucursalEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProductosucursalForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProductosucursalForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProductosucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProductosucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProductoSucursalEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/ExpertManage.SucursalEM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCategoria:function(evento){
        url='AjaxDinamic/ExpertManage.CategoriaEM@ListCategoria';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarArticulo:function(evento){
        url='AjaxDinamic/ExpertManage.ArticuloEM@ListArticulo';
        data={};
        masterG.postAjax(url,data,evento);
    }
};
</script>
