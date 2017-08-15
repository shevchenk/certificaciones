<script type="text/javascript">
var AjaxCertificadoEstado={
    AgregarEditar:function(evento){
        var data=$("#ModalCertificadoEstadoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.CertificadoEstadoMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.CertificadoEstadoMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#CertificadoEstadoForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#CertificadoEstadoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#CertificadoEstadoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.CertificadoEstadoMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalCertificadoEstadoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalCertificadoEstadoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalCertificadoEstadoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalCertificadoEstadoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.CertificadoEstadoMA@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
