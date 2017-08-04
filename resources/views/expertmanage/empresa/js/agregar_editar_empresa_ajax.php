<script type="text/javascript">
var AjaxAgregar_Editar_Empresa={
    AgregarEditar2:function(evento){
        var data=$("#ModalEmpresaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.EmpresaEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.EmpresaEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },


};
</script>
