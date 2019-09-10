<script type="text/javascript">
$(document).ready(function() {
    $("#MyselfForm #txt_paterno").focus();
    MyselfAjax.Validar(HTMLValidar);
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#MyselfForm #txt_paterno").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Paterno',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_materno").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Materno',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_nombre").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Nombre',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_dni").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese DNI',4000);
    }
    else if( $.trim( $("#MyselfForm #slct_sexo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sexo',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_email").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Email',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_celular").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Celular',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_password").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Contraseña',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_password_confirm").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Confirmación de Contraseña',4000);
    }
    else if( $.trim( $("#MyselfForm #txt_password_confirm").val() ) != 
             $.trim( $("#MyselfForm #txt_password").val() ) 
    ){
        r=false;
        msjG.mensaje('warning','Contraseña y Contraseña de confirmación no son'+
                     ' iguales',4000);
    }
    return r;
}

EditarAjax=function(){
    if( ValidaForm() ){
        MyselfAjax.Editar(HTMLEditar);
    }
}

HTMLValidar=function(result){
    if( result.rst==1 && result.estado==1){
        sweetalertG.alert('Validación de Inscripción','Ud. ya validó anteriormente esta acción',Redirect);
    }
}

HTMLEditar=function(result){
    if( result.rst==1 ){
        sweetalertG.alert('Validación de Inscripción','Los datos personales registrados serán usados para la elaboración de sus certificados',Redirect);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

Redirect=function(){
    window.location='../salir';
}
</script>
