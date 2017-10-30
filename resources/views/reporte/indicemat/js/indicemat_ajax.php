<script type="text/javascript">
var AjaxIndice={
    Cargar:function(evento){
        data=$("#IndiceMatForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@LoadIndiceMat';
        masterG.postAjax(url,data,evento);
    },
};
</script>
