<script type="text/javascript">
var AjaxLlamada={
    HistorialLlamada:function(evento,pag){
        $("#ListaprogramacionForm").append("<input type='hidden' value='"+LlamadaG.persona_id+"' name='trabajador_id'>");
        data=$("#ListaprogramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaprogramacionForm input[type='hidden']").remove();
        url='AjaxDinamic/Proceso.LlamadaPR@HistorialLlamada';
        masterG.postAjax(url,data,evento);
    }
};
</script>
