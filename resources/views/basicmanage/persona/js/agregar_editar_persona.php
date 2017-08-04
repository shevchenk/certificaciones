<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var PersonaG={id:0,paterno:"",materno:"",nombre:"",dni:"",sexo:"",email:"",password:"",telefono:"",celular:"",fecha_nacimiento:"",estado:1}; // 

$(document).ready(function() {
    
    $(".fechas").datetimepicker({
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
    
    $('#ModalPersona').on('shown.bs.modal', function (event) {

        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax1();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax1();');
            $("#ModalPersonaForm").append("<input type='hidden' value='"+PersonaG.id+"' name='id'>");
        }

        $('#ModalPersonaForm #txt_paterno').val( PersonaG.paterno );
        $('#ModalPersonaForm #txt_materno').val( PersonaG.materno );
        $('#ModalPersonaForm #txt_nombre').val( PersonaG.nombre );
        $('#ModalPersonaForm #txt_dni').val( PersonaG.dni );
        $('#ModalPersonaForm #slct_sexo').val( PersonaG.sexo );
        $('#ModalPersonaForm #txt_email').val( PersonaG.email );
        $('#ModalPersonaForm #txt_telefono').val( PersonaG.telefono );
        $('#ModalPersonaForm #txt_celular').val( PersonaG.celular );
        $('#ModalPersonaForm #txt_fecha_nacimiento').val( PersonaG.fecha_nacimiento );
        $('#ModalPersonaForm #slct_estado').val( PersonaG.estado );
        $("#ModalPersona select").selectpicker('refresh');
        $('#ModalPersonaForm #txt_nombre').focus();
    });

    $('#ModalPersona').on('hidden.bs.modal', function (event) {
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        $("#ModalPersonaForm input").val('');
    });
});

ValidaForm1=function(){
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

AgregarEditar1=function(val,id){
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
    PersonaG.estado='1';
    if( val==0 ){

        PersonaG.id=id;
        PersonaG.paterno=$("#TableListapersona #trid_"+id+" .paterno").text();
        PersonaG.materno=$("#TableListapersona #trid_"+id+" .materno").text();
        PersonaG.nombre=$("#TableListapersona #trid_"+id+" .nombre").text();
        PersonaG.dni=$("#TableListapersona #trid_"+id+" .dni").text();
        PersonaG.sexo=$("#TableListapersona #trid_"+id+" .sexo").val();
        PersonaG.email=$("#TableListapersona #trid_"+id+" .email").val();
        PersonaG.telefono=$("#TableListapersona #trid_"+id+" .telefono").val();
        PersonaG.celular=$("#TableListapersona #trid_"+id+" .celular").val();
        PersonaG.fecha_nacimiento=$("#TableListapersona #trid_"+id+" .fecha_nacimiento").val();
        PersonaG.estado=$("#TableListapersona #trid_"+id+" .estado").val();
      
    }
    $('#ModalPersona').modal('show');
}

AgregarEditarAjax1=function(){
    if( ValidaForm1() ){
        AjaxAgregar_Editar_Persona.AgregarEditar1(HTMLAgregarEditar1);
    }
}

HTMLAgregarEditar1=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalPersona').modal('hide');
        AjaxListapersona.Cargar(HTMLCargarPersona);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
</script>
