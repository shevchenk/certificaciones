<script type="text/javascript">
var AjaxEmpresa={
    AgregarEditar:function(evento){
        var data=$("#ModalEmpresaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.EmpresaBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.EmpresaBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        data={};
        $("#EmpresaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.EmpresaBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEmpresaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEmpresaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEmpresaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEmpresaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.EmpresaBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
