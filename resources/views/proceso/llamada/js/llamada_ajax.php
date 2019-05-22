<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@Load';
        masterG.postAjax(url,data,evento);
    },
    ObtenerHora:function(evento){
        url='AjaxDinamic/Proceso.AlumnoPR@ObtenerHora';
        data={};
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
    },
    ListarSubTipoLlamada:function(evento,id){
        url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@ListSubTipoLlamada';
        data={tipo_llamada_id:id};
        masterG.postAjax(url,data,evento);
    },
    ListarDetalleTipoLlamada:function(evento,id){
        url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@ListDetalleTipoLlamada';
        data={tipo_llamada_sub_id:id};
        masterG.postAjax(url,data,evento);
    },
    RegistrarLlamada:function(evento){
        var data=$("#ModalLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.LlamadaPR@RegistrarLlamada';
        masterG.postAjax(url,data,evento);
    }
};
</script>
