<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@LoadDistribuidaTotal';
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
        url='AjaxDinamic/Proceso.LlamadaPR@RegistrarLlamadaDistribuida';
        masterG.postAjax(url,data,evento);
    },
    CargarLlamada:function(evento){
        var data=$("#ModalLlamadaForm").serialize().split("txt_").join("").split("slct_").join("")+'&estado=1';
        url='AjaxDinamic/Proceso.LlamadaPR@CargarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CargarLlamadaPendiente:function(evento){
        var data={};
        url='AjaxDinamic/Proceso.LlamadaPR@CargarLlamadaPendiente';
        masterG.postAjax(url,data, evento);
    },
    CargarInfo:function(evento){
        var data=$("#ModalLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.LlamadaPR@CargarInfo';
        masterG.postAjax(url,data, evento);
    },
    CargarMatricula:function(evento){
        var data=$("#ModalLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.LlamadaPR@CargarMatricula';
        masterG.postAjax(url,data, evento);
    },
    ActualizarPersona:function(evento){
        $("#ModalPersonaForm").append("<input type='hidden' value='"+$('#ModalLlamadaForm #txt_persona_id').val()+"' name='id'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@EditLibreLlamada';
        masterG.postAjax(url,data,evento);
    },
};
</script>
