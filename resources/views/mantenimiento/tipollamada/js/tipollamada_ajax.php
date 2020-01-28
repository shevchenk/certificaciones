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

var AjaxTipoLlamadaSub={
    AgregarEditar:function(evento){
        $("#ModalTipoLlamadaSubForm").append("<input type='hidden' value='"+IdTipoLlamadaG+"' id='tipo_llamada_id' name='tipo_llamada_id'>");
        var data=$("#ModalTipoLlamadaSubForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoLlamadaSubForm #tipo_llamada_id").remove();
        url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TipoLlamadaSubForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#TipoLlamadaSubForm").append("<input type='hidden' value='"+IdTipoLlamadaG+"' name='tipo_llamada_id'>");
        data=$("#TipoLlamadaSubForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TipoLlamadaSubForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTipoLlamadaSubForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTipoLlamadaSubForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTipoLlamadaSubForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoLlamadaSubForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};

var AjaxTipoLlamadaSubDetalle={
    AgregarEditar:function(evento){
        $("#ModalTipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+IdTipoLlamadaSubG+"' id='tipo_llamada_sub_id' name='tipo_llamada_sub_id'>");
        var data=$("#ModalTipoLlamadaSubDetalleForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoLlamadaSubDetalleForm #tipo_llamada_sub_id").remove();
        url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#TipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+IdTipoLlamadaSubG+"' name='tipo_llamada_sub_id'>");
        data=$("#TipoLlamadaSubDetalleForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TipoLlamadaSubDetalleForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTipoLlamadaSubDetalleForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTipoLlamadaSubDetalleForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoLlamadaSubDetalleForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
