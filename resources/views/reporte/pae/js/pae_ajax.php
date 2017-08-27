<script type="text/javascript">
var AjaxPae={
    Cargar:function(data,evento){
//        data=$("#PaeForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.ReporteEM@LoadPAE';
        masterG.postAjax(url,data,evento);
    },
};
</script>
