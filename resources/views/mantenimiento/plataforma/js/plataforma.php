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
    $("#PersonaForm #TablePersona select").change(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });
    $("#PersonaForm #TablePersona input").blur(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });

    $('#ModalPersona').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
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
        $("#ModalPersona select").selectpicker('refresh');
        $('#ModalPersonaForm #slct_dia').selectpicker( 'val',PersonaG.dia.split(",") );
        $('#ModalPersonaForm #txt_nombre').focus();
    });

    $('#ModalPersona').on('hidden.bs.modal', function (event) {
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        $('#slct_cargos,#slct_rol,#slct_area').selectpicker('destroy');
        $("#ModalPersonaForm #t_cargoPersona").html('');
    });
});

ValidaForm=function(){
    var r=true;
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

EliminarArea=function(obj){
    //console.log(obj);
    var valor= obj.id;
    obj.parentNode.parentNode.parentNode.remove();
    var index = cargos_selec.indexOf(valor);
    cargos_selec.splice( index, 1 );
};
</script>
