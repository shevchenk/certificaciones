<script type="text/javascript">
var AjaxBandeja={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#BandejaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@LoadPagoAlumno';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,id){
        $("#ModalBoletaForm").append("<input type='hidden' value='7' name='certificado_estado_id'>");
        //$("#ModaPagoAlumnoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalBoletaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModaPagoAlumnoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Certificacion.BandejaCE@EditStatusa6';
        masterG.postAjax(url,data,evento);
    }

};

</script>
