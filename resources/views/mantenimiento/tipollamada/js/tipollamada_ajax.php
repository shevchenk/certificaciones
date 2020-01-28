<script type="text/javascript">
var AjaxTipoLlamada={
    AgregarEditar:function(evento){
        var data=$("#ModalTipoLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TipoLlamadaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#TipoLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TipoLlamadaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTipoLlamadaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTipoLlamadaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTipoLlamadaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoLlamadaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
