<script type="text/javascript">
var AjaxBandeja={
    AgregarEditar:function(evento){
        var data=$("#ModalVerentregavalidaalumnoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Certificacion.PagoAlumnoDetalleCE@New';
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadVerEntregaValidaAlumno';
        masterG.postAjax(url,data,evento);
    },
    CargarContesta:function(evento){
        url='AjaxDinamic/Mantenimiento.ContestaEM@ListContesta';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarDetalle:function(evento,certificado_id){
        url='AjaxDinamic/Certificacion.PagoAlumnoDetalleCE@Load';
        data={certificado_id:certificado_id};
        masterG.postAjax(url,data,evento);
    },
};
</script>
