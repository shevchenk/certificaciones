<script type="text/javascript">
var AjaxBandeja={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadRecibeOde';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,id){
        $("#ModalDistribucionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        $("#ModalDistribucionForm").append("<input type='hidden' value='6' name='certificado_estado_id'>");
        var data=$("#ModalDistribucionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalDistribucionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatusRecibeOde';
        masterG.postAjax(url,data,evento);
    }
};
</script>
