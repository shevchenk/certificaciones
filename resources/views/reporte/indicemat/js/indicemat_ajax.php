<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.ReporteAvanzadoEM@LoadIndiceMatriculacion';
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
    CargarEspecialidad:function(evento){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidad';
        data={global:1};
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={global:1};
        masterG.postAjax(url,data,evento);
    },
};
</script>
