<script type="text/javascript">
var AjaxArticulo={
    AgregarEditar:function(evento){
        var data=$("#ModalArticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.ArticuloBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.ArticuloBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento){
        data={};
        url='AjaxDinamic/BasicManage.ArticuloBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalArticuloForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalArticuloForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalArticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalArticuloForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ArticuloBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
