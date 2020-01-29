<script type="text/javascript">
var AjaxVisita={
    Cargar:function(evento){
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@CalcularAsignados';
        masterG.postAjax(url,data,evento);
    },
    Trabajadores:function(evento){
        data={empresa: $("#slct_empresas").val()};
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@ListarTeleoperadores';
        masterG.postAjax(url,data,evento);
    },
    Guardar:function(evento){
        var data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.LlamadaPR@GuardarAsignacion';
        masterG.postAjax(url,data,evento,null,false);
    },
    CargarEmpresas:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListEmpresaUsuario';
        data={};
        masterG.postAjax(url,data,evento,null,false);
    },
    ListarRegion:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListarRegion';
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento,null,false);
    },
    ListarCampana:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListarCampana';
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento,null,false);
    },
};
</script>
