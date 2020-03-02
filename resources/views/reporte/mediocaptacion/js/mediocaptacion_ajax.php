<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.ReporteAvanzadoEM@LoadMedioCaptacion';
        data = $("#ReporteForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarEmpresa:function(evento){
        url='AjaxDinamic/Mantenimiento.EmpresaMA@ListEmpresaUsuario';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
