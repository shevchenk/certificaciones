<script type="text/javascript">
var persona_id, cargos_selec=[], PersonaObj,SlctItem='',SlctAreasMarcadas="";
var AddEdit=0; //0: Editar | 1: Agregar
var PersonaG={id:0,
paterno:"",
materno:"",
nombre:"",
dni:"",
sexo:0,
email:"",
password:"",
telefono:"",
celular:"",
fecha_nacimiento:"",
estado:1}; // Datos Globales

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
    $(".fechas").val('');
    $(".fechas").datetimepicker({
        format: "hh:ii",
        language: 'es',
        time:true,
        minView:0,
        startView:1,
        minuteStep:15,
        autoclose: true,
        todayBtn: false
    });

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#TablePersona").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxPersona.Cargar(HTMLCargarPersona);
    AjaxPersona.CargarSucursal(SlctCargarSucursal);
    AjaxPersona.CargarMedioPublicitario(SlctCargarMedioPublicitario);
    AjaxPersona.ListarTipoLlamada(SlctListarTipoLlamada);
    $("#ModalPersonaForm #txt_distrito_dir").easyAutocomplete(DistritoDirOpciones);
    $("#PersonaForm #TablePersona select").change(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });
    $("#PersonaForm #TablePersona input").blur(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });

    $('#ModalPersona').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
            $("#ModalPersonaForm #txt_distrito_dir_ico").removeClass('has-success').addClass("has-error").find('span').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalPersonaForm #txt_distrito_dir_ico").removeClass('has-error').addClass("has-success").find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
            $("#ModalPersonaForm").append("<input type='hidden' value='"+PersonaG.id+"' name='id'>");
        }

        $('#ModalPersonaForm #txt_paterno').val( PersonaG.paterno );
        $('#ModalPersonaForm #txt_materno').val( PersonaG.materno );
        $('#ModalPersonaForm #txt_nombre').val( PersonaG.nombre );
        $('#ModalPersonaForm #txt_dni').val( PersonaG.dni );
        $('#ModalPersonaForm #slct_sexo').val( PersonaG.sexo );
        $('#ModalPersonaForm #txt_email').val( PersonaG.email );
        $('#ModalPersonaForm #txt_telefono').val( PersonaG.telefono );
        $('#ModalPersonaForm #txt_password').val( PersonaG.password );
        $('#ModalPersonaForm #txt_celular').val( PersonaG.celular );        
        $('#ModalPersonaForm #txt_fecha_nacimiento').val( PersonaG.fecha_nacimiento );
        $('#ModalPersonaForm #txt_carrera').val( PersonaG.carrera );
        $('#ModalPersonaForm #slct_estado').val( PersonaG.estado );
        $('#ModalPersonaForm #slct_medio_publicitario').val( PersonaG.medio_publicitario_id );
        $('#ModalPersonaForm #slct_sucursal').val( PersonaG.sucursal_id );
        $('#ModalPersonaForm #txt_hora_inicio').val( PersonaG.hora_inicio );
        $('#ModalPersonaForm #txt_hora_final').val( PersonaG.hora_final );
        $('#ModalPersonaForm #txt_region_id_dir').val( PersonaG.region_id_dir );
        $('#ModalPersonaForm #txt_provincia_id_dir').val( PersonaG.provincia_id_dir );
        $('#ModalPersonaForm #txt_distrito_id_dir').val( PersonaG.distrito_id_dir );
        $('#ModalPersonaForm #txt_region_dir').val( PersonaG.region_dir );
        $('#ModalPersonaForm #txt_provincia_dir').val( PersonaG.provincia_dir );
        $('#ModalPersonaForm #txt_distrito_dir').val( PersonaG.distrito_dir );
        $('#ModalPersonaForm #txt_referencia_dir').val( PersonaG.referencia_dir );
        $("#ModalPersona select").selectpicker('refresh');
        $('#ModalPersonaForm #slct_dia').selectpicker( 'val',PersonaG.dia.split(",") );
        $('#ModalPersonaForm #slct_tipo_llamada').selectpicker( 'val',PersonaG.tipo_llamada );
        $('#ModalPersonaForm #slct_sub_tipo_llamada').selectpicker( 'val',PersonaG.tipo_llamada_sub );
        $('#ModalPersonaForm #slct_detalle_tipo_llamada').selectpicker( 'val',PersonaG.tipo_llamada_sub_detalle );
        $('#ModalPersonaForm #txt_fechas').val( PersonaG.fechas );
        ActivarComentario();
        $('#ModalPersonaForm #txt_nombre').focus();
    });

    $('#ModalPersona').on('hidden.bs.modal', function (event) {
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        $('#slct_cargos,#slct_rol,#slct_area').selectpicker('destroy');
        $("#ModalPersonaForm #t_cargoPersona").html('');
    });
});

