<script type="text/javascript">
var AjaxTrabajador={
    AgregarEditar:function(evento){
        var data=$("#ModalTrabajadorForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.TrabajadorEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TrabajadorForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#TrabajadorForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TrabajadorForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTrabajadorForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTrabajadorForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTrabajadorForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTrabajadorForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarRol:function(evento){
        url='AjaxDinamic/Mantenimiento.RolEM@ListRol';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarTarea:function(evento,val){
        url='AjaxDinamic/Mantenimiento.TareaEM@ListTarea';
        data={rol_id:val};
        masterG.postAjax(url,data,evento);
    }
};
</script>
