<script type="text/javascript">
var AjaxBandeja={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadValidaPagoAlumno';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,id){
        $("#ModalBandejaForm").append("<input type='hidden' value='"+id+"' name='id[]'>");
        var data=$("#ModalBandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalBandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstadoMasivo:function(evento){
        var data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
