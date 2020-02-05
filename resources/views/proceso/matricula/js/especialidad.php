<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var PromocionGeneral=0;
var ProgramacionG={id:0,persona_id:0,persona:"",docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",estado:1}; // Datos Globales
$(document).ready(function() {

    $('#exonerar_matricula').prop('checked', true);
    $('#ModalPersonaForm').append("<input type='hidden' class='mant' name='alumnonuevo' value='1'>");
    CargarSlct(1);CargarSlct(2);CargarSlct(3);
    var responsable='<?php echo Auth::user()->paterno .' '. Auth::user()->materno .' '. Auth::user()->nombre ?>';
    var responsable_id='<?php echo Auth::user()->id ?>';
    var hoy='<?php echo date('Y-m-d') ?>';
    $("#ModalMatriculaForm #txt_responsable").val(responsable);
    $("#ModalMatriculaForm #txt_responsable_id").val(responsable_id);
    $("#ModalMatriculaForm #txt_fecha").val(hoy);
    
    $( "#ModalMatriculaForm #slct_region_id" ).change(function() {
            var region_id= $('#ModalMatriculaForm #slct_region_id').val();
            AjaxMatricula.CargarProvincia(SlctCargarProvincia,region_id);
            data={};
            SlctCargarDistrito(data);
    });
    
    $( "#ModalMatriculaForm #slct_provincia_id" ).change(function() {
            var provincia_id= $('#ModalMatriculaForm #slct_provincia_id').val();
            AjaxMatricula.CargarDistrito(SlctCargarDistrito,provincia_id);
    });
    
    $( "#ModalMatriculaForm #exonerar_matricula" ).change(function() {
        if( $('#ModalMatriculaForm #exonerar_matricula').prop('checked') ) {
              $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).prop("readOnly",true);
              $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).prop("readOnly",true);
              $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).val("");
              $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).val("");
              $( "#ModalMatriculaForm #pago_nombre_matricula" ).val("");
              $( "#ModalMatriculaForm #pago_archivo_matricula" ).val("");
              $( "#ModalMatriculaForm #file_matricula" ).prop("disabled",true);
        }else{
              $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).prop("readOnly",false);
              $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).prop("readOnly",false);
              $( "#ModalMatriculaForm #file_matricula" ).prop("disabled",false);
        }

    });
    ActivarPago(1);
});

ValidaTipo=function(v){}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalMatriculaForm #slct_sucursal_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione ODE de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_sucursal_destino_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Lugar de Recojo del Documento',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_tipo_participante_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Tipo de Participante',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_persona_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
    /*else if( $.trim( $("#ModalMatriculaForm #slct_region_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Región',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_provincia_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Provincia',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_distrito_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Distrito',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_direccion").val() )==''){
        r=false;
        msjG.mensaje('warning','Ingrese Dirección',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_referencia").val() )==''){
        r=false;
        msjG.mensaje('warning','Ingrese Referencia',4000);
    }*/
    else if( $.trim( $("#ModalMatriculaForm #txt_marketing_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Teleoperadora',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_persona_caja_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Responsable de Caja',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_programacion_id").val() )=='' && $.trim( $("#ModalMatriculaForm #txt_especialidad_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione al menos una especialidad',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_nro_pago_matricula").val() )=='' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==false){
        r=false;
        msjG.mensaje('warning','Ingrese Número de pago de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_monto_pago_matricula").val() )=='' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==false){
        r=false;
        msjG.mensaje('warning','Ingrese Monto de pago de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_observacion").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Observación de matrícula o escriba S/O',4000);
    }
    return r;
}

