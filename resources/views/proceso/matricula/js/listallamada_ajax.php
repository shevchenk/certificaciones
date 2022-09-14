<script type="text/javascript">
var AjaxLlamada={
    HistorialLlamada:function(evento,pag){
        $("#LlamadaForm").append("<input type='hidden' value='"+LlamadaG.persona_id+"' name='trabajador_id'>");
        data=$("#LlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#LlamadaForm input[type='hidden']").remove();
        url='AjaxDinamic/Proceso.LlamadaPR@HistorialLlamada';
        masterG.postAjax(url,data,evento);
    }
};
</script>