ActivarComentario=function(){
    codigo=$("#slct_tipo_llamada option:selected").attr('data-obs');
    id=$("#slct_tipo_llamada").val();
    $('.tipo1, .tipo2').css('display','none');

    if(codigo==1 || codigo==2){
        $('.tipo1').css('display','block');
        $('.fechadinamica').text('Fecha a Volver a Llamar');
        if( codigo==1 ){
            $('.fechadinamica').text('Fecha a Inscribirse');
        }
    }
    else if(codigo==3){
        $('.tipo2').css('display','block');
        AjaxPersona.ListarSubTipoLlamada(SlctListarSubTipoLlamada,id);
    }
}

ActivarDetalle=function(){
    id=$("#slct_sub_tipo_llamada").val();
    AjaxPersona.ListarDetalleTipoLlamada(SlctListarDetalleTipoLlamada,id*1);
}

SlctListarTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option data-obs='"+r.obs+"' value="+r.id+">"+r.tipo_llamada+"</option>";
    });
    $("#ModalPersonaForm #slct_tipo_llamada").html(html); 
    $("#ModalPersonaForm #slct_tipo_llamada").selectpicker('refresh');

};

SlctListarSubTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        selected='';
        if( PersonaG.tipo_llamada_sub!='' && PersonaG.tipo_llamada_sub==r.id ){
            selected='selected';
        }
        html+="<option value="+r.id+" "+selected+">"+r.tipo_llamada_sub+"</option>";
    });
    $("#ModalPersonaForm #slct_sub_tipo_llamada").html(html); 
    $("#ModalPersonaForm #slct_sub_tipo_llamada").selectpicker('refresh');
        if( PersonaG.tipo_llamada_sub!='' ){
            ActivarDetalle();
        }
};

SlctListarDetalleTipoLlamada=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        selected='';
        if( PersonaG.tipo_llamada_sub_detalle!='' && PersonaG.tipo_llamada_sub_detalle==r.id ){
            selected='selected';
        }
        html+="<option value="+r.id+" "+selected+">"+r.tipo_llamada_sub_detalle+"</option>";
    });
    $("#ModalPersonaForm #slct_detalle_tipo_llamada").html(html); 
    $("#ModalPersonaForm #slct_detalle_tipo_llamada").selectpicker('refresh');
};

