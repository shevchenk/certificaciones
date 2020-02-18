<script type="text/javascript">
var PersonaAdicionalG = {colegio_id:"",
    pais_id:"",
    region_id:"",
    provincia_id:"",
    distrito_id:"",
    region_id_dir:"",
    provincia_id_dir:"",
    distrito_id_dir:"",
    direccion:"",
    tenencia:0,
    empresa_laboral:"",
    empresa_direccion:"",
    empresa_telefono:""
}; // Datos Adicionales

var ColegioOpciones = {
    placeholder: 'Colegio',
    url: "AjaxDinamic/Mantenimiento.PersonaEM@ListColegio",
    listLocation: "data",
    getValue: "colegio",
    ajaxSettings: { dataType: "json", method: "POST", data: {},
        success: function(r) {
            if(r.data.length==0){ 
                msjG.mensaje('warning',$("#ModalPersonaForm #txt_colegio").val()+' <b>sin resultados</b>',6000);
            }
        }, 
    },
    preparePostData: function(data) {
        data.phrase = $("#ModalPersonaForm #txt_colegio").val();
        return data;
    },
    list: {
        onClickEvent: function() {
            var value = $("#ModalPersonaForm #txt_colegio").getSelectedItemData().id;
            $("#ModalPersonaForm #txt_colegio_id").val(value).trigger("change");
            $("#ModalPersonaForm #txt_colegio_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        },
        onLoadEvent: function() {
            $("#ModalPersonaForm #txt_colegio_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        },
    },
    template: {
        type: "description",
        fields: {
            description: "distrito"
        }
    },
    adjustWidth:false,
};
var PaisOpciones = {
    placeholder: 'Pais',
    url: "AjaxDinamic/Mantenimiento.PersonaEM@ListPais",
    listLocation: "data",
    getValue: "pais",
    ajaxSettings: { dataType: "json", method: "POST", data: {},
        success: function(r) {
            if(r.data.length==0){ 
                msjG.mensaje('warning',$("#ModalPersonaForm #txt_pais").val()+' <b>sin resultados</b>',6000);
            }
        }, 
    },
    preparePostData: function(data) {
        data.phrase = $("#ModalPersonaForm #txt_pais").val();
        return data;
    },
    list: {
        onClickEvent: function() {
            var value = $("#ModalPersonaForm #txt_pais").getSelectedItemData().id;
            $("#ModalPersonaForm #txt_pais_id").val(value).trigger("change");

            $(".paisafectado input").removeAttr('disabled');
            if( $("#ModalPersonaForm #txt_pais").getSelectedItemData().id!=173 ){
                $(".paisafectado input,.paisafectado2 input").val('').attr('disabled','true');
            }
            $("#ModalPersonaForm #txt_pais_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        },
        onLoadEvent: function() {
            $("#ModalPersonaForm #txt_pais_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        },
    },
    template: {
        type: "description",
        fields: {
            description: "abreviatura"
        }
    },
    adjustWidth:false,
};
var DistritoOpciones = {
    placeholder: 'Distrito',
    url: "AjaxDinamic/Mantenimiento.PersonaEM@ListDistrito",
    listLocation: "data",
    getValue: "distrito",
    ajaxSettings: { dataType: "json", method: "POST", data: {},
        success: function(r) {
            if(r.data.length==0){ 
                msjG.mensaje('warning',$("#ModalPersonaForm #txt_distrito").val()+' <b>sin resultados</b>',6000);
            }
        }, 
    },
    preparePostData: function(data) {
        data.phrase = $("#ModalPersonaForm #txt_distrito").val();
        return data;
    },
    list: {
        onClickEvent: function() {
            var value = $("#ModalPersonaForm #txt_distrito").getSelectedItemData().id;
            var value2 = $("#ModalPersonaForm #txt_distrito").getSelectedItemData().provincia_id;
            var value3 = $("#ModalPersonaForm #txt_distrito").getSelectedItemData().region_id;
            var value4 = $("#ModalPersonaForm #txt_distrito").getSelectedItemData().provincia;
            var value5 = $("#ModalPersonaForm #txt_distrito").getSelectedItemData().region;
            $("#ModalPersonaForm #txt_distrito_id").val(value).trigger("change");
            $("#ModalPersonaForm #txt_provincia_id").val(value2).trigger("change");
            $("#ModalPersonaForm #txt_region_id").val(value3).trigger("change");
            $("#ModalPersonaForm #txt_provincia").val(value4).trigger("change");
            $("#ModalPersonaForm #txt_region").val(value5).trigger("change");
            $("#ModalPersonaForm #txt_distrito_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        },
        onLoadEvent: function() {
            $("#ModalPersonaForm #txt_distrito_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
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
var DistritoDirOpciones = {
    placeholder: 'Distrito',
    url: "AjaxDinamic/Mantenimiento.PersonaEM@ListDistrito",
    listLocation: "data",
    getValue: "distrito",
    ajaxSettings: { dataType: "json", method: "POST", data: {},
        success: function(r) {
            if(r.data.length==0){ 
                msjG.mensaje('warning',$("#ModalPersonaForm #txt_distrito_dir").val()+' <b>sin resultados</b>',6000);
            }
        }, 
    },
    preparePostData: function(data) {
        data.phrase = $("#ModalPersonaForm #txt_distrito_dir").val();
        return data;
    },
    list: {
        onClickEvent: function() {
            var value = $("#ModalPersonaForm #txt_distrito_dir").getSelectedItemData().id;
            var value2 = $("#ModalPersonaForm #txt_distrito_dir").getSelectedItemData().provincia_id;
            var value3 = $("#ModalPersonaForm #txt_distrito_dir").getSelectedItemData().region_id;
            var value4 = $("#ModalPersonaForm #txt_distrito_dir").getSelectedItemData().provincia;
            var value5 = $("#ModalPersonaForm #txt_distrito_dir").getSelectedItemData().region;
            $("#ModalPersonaForm #txt_distrito_id_dir").val(value).trigger("change");
            $("#ModalPersonaForm #txt_provincia_id_dir").val(value2).trigger("change");
            $("#ModalPersonaForm #txt_region_id_dir").val(value3).trigger("change");
            $("#ModalPersonaForm #txt_provincia_dir").val(value4).trigger("change");
            $("#ModalPersonaForm #txt_region_dir").val(value5).trigger("change");
            $("#ModalPersonaForm #txt_distrito_dir_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        },
        onLoadEvent: function() {
            $("#ModalPersonaForm #txt_distrito_dir_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
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

$(document).ready(function() {

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#ModalPersonaForm #txt_colegio").easyAutocomplete(ColegioOpciones);
    $("#ModalPersonaForm #txt_pais").easyAutocomplete(PaisOpciones);
    $("#ModalPersonaForm #txt_distrito").easyAutocomplete(DistritoOpciones);
    $("#ModalPersonaForm #txt_distrito_dir").easyAutocomplete(DistritoDirOpciones);
    

    $('#ModalPersona').on('show.bs.modal', function (event) {
        CargarModal();
        if( AddEdit==1 ){
            $("#ModalPersonaForm #txt_distrito_dir_ico, #ModalPersonaForm #txt_distrito_ico, #ModalPersonaForm #txt_pais_ico, #ModalPersonaForm #txt_colegio_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        }
        else{
            $("#ModalPersonaForm #txt_distrito_dir_ico, #ModalPersonaForm #txt_distrito_ico, #ModalPersonaForm #txt_pais_ico, #ModalPersonaForm #txt_colegio_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        }
    });

});

CargarModal=function(){
    if( AddEdit==0 ){
        AjaxPersonaModal.CargarPersonaAdicional(HTMLPersonaAdicional);
    }
}

HTMLPersonaAdicional=function(result){
    $(".paisafectado input,.paisafectado2 input").attr('disabled','true');
    $('#ModalPersonaForm #txt_pais_id').val( '' );
    $('#ModalPersonaForm #txt_colegio_id').val( '' );
    $('#ModalPersonaForm #txt_region_id').val( '' );
    $('#ModalPersonaForm #txt_provincia_id').val( '' );
    $('#ModalPersonaForm #txt_distrito_id').val( '' );
    $('#ModalPersonaForm #txt_region_id_dir').val( '' );
    $('#ModalPersonaForm #txt_provincia_id_dir').val( '' );
    $('#ModalPersonaForm #txt_distrito_id_dir').val( '' );
    $('#ModalPersonaForm #txt_direccion_dir').val( '' );
    $('#ModalPersonaForm #txt_referencia_dir').val( '' );
    $('#ModalPersonaForm #slct_tenencia').val( '0' );
    $('#ModalPersonaForm #slct_estado_civil').val( 'S' );
    $('#ModalPersonaForm #txt_fecha_nacimiento').val( '' );

    $('#ModalPersonaForm #txt_pais').val( '' );
    $('#ModalPersonaForm #txt_colegio').val( '' );
    $('#ModalPersonaForm #txt_region').val( '' );
    $('#ModalPersonaForm #txt_provincia').val( '' );
    $('#ModalPersonaForm #txt_distrito').val( '' );
    $('#ModalPersonaForm #txt_region_dir').val( '' );
    $('#ModalPersonaForm #txt_provincia_dir').val( '' );
    $('#ModalPersonaForm #txt_distrito_dir').val( '' );
    if( result.data!=null ){
        $('#ModalPersonaForm #txt_pais_id').val( $.trim(result.data.pais_id) );
        $('#ModalPersonaForm #txt_colegio_id').val( $.trim(result.data.colegio_id) );
        $('#ModalPersonaForm #txt_region_id').val( $.trim(result.data.region_id) );
        $('#ModalPersonaForm #txt_provincia_id').val( $.trim(result.data.provincia_id) );
        $('#ModalPersonaForm #txt_distrito_id').val( $.trim(result.data.distrito_id) );
        $('#ModalPersonaForm #txt_region_id_dir').val( $.trim(result.data.region_id_dir) );
        $('#ModalPersonaForm #txt_provincia_id_dir').val( $.trim(result.data.provincia_id_dir) );
        $('#ModalPersonaForm #txt_distrito_id_dir').val( $.trim(result.data.distrito_id_dir) );
        $('#ModalPersonaForm #txt_direccion_dir').val( $.trim(result.data.direccion_dir) );
        $('#ModalPersonaForm #txt_referencia_dir').val( $.trim(result.data.referencia_dir) );
        $('#ModalPersonaForm #slct_tenencia').val( $.trim(result.data.tenencia) );
        $('#ModalPersonaForm #slct_estado_civil').val( $.trim(result.data.estado_civil) );
        $('#ModalPersonaForm #txt_fecha_nacimiento').val( $.trim(result.data.fecha_nacimiento) );

        $('#ModalPersonaForm #txt_pais').val( $.trim(result.data.pais) );
        $('#ModalPersonaForm #txt_colegio').val( $.trim(result.data.colegio) );
        $('#ModalPersonaForm #txt_region').val( $.trim(result.data.region) );
        $('#ModalPersonaForm #txt_provincia').val( $.trim(result.data.provincia) );
        $('#ModalPersonaForm #txt_distrito').val( $.trim(result.data.distrito) );
        $('#ModalPersonaForm #txt_region_dir').val( $.trim(result.data.region_dir) );
        $('#ModalPersonaForm #txt_provincia_dir').val( $.trim(result.data.provincia_dir) );
        $('#ModalPersonaForm #txt_distrito_dir').val( $.trim(result.data.distrito_dir) );
        
        $(".paisafectado input").removeAttr('disabled');
        if( $.trim(result.data.pais_id)!=173 ){
            $(".paisafectado input,.paisafectado2 input").attr('disabled','true');
        }
    }
    $("#ModalPersonaForm select").not('.mant').selectpicker('refresh');
}

LimpiarPersonaModal=function(limpiar){
    $("#"+limpiar).val('');
}
</script>
