<script type="text/javascript">
var AjaxRol={
    AgregarEditar:function(evento){
        var data=$("#ModalRolForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.RolMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.RolMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#RolForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#RolForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#RolForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.RolMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalRolForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalRolForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalRolForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalRolForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.RolMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
