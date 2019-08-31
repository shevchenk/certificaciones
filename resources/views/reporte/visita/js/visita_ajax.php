<script type="text/javascript">
var AjaxVisita={
    Cargar:function(evento){
        data=$("#IndiceMatForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@LoadVisita';
        masterG.postAjax(url,data,evento);
    },
};
</script>
