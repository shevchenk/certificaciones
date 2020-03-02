<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.ReporteAvanzadoEM@LoadVendedorComision';
        data = $("#ReporteForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },
    CargarMedioCaptacion:function(evento){
        url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@ListMedioCaptacion';
        data={tipo_medio:1};
        masterG.postAjax(url,data,evento);
    },
    CargarEmpresa:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListEmpresaUsuario';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
