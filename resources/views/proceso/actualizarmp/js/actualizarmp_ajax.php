<script type="text/javascript">
var MPAjax={
    Cargar:function(evento){
        url='AjaxDinamic/Proceso.MPPR@CargarMP';
        data = $("#PaeForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },

    CargarEspecialidad:function(evento){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidad';
        data={};
        masterG.postAjax(url,data,evento);
    },

    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },

    CargarProgramacion:function(evento, data){
        url='AjaxDinamic/Proceso.MPPR@CargarProgramacionMP';
        masterG.postAjax(url,data,evento);
    },

    Actualizar:function(evento){
        url='AjaxDinamic/Proceso.MPPR@ActualizarMP';
        data = $("#MPForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },
};
</script>
