<script type="text/javascript">
var AjaxEntidad={
    AgregarEditar:function(evento){
        var data=$("#ModalEntidadForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.EntidadEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.EntidadEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EntidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EntidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EntidadForm input[type='hidden']").remove();
        url='AjaxDinamic/ExpertManage.EntidadEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEntidadForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEntidadForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEntidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEntidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.EntidadEM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};

</script>
