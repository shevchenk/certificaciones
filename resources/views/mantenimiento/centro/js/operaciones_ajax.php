<script type="text/javascript">
var AjaxCentroOperacion={
    AgregarEditar:function(evento){
        var data=$("#ModalCentroOperacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.CentroOperacionMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.CentroOperacionMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#CentroOperacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#CentroOperacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#CentroOperacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.CentroOperacionMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalCentroOperacionForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalCentroOperacionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalCentroOperacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalCentroOperacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.CentroOperacionMA@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
