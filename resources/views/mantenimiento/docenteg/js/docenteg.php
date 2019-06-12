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

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
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
    $("#PersonaForm #TablePersona select").change(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });
    $("#PersonaForm #TablePersona input").blur(function(){ AjaxPersona.Cargar(HTMLCargarPersona); });

    $('#ModalPersona').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalPersonaForm").append("<input type='hidden' value='"+PersonaG.id+"' name='id'>");
            $("#ModalPersonaForm").append("<input type='hidden' value='"+PersonaG.persona_id+"' name='persona_id'>");
        }
        console.log(PersonaG);
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
        $('#ModalPersonaForm #slct_estado').val( PersonaG.estado );
        $("#ModalPersona select").selectpicker('refresh');
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
        msjG.mensaje('warning','Sleccione Sexo',4000);
    }
   
   
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    PersonaG.id='';
    PersonaG.persona_id='';
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
    PersonaG.estado='1';
    if( val==0 ){

        PersonaG.id=id;
        PersonaG.persona_id=$("#TablePersona #trid_"+id+" .persona_id").val();
        PersonaG.paterno=$("#TablePersona #trid_"+id+" .paterno").text();
        PersonaG.materno=$("#TablePersona #trid_"+id+" .materno").text();
        PersonaG.nombre=$("#TablePersona #trid_"+id+" .nombre").text();
        PersonaG.dni=$("#TablePersona #trid_"+id+" .dni").text();
        PersonaG.sexo=$("#TablePersona #trid_"+id+" .sexo").val();
        PersonaG.email=$("#TablePersona #trid_"+id+" .email").text();
        PersonaG.telefono=$("#TablePersona #trid_"+id+" .telefono").val();
        PersonaG.celular=$("#TablePersona #trid_"+id+" .celular").val();
        PersonaG.fecha_nacimiento=$("#TablePersona #trid_"+id+" .fecha_nacimiento").val();
        PersonaG.estado=$("#TablePersona #trid_"+id+" .estado").val();
      
    }
    $('#ModalPersona').modal('show');
}

CambiarEstado=function(estado,id,persona_id){
    AjaxPersona.CambiarEstado(HTMLCambiarEstado,estado,id,persona_id);
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

HTMLCargarPersona=function(result){
    var html="";
    $('#TablePersona').DataTable().destroy();

    $.each(result.data.data,function(index,r){        
        if( $.trim(r.id)==''){
            r.id=0;
        }
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+','+r.persona_id+')" class="btn btn-warning">Persona</span>';
        btn='';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+','+r.persona_id+')" class="btn btn-success">Docente</span>';
            btn='<a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='paterno'>"+r.paterno+"</td>"+
            "<td class='materno'>"+r.materno+"</td>"+
            "<td class='nombre'>"+r.nombre+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
            "<td class='email'>"+$.trim(r.email)+"</td>"+
            "<td>"+
            "<input type='hidden' class='fecha_nacimiento' value='"+$.trim(r.fecha_nacimiento)+"'>"+
            "<input type='hidden' class='persona_id' value='"+$.trim(r.persona_id)+"'>"+
            "<input type='hidden' class='sexo' value='"+$.trim(r.sexo)+"'>"+
            "<input type='hidden' class='telefono' value='"+$.trim(r.telefono)+"'>"+
            "<input type='hidden' class='celular' value='"+$.trim(r.celular)+"'>"+
            "<input type='hidden' class='estado' value='"+$.trim(r.estado)+"'>"+estadohtml+
            "</td>"+
            '<td>'+btn+'</td>';
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
</script>
