<script type="text/javascript">
var AjaxAgregar_Editar_Categoria={
    AgregarEditar4:function(evento){
        var data=$("#ModalCategoriaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.CategoriaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.CategoriaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
   
};
</script>
