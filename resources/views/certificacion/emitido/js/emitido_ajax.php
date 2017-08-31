<script type="text/javascript">
var AjaxBandeja={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadEmitido';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstadoa4:function(evento,id){
        $("#ModalBandejaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalBandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalBandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatusa4';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstadoa6:function(evento,id){
        $("#ModalBandejaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalBandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalBandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatusa6';
        masterG.postAjax(url,data,evento);
    }
};
</script>