ValidaTabla=function(){
    var r=true;
    var ValidaTotal=0;
    var contador=0;
         $("#t_pago>tbody tr").each(function(){

                  if($(this).find("td:eq(1) input[type='text']").val()==''){
                      r=false;
                      msjG.mensaje('warning','Ingrese N° Recibo del Curso',4000);
                  }
                  else if($(this).find("td:eq(2) input[type='text']").val()==''){
                      r=false;
                      msjG.mensaje('warning','Ingrese Importe del Curso',4000);   
                  }

                  if( $(this).find("input[type='checkbox']").is(':checked') ){
                      ValidaTotal++;
                  }

                  $(this).find("input[type='checkbox']").attr("value",contador);
                  contador++;
          
         });
         /*if( (ValidaTotal>PromocionG) || (ValidaTotal<PromocionG && ValidaTotal>0) ){
            r=false;
            msjG.mensaje('warning','La oferta existente tiene un máximo de '+PromocionG+' seminarios en promoción. Verifique y actualice los seminarios seleccionados.',9000);
         }*/

         if( $('#txt_nro_pago_inscripcion').val()=='' && PromocionGeneral==1 ){
            r=false;
            msjG.mensaje('warning','Ingrese N° Recibo de la Inscripción',4000);
         }
         else if( $('#txt_monto_pago_inscripcion').val()=='' && PromocionGeneral==1){
            r=false;
            msjG.mensaje('warning','Ingrese monto de la Inscripción',4000);
         }
         else if( $('#slct_tipo_pago_inscripcion').val()=='0' && PromocionGeneral==1){
            r=false;
            msjG.mensaje('warning','Seleccione tipo de Operación',4000);
         }

    return r;     
}
AgregarEditarAjax=function(){
    if( ValidaTabla() && ValidaForm()){
        AjaxMatricula.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $("#ModalMatriculaForm input[type='hidden'],#ModalMatriculaForm input[type='text'],#ModalMatriculaForm textarea").not('.mant').val('');
        $("#ModalMatriculaForm select").selectpicker('val','0');
        $('#ModalMatriculaForm #tb_matricula, #ModalMatriculaForm #tb_pago, #ModalMatriculaForm #tb_pago_cuota, #ModalMatriculaForm #promocion_seminario').html('');
        $("#txt_observacion").val('S/O');
        $("#pago_img_ins,#dni_img").attr('src','');
        $("#tb_matricula").html('');
        ActivarPago(1);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

CargarSlct=function(slct){
    if(slct==1){
        AjaxMatricula.CargarSucursal(SlctCargarSucursal1);
        AjaxMatricula.CargarSucursalTotal(SlctCargarSucursalTotal);
    }
    if(slct==2){
        AjaxMatricula.CargarRegion(SlctCargarRegion);
    }
    if(slct==3){
        AjaxMatricula.CargarTipoParticipante(SlctCargarTipoParticipante);
    }
}

SlctCargarSucursalTotal=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    html+="<option value='0'>A Domicilio</option>";
    $.each(result.data,function(index,r){
        if(r.id>1){
          html+="<option value="+r.id+">"+r.sucursal+"</option>";
        }
    });
    $("#ModalMatriculaForm #slct_sucursal_destino_id").html(html); 
    $("#ModalMatriculaForm #slct_sucursal_destino_id").selectpicker('refresh');

};

SlctCargarSucursal1=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalMatriculaForm #slct_sucursal_id").html(html); 
    $("#ModalMatriculaForm #slct_sucursal_id").selectpicker('refresh');

};

SlctCargarRegion=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.region+"</option>";
    });
    $("#ModalMatriculaForm #slct_region_id").html(html); 
    $("#ModalMatriculaForm #slct_region_id").selectpicker('refresh');

};

SlctCargarProvincia=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.provincia+"</option>";
    });
    $("#ModalMatriculaForm #slct_provincia_id").html(html); 
    $("#ModalMatriculaForm #slct_provincia_id").selectpicker('refresh');

};

SlctCargarDistrito=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.distrito+"</option>";
    });
    $("#ModalMatriculaForm #slct_distrito_id").html(html); 
    $("#ModalMatriculaForm #slct_distrito_id").selectpicker('refresh');

};

SlctCargarTipoParticipante=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_participante+"</option>";
    });
    $("#ModalMatriculaForm #slct_tipo_participante_id").html(html); 
    $("#ModalMatriculaForm #slct_tipo_participante_id").selectpicker('refresh');

};

