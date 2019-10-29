<script type="text/javascript">
var AjaxAprobados={
    Cargar:function(evento){
        data=$("#AsignacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@Aprobados';
        masterG.postAjax(url,data,evento);
    },
    CargarEmpresas:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListEmpresa';
        data={};
        masterG.postAjax(url,data,evento,null,false);
    },
};
</script>