ValidaForm=function(){
    var r=true;
    codigo=$("#slct_tipo_llamada option:selected").attr('data-obs');
    if( $.trim( $("#ModalPersonaForm #txt_nombre").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Nombre',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_paterno").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Apellido Paterno',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_materno").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Apellido Materno',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_dni").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese DNI',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #slct_sexo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sexo',4000);
    }

    else if( $.trim( $("#ModalPersonaForm #slct_sucursal").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sede de Inscripción',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #slct_medio_publicitario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Medio Publicitario',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_carrera").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Carrera/Especialidad Interesado(a)',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #slct_dia").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Frencuencia',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_hora_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Hora Inicio',4000);
    }
    else if( $.trim( $("#ModalPersonaForm #txt_hora_final").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Hora Final',4000);
    }
    /*else if( $.trim( $("#ModalPersonaForm #slct_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Estado',4000);
    }*/
    else if( (codigo==1 || codigo==2) && $.trim( $("#ModalPersonaForm #txt_fechas").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione '+$('.fechadinamica').text(),4000);
    }
    else if( codigo==3 && $.trim( $("#ModalPersonaForm #slct_sub_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sub Estado',4000);
    }
    else if( codigo==3 && $.trim( $("#ModalPersonaForm #slct_detalle_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Detalle Sub Estado',4000);
    }
   
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    PersonaG.id='';
    PersonaG.paterno='';
    PersonaG.materno='';
    PersonaG.nombre='';
    PersonaG.dni='';
    PersonaG.sexo='0';
    PersonaG.email='';
    PersonaG.password='';
    PersonaG.telefono='';
    PersonaG.celular='';
    PersonaG.fecha_nacimiento='';
    PersonaG.carrera='';
    PersonaG.estado='1';
    PersonaG.sucursal_id='';
    PersonaG.medio_publicitario_id='';
    PersonaG.dia='';
    PersonaG.hora_inicio='';
    PersonaG.hora_final='';
    PersonaG.region_id_dir='';
    PersonaG.provincia_id_dir='';
    PersonaG.distrito_id_dir='';
    PersonaG.region_dir='';
    PersonaG.provincia_dir='';
    PersonaG.distrito_dir='';
    PersonaG.referencia_dir='';
    PersonaG.tipo_llamada='';
    PersonaG.tipo_llamada_sub='';
    PersonaG.tipo_llamada_sub_detalle='';
    PersonaG.fechas='';

    if( val==0 ){

        PersonaG.id=id;
        PersonaG.paterno=$("#TablePersona #trid_"+id+" .paterno").text();
        PersonaG.materno=$("#TablePersona #trid_"+id+" .materno").text();
        PersonaG.nombre=$("#TablePersona #trid_"+id+" .nombre").text();
        PersonaG.dni=$("#TablePersona #trid_"+id+" .dni").text();
        PersonaG.sexo=$("#TablePersona #trid_"+id+" .sexo").val();
        PersonaG.email=$("#TablePersona #trid_"+id+" .email").text();
        PersonaG.telefono=$("#TablePersona #trid_"+id+" .telefono").val();
        PersonaG.celular=$("#TablePersona #trid_"+id+" .celular").val();
        PersonaG.fecha_nacimiento=$("#TablePersona #trid_"+id+" .fecha_nacimiento").val();
        PersonaG.carrera=$("#TablePersona #trid_"+id+" .carrera").val();
        PersonaG.estado=$("#TablePersona #trid_"+id+" .estado").val();
        PersonaG.sucursal_id=$("#TablePersona #trid_"+id+" .sucursal_id").val();
        PersonaG.medio_publicitario_id=$("#TablePersona #trid_"+id+" .medio_publicitario_id").val();
        PersonaG.dia=$("#TablePersona #trid_"+id+" .dia").val();
        PersonaG.hora_inicio=$("#TablePersona #trid_"+id+" .hora_inicio").val();
        PersonaG.hora_final=$("#TablePersona #trid_"+id+" .hora_final").val();
        PersonaG.region_id_dir=$("#TablePersona #trid_"+id+" .region_id_dir").val();
        PersonaG.provincia_id_dir=$("#TablePersona #trid_"+id+" .provincia_id_dir").val();
        PersonaG.distrito_id_dir=$("#TablePersona #trid_"+id+" .distrito_id_dir").val();
        PersonaG.region_dir=$("#TablePersona #trid_"+id+" .region_dir").val();
        PersonaG.provincia_dir=$("#TablePersona #trid_"+id+" .provincia_dir").val();
        PersonaG.distrito_dir=$("#TablePersona #trid_"+id+" .distrito_dir").val();
        PersonaG.referencia_dir=$("#TablePersona #trid_"+id+" .referencia_dir").val();
        PersonaG.tipo_llamada=$("#TablePersona #trid_"+id+" .tipo_llamada").val();
        PersonaG.tipo_llamada_sub=$("#TablePersona #trid_"+id+" .tipo_llamada_sub").val();
        PersonaG.tipo_llamada_sub_detalle=$("#TablePersona #trid_"+id+" .tipo_llamada_sub_detalle").val();
        PersonaG.fechas=$("#TablePersona #trid_"+id+" .fechas").val();
      
    }
    $('#ModalPersona').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxPersona.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxPersona.Cargar(HTMLCargarPersona);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxPersona.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        cargos_selec=[];
        $('#ModalPersona').modal('hide');
        AjaxPersona.Cargar(HTMLCargarPersona);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

SlctCargarSucursal=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalPersona #slct_sucursal").html(html); 
    $("#ModalPersona #slct_sucursal").selectpicker('refresh');
};

SlctCargarMedioPublicitario=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.medio_publicitario+"</option>";
    });
    $("#ModalPersona #slct_medio_publicitario").html(html); 
    $("#ModalPersona #slct_medio_publicitario").selectpicker('refresh');
};

HTMLCargarPersona=function(result){
    var html="";
    $('#TablePersona').DataTable().destroy();

    $.each(result.data.data,function(index,r){        
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }
       
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='paterno'>"+r.paterno+"</td>"+
            "<td class='materno'>"+r.materno+"</td>"+
            "<td class='nombre'>"+r.nombre+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
            "<td class='email'>"+r.email+"</td>"+
            "<td>"+
            "<input type='hidden' class='fecha_nacimiento' value='"+r.fecha_nacimiento+"'>"+
            "<input type='hidden' class='carrera' value='"+r.carrera+"'>"+
            "<input type='hidden' class='sexo' value='"+r.sexo+"'>"+
            "<input type='hidden' class='telefono' value='"+r.telefono+"'>"+
            "<input type='hidden' class='celular' value='"+r.celular+"'>"+
            "<input type='hidden' class='medio_publicitario_id' value='"+r.medio_publicitario_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='dia' value='"+r.frecuencia+"'>"+
            "<input type='hidden' class='hora_inicio' value='"+r.hora_inicio+"'>"+
            "<input type='hidden' class='hora_final' value='"+r.hora_final+"'>"+
            "<input type='hidden' class='region_id_dir' value='"+$.trim(r.region_id_dir)+"'>"+
            "<input type='hidden' class='region_dir' value='"+$.trim(r.region_dir)+"'>"+
            "<input type='hidden' class='provincia_id_dir' value='"+$.trim(r.provincia_id_dir)+"'>"+
            "<input type='hidden' class='provincia_dir' value='"+$.trim(r.provincia_dir)+"'>"+
            "<input type='hidden' class='distrito_id_dir' value='"+$.trim(r.distrito_id_dir)+"'>"+
            "<input type='hidden' class='distrito_dir' value='"+$.trim(r.distrito_dir)+"'>"+
            "<input type='hidden' class='referencia_dir' value='"+$.trim(r.referencia_dir)+"'>"+
            "<input type='hidden' class='tipo_llamada' value='"+$.trim(r.tipo_llamada_id)+"'>"+
            "<input type='hidden' class='tipo_llamada_sub' value='"+$.trim(r.tipo_llamada_sub_id)+"'>"+
            "<input type='hidden' class='tipo_llamada_sub_detalle' value='"+$.trim(r.tipo_llamada_sub_detalle_id)+"'>"+
            "<input type='hidden' class='fechas' value='"+$.trim(r.fechas)+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TablePersona tbody").html(html); 
    $("#TablePersona").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthMenu": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TablePersona_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarPersona','AjaxPersona',result.data,'#TablePersona_paginate');
        } 
    });
};

ValidaOnline=function(v){
    if( v == 'ON'){
        $("#txt_hora_inicio").val('00:00');
        $("#txt_hora_final").val('23:59');
    }
}

EliminarArea=function(obj){
    //console.log(obj);
    var valor= obj.id;
    obj.parentNode.parentNode.parentNode.remove();
    var index = cargos_selec.indexOf(valor);
    cargos_selec.splice( index, 1 );
};
</script>
