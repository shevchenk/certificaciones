<script type="text/javascript">
var AjaxCliente={
    AgregarEditar:function(evento){
        var data=$("#ModalClienteForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.ClienteBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.ClienteBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        url='AjaxDinamic/BasicManage.ClienteBM@Load';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalClienteForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalClienteForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalClienteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalClienteForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ClienteBM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
