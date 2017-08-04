<script type="text/javascript">
var AjaxAgregar_Editar_Empresa={
    AgregarEditar2:function(evento){
        var data=$("#ModalEmpresaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.EmpresaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.EmpresaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },


};
</script>
