<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.SeminarioEM@LoadControlPago';
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
    GuardarArchivo:function(evento, data){
        url='AjaxDinamic/Proceso.CargarCertificadoPR@GuardarArchivo';
        masterG.postAjax(url,data,evento);
    },
    EliminarArchivo:function(evento, data){
        url='AjaxDinamic/Proceso.CargarCertificadoPR@EliminarArchivo';
        masterG.postAjax(url,data,evento);
    },
};
</script>
