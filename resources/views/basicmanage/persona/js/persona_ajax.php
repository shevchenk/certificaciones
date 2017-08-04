<script type="text/javascript">
var AjaxPersona={
    AgregarEditar:function(evento){
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.PersonaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.PersonaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento){
        data={};
        $("#PersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.PersonaBM@Load';
        masterG.postAjax(url,data,evento);

        
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalPersonaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalPersonaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.PersonaBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