CargarAlumno=function(result){
    if(result.data!=null){
        $("#ModalMatriculaForm #txt_direccion").val(result.data.direccion); 
        $("#ModalMatriculaForm #txt_referencia").val(result.data.referencia);
        $("#ModalMatriculaForm #txt_codigo_interno").val(result.data.codigo_interno);
        
        $("#ModalMatriculaForm #slct_region_id").selectpicker('val',result.data.region_id);
        $("#ModalMatriculaForm #slct_region_id").change();
        $("#ModalMatriculaForm #slct_provincia_id").selectpicker('val',result.data.provincia_id);
        $("#ModalMatriculaForm #slct_provincia_id").change();
        $("#ModalMatriculaForm #slct_distrito_id").selectpicker('val',result.data.distrito_id);
    }else{
        $("#ModalMatriculaForm #txt_direccion").val(''); 
        $("#ModalMatriculaForm #txt_referencia").val('');
        
        $("#ModalMatriculaForm #slct_region_id").selectpicker('val',0);
        $("#ModalMatriculaForm #slct_region_id").change();
        $("#ModalMatriculaForm #slct_provincia_id").selectpicker('val',0);
        $("#ModalMatriculaForm #slct_provincia_id").change();
        $("#ModalMatriculaForm #slct_distrito_id").selectpicker('val',0);
    }
};

ActivarPago=function(id){
    PromocionGeneral=0;
    if(id==1){
        var contador=0;
        $("#t_pago textarea").each(function(){ contador++; });
        if(contador<=10){
            PromocionGeneral=1;
            $("#t_pago").hide(1500);
            $("#t_pago_promocion").show(1500);

            $("#t_pago>tbody tr").each(function(){
                $(this).find("td:eq(1) input[type='text']").val('0');
                $(this).find("td:eq(2) input[type='text']").val('0');
                $(this).find("td:eq(3) select").val('0');

                $(this).find("td:eq(1) input[type='text']").attr('readOnly','true');
                $(this).find("td:eq(2) input[type='text']").attr('readOnly','true');
            });

            $("#txt_nro_promocion").removeAttr('disabled');
            $("#txt_monto_promocion").removeAttr('disabled');
            $("#slct_tipo_pago").removeAttr('disabled');
            $("#txt_nro_promocion").val('');
            $("#txt_monto_promocion").val('');
            $("#slct_tipo_pago").val('');
        }
        else{
            //msjG.mensaje('warning','Para activar la promoción, requiere de '+PromocionG+' seminarios seleccionados',3000);
            msjG.mensaje('warning','El pago único solo puede darse hasta un máximo de 10 seminarios seleccionados',3000);
        }
    }
    else{
        $("#t_pago_promocion").hide(1500);
        $("#t_pago").show(1500);
        $("#t_pago>tbody tr").each(function(){
            $(this).find("td:eq(1) input[type='text']").val('');
            $(this).find("td:eq(2) input[type='text']").val('');
            $(this).find("td:eq(3) select").val('');

            $(this).find("td:eq(1) input[type='text']").removeAttr('readOnly');
            $(this).find("td:eq(2) input[type='text']").removeAttr('readOnly');
        });
        $("#txt_nro_promocion").attr('disabled','true');
        $("#txt_monto_promocion").attr('disabled','true');
        $("#slct_tipo_pago").attr('disabled','true');

        $("#txt_nro_promocion").val('0');
        $("#txt_monto_promocion").val('0');
        $("#slct_tipo_pago").val('0');
    }
    $("#slct_tipo_pago").selectpicker('refresh');
}

ValidaDeuda2 = function(c,t){
    $("#txt_monto_pago_inscripcion_ico,#i_monto_deuda_inscripcion_ico").removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_pago_inscripcion_ico,#i_monto_deuda_inscripcion_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda_inscripcion").text(saldo.toFixed(2));
}
</script>
