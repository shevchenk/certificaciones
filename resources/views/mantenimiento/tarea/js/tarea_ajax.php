<script type="text/javascript">
var AjaxTarea={
    AgregarEditar:function(evento){
        var data=$("#ModalTareaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.TareaMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.TareaMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TareaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#TareaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TareaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TareaMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTareaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTareaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTareaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTareaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TareaMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarRol:function(evento){
        url='AjaxDinamic/Mantenimiento.RolEM@ListRol';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
