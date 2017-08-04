<script type="text/javascript">
var AjaxArticulo={
    AgregarEditar:function(evento){
        var data=$("#ModalArticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.ArticuloEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.ArticuloEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ArticuloForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ArticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ArticuloForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ArticuloEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalArticuloForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalArticuloForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalArticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalArticuloForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ArticuloEM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
