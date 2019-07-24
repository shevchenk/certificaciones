<script type="text/javascript">
var AjaxListaespecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaespecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+$("#div_cursos_progra #txt_persona_id").val()+"' name='persona_id'>");
        data=$("#ListaespecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaespecialidadForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidadDisponible';
        masterG.postAjax(url,data,evento);
    },
    CambiarEspecialidad:function(evento,id){
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+$("#div_cursos_progra #txt_matricula_id").val()+"' name='matricula_id'>");
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+$("#div_cursos_progra #txt_persona_id").val()+"' name='persona_id'>");
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+id.split("_")[0]+"' name='especialidad_id'>");
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+id.split("_")[1]+"' name='especialidad_programacion_id'>");
        data=$("#ListaespecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaespecialidadForm input[type='hidden']").remove();
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@CambiarEspecialidad';
        masterG.postAjax(url,data,evento);
    }
};
</script>
