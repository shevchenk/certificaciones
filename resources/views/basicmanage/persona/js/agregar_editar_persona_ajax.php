<script type="text/javascript">
var AjaxAgregar_Editar_Persona={
    AgregarEditar1:function(evento){
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.PersonaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.PersonaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },


};
</script>
