<script type="text/javascript">
var AjaxSucursal={
    AgregarEditar:function(evento){
        var data=$("#ModalSucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.SucursalBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.SucursalBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    }, 
    Cargar:function(evento,pag){
        data={};
        $("#SucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.SucursalBM@Load';
        masterG.postAjax(url,data,evento);
        
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalSucursalForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalSucursalForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalSucursalForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalSucursalForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.SucursalBM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script> 
