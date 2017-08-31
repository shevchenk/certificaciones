<script type="text/javascript">
var AjaxBandeja={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadDistribucion';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,id){
        $("#ModalDistribucionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        $("#ModalDistribucionForm").append("<input type='hidden' value='5' name='estadof'>");
        var data=$("#ModalDistribucionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalDistribucionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatusDistribucion';
        masterG.postAjax(url,data,evento);
    }
};
</script>
