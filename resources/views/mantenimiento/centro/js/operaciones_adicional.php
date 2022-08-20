<script type="text/javascript">

var DistritoOpciones = {
    placeholder: 'Distrito',
    url: "AjaxDinamic/Mantenimiento.PersonaEM@ListDistrito",
    listLocation: "data",
    getValue: "distrito",
    ajaxSettings: { dataType: "json", method: "POST", data: {},
        success: function(r) {
            if(r.data.length==0){ 
                msjG.mensaje('warning',$("#ModalCentroOperacionForm #txt_distrito").val()+' <b>sin resultados</b>',6000);
            }
        }, 
    },
    preparePostData: function(data) {
        data.phrase = $("#ModalCentroOperacionForm #txt_distrito").val();
        return data;
    },
    list: {
        onClickEvent: function() {
            var value = $("#ModalCentroOperacionForm #txt_distrito").getSelectedItemData().id;
            var value2 = $("#ModalCentroOperacionForm #txt_distrito").getSelectedItemData().provincia_id;
            var value3 = $("#ModalCentroOperacionForm #txt_distrito").getSelectedItemData().region_id;
            var value4 = $("#ModalCentroOperacionForm #txt_distrito").getSelectedItemData().provincia;
            var value5 = $("#ModalCentroOperacionForm #txt_distrito").getSelectedItemData().region;
            $("#ModalCentroOperacionForm #txt_distrito_id").val(value).trigger("change");
            $("#ModalCentroOperacionForm #txt_provincia_id").val(value2).trigger("change");
            $("#ModalCentroOperacionForm #txt_region_id").val(value3).trigger("change");
            $("#ModalCentroOperacionForm #txt_provincia").val(value4).trigger("change");
            $("#ModalCentroOperacionForm #txt_region").val(value5).trigger("change");
            $("#ModalCentroOperacionForm #txt_distrito_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        },
        onLoadEvent: function() {
            $("#ModalCentroOperacionForm #txt_distrito_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        },
    },
    template: {
        type: "description",
        fields: {
            description: "detalle"
        }
    },
    adjustWidth:false,
};
</script>
