<script type="text/javascript">
var AjaxCliente={
    AgregarEditar:function(evento){
        var data=$("#ModalClienteForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.ClienteEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.ClienteEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ClienteForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ClienteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ClienteForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ClienteEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalClienteForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalClienteForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalClienteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalClienteForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ClienteEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
