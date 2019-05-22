<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@Load';
        masterG.postAjax(url,data,evento);
    },
    ListarTeleoperadora:function(evento){
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@ListarTeleoperadora';
        data={rol_id:1};
        masterG.postAjax(url,data,evento);
    },
    ListarTipoLlamada:function(evento){
        url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@ListTipoLlamada';
        data={rol_id:1};
        masterG.postAjax(url,data,evento);
    }
};
</script>
