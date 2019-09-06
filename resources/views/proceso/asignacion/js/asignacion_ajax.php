<script type="text/javascript">
var AjaxVisita={
    Cargar:function(evento){
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@CalcularAsignados';
        masterG.postAjax(url,data,evento);
    },
    Trabajadores:function(evento){
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@ListarTeleoperadores';
        masterG.postAjax(url,data,evento);
    },
    Guardar:function(evento){
        var data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.LlamadaPR@GuardarAsignacion';
        masterG.postAjax(url,data,evento,null,false);
    },
};
</script>
