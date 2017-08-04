<script type="text/javascript">
var AjaxProveedor={
    AgregarEditar:function(evento){
        var data=$("#ModalProveedorForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.ProveedorEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.ProveedorEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProveedorForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ProveedorForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProveedorForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProveedorEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProveedorForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProveedorForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProveedorForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProveedorForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.ProveedorEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },

};
</script>
