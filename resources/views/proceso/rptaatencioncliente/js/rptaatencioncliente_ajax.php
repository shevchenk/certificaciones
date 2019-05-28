<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#EspecialidadForm").append("<input type='hidden' value='1' name='solopendiente'>");
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@CargarLlamada';
        masterG.postAjax(url,data,evento);
    },
    ResponderLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@ResponderLlamada';
        masterG.postAjax(url,data, evento);
    },
    
};
</script>
