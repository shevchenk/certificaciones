<script type="text/javascript">
var AjaxEspecialidadProgramacion={
    AgregarEditar:function(evento){
        var data=$("#ModalEspecialidadProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.EspecialidadProgramacionEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.EspecialidadProgramacionEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadProgramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEspecialidadProgramacionForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEspecialidadProgramacionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEspecialidadProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEspecialidadProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadProgramacionEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarEspecialidad:function(evento){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidad';
        data={};
        masterG.postAjax(url,data,evento);
    }
};
</script>
