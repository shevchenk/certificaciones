<script type="text/javascript">
var AjaxCategoria={
    AgregarEditar:function(evento){
        var data=$("#ModalCategoriaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.CategoriaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.CategoriaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento){
        data={};
        url='AjaxDinamic/BasicManage.CategoriaBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalCategoriaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalCategoriaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalCategoriaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalCategoriaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.CategoriaBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
