<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.SeminarioEM@LoadAsesoria';
        data = $("#PaeForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },
    CargarEspecialidad:function(evento){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidad';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@CargarLlamada';
        masterG.postAjax(url,data, evento);
    },
    RegistrarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@RegistrarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CerrarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@CerrarLlamada';
        masterG.postAjax(url,data, evento);
    },
};
</script>
