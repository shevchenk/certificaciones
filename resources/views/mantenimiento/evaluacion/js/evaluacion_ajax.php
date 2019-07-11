<script type="text/javascript">
var AjaxProgramacion={
    AgregarEditar:function(evento){
        var data=$("#ModalProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@ProgramarEvaluacion';
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProgramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CargarTipoEvaluacion:function(evento){
        url='AjaxDinamic/Mantenimiento.TipoEvaluacionMA@ListTipoEvaluacion';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
