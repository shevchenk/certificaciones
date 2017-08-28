<script type="text/javascript">
var AjaxNotas={
    Cargar:function(data,evento){
//        data=$("#NotasForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Reporte.NotasEM@LoadNOTAS';
        masterG.postAjax(url,data,evento);
    },
};
</script>
