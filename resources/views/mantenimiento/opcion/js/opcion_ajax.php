<script type="text/javascript">
var AjaxOpcion={
    AgregarEditar:function(evento){
        var data=$("#ModalOpcionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.OpcionMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.OpcionMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.OpcionMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalOpcionForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalOpcionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalOpcionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalOpcionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.OpcionMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.OpcionMA@ListOpcion';
        data={};
        masterG.postAjax(url,data,evento);
    }
};
</script>
