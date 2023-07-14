<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var PromocionGeneral=0;
var ProgramacionG={id:0,persona_id:0,persona:"",docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",estado:1}; // Datos Globales
var MatriculaIdG=0;
$(document).ready(function() {

    $("#spn_fecha_pago_mat").click(()=>{ $("#txt_fecha_pago_mat").focus(); });
    $("#spn_fecha_pago_ins").click(()=>{ $("#txt_fecha_pago_ins").focus(); });
    $("#spn_fecha_pago_pro").click(()=>{ $("#txt_fecha_pago_pro").focus(); });

    $( "#ModalMatriculaForm #file_inscripcion, #ModalMatriculaForm #file_matricula, #ModalMatriculaForm #file_dni" ).prop("disabled",true);
    $('#exonerar_matricula').prop('checked', false);
    $('#exonerar_inscripcion').prop('checked', false);
    $('#ModalPersonaForm').append("<input type='hidden' class='mant' name='alumnonuevo' value='1'>");
    CargarSlct(1);CargarSlct(2);CargarSlct(3);
    AjaxMatricula.CargarMedioCaptacion(SlctCargarMedioCaptacion);
    AjaxMatricula.CargarBanco(SlctCargarBanco);
    var responsable='<?php echo Auth::user()->paterno .' '. Auth::user()->materno .' '. Auth::user()->nombre ?>';
    var responsable_id='<?php echo Auth::user()->id ?>';
    var hoy='<?php echo date('Y-m-d') ?>';
    $("#ModalMatriculaForm #txt_responsable").val(responsable);
    $("#ModalMatriculaForm #txt_responsable_id").val(responsable_id);
    $("#ModalMatriculaForm #txt_fecha").val(hoy);
    $(".cursospro2").css('display','none');
    $(".cursospro1").css('display','');

    $( "#ModalMatriculaForm #rdbtipo2" ).prop("checked",true);
    $( "#ModalMatriculaForm #rdbtipo3" ).removeAttr("checked");
    
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
        ValidaCheckMatricula();
    });

    $( "#ModalMatriculaForm #exonerar_inscripcion" ).change(function() {
        ValidaCheckInscripcion();
    });
});

ValidaMedioCaptacion=function(){
    tipo = $("#ModalMatriculaForm #slct_medio_captacion_id option:selected").attr('data-tipo');
    $("#ModalMatriculaForm .validamediocaptacion").hide();
    $("#ModalMatriculaForm #txt_marketing_id, #ModalMatriculaForm #txt_marketing").val('');
    if( tipo*1==1 || tipo*1==2 ){
        $("#ModalMatriculaForm #btn_marketing").attr("data-filtros2","estado:1|rol_id:1|medio_captacion_id:"+$("#ModalMatriculaForm #slct_medio_captacion_id").val());
        $(".validamediocaptacion").show();
    }
}

ValidaCheckMatricula=function(){
    $("#t_pago_matricula").hide();
    if( $('#ModalMatriculaForm #exonerar_matricula').prop('checked') ) {
        $("#t_pago_matricula").show();
        $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).prop("readOnly",false);
        $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).prop("readOnly",false);
        $( "#ModalMatriculaForm #slct_tipo_pago_matricula, #ModalMatriculaForm #txt_fecha_pago_matricula" ).removeAttr("disabled");
        $( "#ModalMatriculaForm #file_matricula" ).prop("disabled",false);
    }else{
        $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).prop("readOnly",true);
        $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).prop("readOnly",true);
        $( "#ModalMatriculaForm #slct_tipo_pago_matricula, #ModalMatriculaForm #txt_fecha_pago_matricula" ).prop("disabled",true);
        $( "#ModalMatriculaForm #slct_tipo_pago_matricula" ).val("0");
        $( "#ModalMatriculaForm #txt_nro_pago_matricula" ).val("");
        $( "#ModalMatriculaForm #txt_monto_pago_matricula" ).val("");
        $("#ModalMatriculaForm #i_monto_deuda_matricula" ).text("0");
        $( "#ModalMatriculaForm #pago_nombre_matricula" ).val("");
        $( "#ModalMatriculaForm #pago_archivo_matricula" ).val("");
        $( "#ModalMatriculaForm #txt_fecha_pago_matricula" ).val("");
        $( "#ModalMatriculaForm #file_matricula" ).prop("disabled",true);
        $("#txt_monto_pago_matricula_ico,#i_monto_deuda_matricula_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    $( "#ModalMatriculaForm #slct_tipo_pago_matricula" ).selectpicker("refresh");
}

ValidaCheckInscripcion=function(){
    $("#t_pago_inscripcion").hide();
    if( $('#ModalMatriculaForm #exonerar_inscripcion').prop('checked') ) {
        $("#t_pago_inscripcion").show();
          $( "#ModalMatriculaForm #txt_nro_pago_inscripcion" ).prop("readOnly",false);
          $( "#ModalMatriculaForm #txt_monto_pago_inscripcion" ).prop("readOnly",false);
          $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #txt_fecha_pago_inscripcion" ).removeAttr("disabled");
          $( "#ModalMatriculaForm #file_inscripcion, #ModalMatriculaForm #file_dni" ).prop("disabled",false);
    }else{
          $( "#ModalMatriculaForm #txt_nro_pago_inscripcion" ).prop("readOnly",true);
          $( "#ModalMatriculaForm #txt_monto_pago_inscripcion" ).prop("readOnly",true);
          $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #txt_fecha_pago_inscripcion" ).prop("disabled",true);
          $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion" ).val("0");
          $( "#ModalMatriculaForm #txt_nro_pago_inscripcion" ).val("");
          $( "#ModalMatriculaForm #txt_monto_pago_inscripcion" ).val("");
          $("#ModalMatriculaForm #i_monto_deuda_inscripcion" ).text("0");
          $( "#ModalMatriculaForm #pago_nombre_inscripcion" ).val("");
          $( "#ModalMatriculaForm #pago_archivo_inscripcion" ).val("");
          $( "#ModalMatriculaForm #txt_fecha_pago_inscripcion" ).val("");
          $( "#ModalMatriculaForm #dni_nombre" ).val("");
          $( "#ModalMatriculaForm #dni_archivo" ).val("");
          $( "#ModalMatriculaForm #file_inscripcion, #ModalMatriculaForm #file_dni" ).prop("disabled",true);
          $("#txt_monto_pago_inscripcion_ico,#i_monto_deuda_inscripcion_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
          $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion" ).selectpicker("refresh");
}

ValidaForm=function(){
    var r=true;
    let tipo = $("#ModalMatriculaForm #slct_medio_captacion_id option:selected").attr('data-tipo');
    if( $.trim( $("#ModalMatriculaForm #txt_persona_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_sucursal_id").val() )=='0' ){
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
    else if( $.trim( $("#ModalMatriculaForm #slct_ode_estudio_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione Local de Estudios',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_especialidad2_id").val() )=='0'){
        r=false;
        msjG.mensaje('warning','Seleccione una Carrera / Módulo',4000);
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
    else if( $.trim( $("#ModalMatriculaForm #txt_nro_pago_inscripcion").val() )=='' && $('#ModalMatriculaForm #exonerar_inscripcion').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese número de pago de Inscripción',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_monto_pago_inscripcion").val() )=='' && $('#ModalMatriculaForm #exonerar_inscripcion').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese monto de pago de Inscripción',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_tipo_pago_inscripcion").val() )=='0' && $('#ModalMatriculaForm #exonerar_inscripcion').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Seleccione tipo de operación de Inscripción',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_fecha_pago_ins").val() )=='' && $('#ModalMatriculaForm #exonerar_inscripcion').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese la fecha de pago de la Inscripción',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_nro_pago_matricula").val() )=='' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese número de pago de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_monto_pago_matricula").val() )=='' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese monto de pago de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_tipo_pago_matricula").val() )=='0' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Seleccione tipo de operación de Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_fecha_pago_mat").val() )=='' && $('#ModalMatriculaForm #exonerar_matricula').prop('checked')==true){
        r=false;
        msjG.mensaje('warning','Ingrese la fecha de pago de la Matrícula',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #txt_programacion_id").val() )=='' && $.trim( $("#ModalMatriculaForm #txt_especialidad_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione al menos un Inicio / Curso',4000);
    }
    /*else if( $.trim( $("#ModalMatriculaForm #txt_persona_caja_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Responsable de Caja',4000);
    }*/
    /*else if( $.trim( $("#ModalMatriculaForm #slct_medio_captacion_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Medio de Captación',4000);
    }*/
    else if( $.trim( $("#ModalMatriculaForm #txt_marketing_id").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione Persona Marketing',4000);
    }
    else if( $.trim( $("#ModalMatriculaForm #slct_medio_captacion_id2").val() )==''){
        r=false;
        msjG.mensaje('warning','Seleccione como llegó aquí',4000);
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

    return r;     
}

validaPromocion= function(){
    var r=true;

    if( $('#txt_nro_promocion').val()=='' && PromocionGeneral==1 ){
        r=false;
        msjG.mensaje('warning','Ingrese N° Recibo de la Promoción',4000);
     }
     else if( $('#txt_monto_promocion').val()=='' && PromocionGeneral==1){
        r=false;
        msjG.mensaje('warning','Ingrese monto de la Promoción',4000);
     }
     else if( $('#slct_tipo_pago').val()=='0' && PromocionGeneral==1){
        r=false;
        msjG.mensaje('warning','Seleccione tipo de Operación',4000);
     }

     return r;
}

ValidaTipoLocal=function(){
  $("#TableListaprogramacion #txt_sucursal").val($("#ModalMatriculaForm #slct_ode_estudio_id option:selected").text());
  $("#TableListaprogramacion #txt_sucursal").hide();
  var v = 2;
  if( $("#rdbtipo3").prop("checked")==true ){
    v=3;
  }
  ValidaTipo(v);
}

ValidaTipo=function(v){
    console.log(v);
    $('#ModalMatriculaForm #tb_matricula, #ModalMatriculaForm #tb_pago').html('');
    $("#ModalMatriculaForm #txt_marketing,#ModalMatriculaForm #txt_marketing_id,#ModalMatriculaForm #txt_persona_caja,#ModalMatriculaForm #txt_persona_caja_id").val('');
    $("#ModalMatriculaForm select").not("#slct_ode_estudio_id,#slct_sucursal_id,#slct_sucursal_destino_id,#slct_tipo_participante_id").selectpicker('val','0');
    $("#txt_observacion").val('S/O');
    if( v==1 ){
        var html="<option value='0'>.::Curso Libre::.</option>";
        $("#ModalMatriculaForm #slct_especialidad2_id").html(html); 
        $("#ModalMatriculaForm #slct_especialidad2_id").selectpicker('refresh');
        $( "#ModalMatriculaForm #slct_especialidad2_id" ).prop("disabled",true);
    }
    else{
        persona_id = $("#ModalMatriculaForm #txt_persona_id").val();
        sucursal_id = $("#ModalMatriculaForm #slct_ode_estudio_id").val();
        AjaxMatricula.CargarEspecialidad(SlctCargarEspecialidad,v, persona_id, sucursal_id);
        $( "#ModalMatriculaForm #slct_especialidad2_id" ).removeAttr("disabled");
    }

    $(".cursospro2").css('display','none');
    $(".cursospro1").css('display','');
    if( v==3 ){
        $(".cursospro1").css('display','none');
        $(".cursospro2").css('display','');
    }
}

AgregarEditarAjax=function(){
    let tipo = $("#ModalMatriculaForm #slct_medio_captacion_id option:selected").attr('data-tipo');
    if( (tipo==1 || tipo==2) && $.trim( $("#txt_llamada_id").val() ) == '' ){
        sweetalertG.pregunta('LLamadas','No ha seleccionado "ID DE LLAMADA" ¿Desea continuar?', FinalizarRegistro);
    }
    else{
        FinalizarRegistro();
    }
}

FinalizarRegistro = () => {
    if( ValidaForm() && validaPromocion() ){
        //if( ValidaTabla() ){
            AjaxMatricula.AgregarEditar(HTMLAgregarEditar);
        /*}
        else{
            sweetalertG.pregunta('Inscripción sin pago','Usted esta inscribiendo al alumno sin registrar pago. ¿El alumno estudiará gratis y pagará cuando solicite el certificado?',EjecutarVenta);
        }*/
    }
}

EjecutarVenta=function(){
    $("#t_pago>tbody tr").each(function(){
                $(this).find("td:eq(1) input[type='text']").val('');
                $(this).find("td:eq(2) input[type='text']").val('0');
                $(this).find("td:eq(3) select").val('0');
                $(this).find("td:eq(4) input").val('');
                $(this).find("td:eq(5) input").val('');
    });
    AjaxMatricula.AgregarEditar(HTMLAgregarEditar);
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $("#ModalMatriculaForm input[type='hidden'],#ModalMatriculaForm input[type='text'],#ModalMatriculaForm textarea").not('.mant').val('');
        $("#ModalMatriculaForm select").selectpicker('val','0');
        $("#ModalMatriculaForm #slct_medio_captacion_id").selectpicker('val','');
        $('#ModalMatriculaForm #tb_matricula, #ModalMatriculaForm #tb_pago').html('');
        $("#txt_monto_promocion,#txt_nro_promocion").attr("disabled","true");
        $("#txt_observacion").val('S/O');
        $("#pago_img,#dni_img").attr('src','');
        $( "#ModalMatriculaForm #rdbtipo2" ).prop("checked",true);
        $( "#ModalMatriculaForm #rdbtipo3" ).removeAttr("checked");
        ValidaTipo(2);

        $( "#ModalMatriculaForm #txt_nro_pago_inscripcion, #ModalMatriculaForm #txt_nro_pago_matricula" ).prop("readOnly",true);
        $( "#ModalMatriculaForm #txt_monto_pago_inscripcion, #ModalMatriculaForm #txt_monto_pago_matricula" ).prop("readOnly",true);
        $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #slct_tipo_pago_matricula" ).prop("disabled",true);
        $( "#ModalMatriculaForm #txt_fecha_pago_inscripcion, #ModalMatriculaForm #txt_fecha_pago_matricula" ).prop("disabled",true);
        $( "#ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #slct_tipo_pago_matricula,#ModalMatriculaForm #slct_especialidad2_id" ).val("0");
        $( "#ModalMatriculaForm #txt_nro_pago_inscripcion, #ModalMatriculaForm #txt_nro_pago_matricula" ).val("");
        $( "#ModalMatriculaForm #txt_monto_pago_inscripcion, #ModalMatriculaForm #txt_monto_pago_matricula" ).val("");
        $("#ModalMatriculaForm #i_monto_deuda_inscripcion, #ModalMatriculaForm #i_monto_deuda_matricula" ).text("0");
        $( "#ModalMatriculaForm #pago_nombre_inscripcion, #ModalMatriculaForm #pago_nombre_matricula" ).val("");
        $( "#ModalMatriculaForm #pago_archivo_inscripcion, #ModalMatriculaForm #pago_archivo_matricula" ).val("");
        $( "#ModalMatriculaForm #txt_fecha_pago_inscripcion, #ModalMatriculaForm #txt_fecha_pago_matricula" ).val("");
        $( "#ModalMatriculaForm #dni_nombre" ).val("");
        $( "#ModalMatriculaForm #dni_archivo" ).val("");
        $( "#ModalMatriculaForm #file_inscripcion, #ModalMatriculaForm #file_matricula, #ModalMatriculaForm #file_dni" ).prop("disabled",true);
        $( "#ModalMatriculaForm #exonerar_inscripcion, #ModalMatriculaForm #exonerar_matricula" ).removeAttr("checked");
        $("#txt_monto_pago_inscripcion_ico, #ModalMatriculaForm #txt_monto_pago_matricula_ico,#i_monto_deuda_inscripcion_ico, #ModalMatriculaForm #i_monto_deuda_matricula_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    

        ActivarPago();
        MatriculaIdG = result.matricula_id;
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

DescargarFicha=function(){
    if( MatriculaIdG>0 ){
        //window.open('ReportDinamic/Reporte.SeminarioEM@ExportFicha'+'?matricula_id='+MatriculaIdG, '_blank');
        msjG.mensaje('info','.::En Proceso::.',5000);
    }
    else{
        msjG.mensaje('warning','Debe realizar una inscripción',5000);
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

ValidarCobro=function(v){
    precio_ins = $("#slct_especialidad2_id option:selected").attr("data-ins");
    precio_mat = $("#slct_especialidad2_id option:selected").attr("data-mat");
    id_ep = $("#slct_especialidad2_id option:selected").attr("data-id_ep");
    adicional = $("#slct_especialidad2_id option:selected").attr("data-adicional");

    $("#t_adicional").hide();
    $("#tb_adicional").html("");
    if( $.trim(adicional.split("|")[1]) != '' ){
        $("#t_adicional").show();
        $("#tb_adicional").html("Promoción de la venta: <span style='font-style: italic;'>"+$.trim(adicional).split("|")[1]+"</span></li>");
    }

    $("#ModalMatriculaForm #txt_especialidad_programacion_id2" ).val(id_ep);
    $("#precio_ins").html(precio_ins);
    $("#txt_monto_pago_inscripcion").attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda2('+precio_ins+',this);');
    $("#precio_mat").html(precio_mat);
    $("#txt_monto_pago_matricula").attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda3('+precio_mat+',this);');

    $('#exonerar_inscripcion,#exonerar_matricula').prop('checked', true);
    if( precio_ins*1==0 ){
      $('#exonerar_inscripcion').prop('checked', false);
    }
    if( precio_mat*1==0 ){
      $('#exonerar_matricula').prop('checked', false);
    }
    ValidaCheckInscripcion();
    ValidaCheckMatricula();
}

SlctCargarEspecialidad=function(result){
    var html="";
    $.each(result.data,function(index,r){
        adicional = '|';
        if( $.trim(r.adicional) != '' ){
            adicional = r.adicional;
        }
        html+="<option data-id_ep='"+r.id_ep+"' data-adicional='"+ adicional +"'  data-mat='"+r.matricula+"' data-ins='"+r.inscripcion+"' value='"+r.id+"'>"+r.especialidad+" => FI:"+r.fecha_inicio+" / Ins:"+r.inscripcion+" / Mat:"+r.matricula+"</option>";
    });
    $("#ModalMatriculaForm #slct_especialidad2_id").html(html); 
    $("#ModalMatriculaForm #slct_especialidad2_id").selectpicker('refresh');
    ValidarCobro(0);
};

SlctCargarMedioCaptacion=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    var html2=html;
    $.each(result.data,function(index,r){
        if( r.tipo_medio == '1' || r.tipo_medio == '2' ){
            html+="<option data-tipo='"+r.tipo_medio+"' value='"+r.id+"'>"+r.medio_captacion+"</option>";
        }
        else{
            html2+="<option data-tipo='"+r.tipo_medio+"' value='"+r.id+"'>"+r.medio_captacion+"</option>";
        }
    });
    $("#ModalMatriculaForm #slct_medio_captacion_id").html(html);
    $("#ModalMatriculaForm #slct_medio_captacion_id2").html(html2);
    $("#ModalMatriculaForm #slct_medio_captacion_id, #ModalMatriculaForm #slct_medio_captacion_id2").selectpicker('refresh');
}

SlctCargarBanco=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.banco+"</option>";
    });
    $("#ModalMatriculaForm #slct_tipo_pago_matricula, #ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #slct_tipo_pago").html(html);
    $("#ModalMatriculaForm #slct_tipo_pago_matricula, #ModalMatriculaForm #slct_tipo_pago_inscripcion, #ModalMatriculaForm #slct_tipo_pago").selectpicker('refresh');
}

SlctCargarSucursalTotal=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    html+="<option value='1'>A Domicilio</option>";
    var html2="<option value='0'>.::Seleccione::.</option>";

    $.each(result.data,function(index,r){
      html2+="<option value="+r.id+">"+r.sucursal+"</option>";
        if(r.id>1){
          html+="<option value="+r.id+">"+r.sucursal+"</option>";
        }
    });
    $("#ModalMatriculaForm #slct_sucursal_destino_id").html(html);
    $("#ModalMatriculaForm #slct_ode_estudio_id").html(html2);
    $("#ModalMatriculaForm #slct_sucursal_destino_id,#ModalMatriculaForm #slct_ode_estudio_id").selectpicker('refresh');

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
    var total=0;
    if(id==1){
        var contador=0;
        $("#t_pago textarea").each(function(){ contador++; });
        if(contador<=15){
            PromocionGeneral=1;
            $("#t_pago").hide(1500);
            $("#t_pago_promocion").show(1500);

            $("#t_pago>tbody tr").each(function(){
                $(this).find("td:eq(1) input[type='text']").val('0');
                $(this).find("td:eq(2) input[type='text']").val('0');
                total= $(this).find("td:eq(2) i").text()*1 + total*1;
                $(this).find("td:eq(3) select").val('0');
                $(this).find("td:eq(4) input[type='text']").val('');

                $(this).find("td:eq(1) input[type='text']").attr('readOnly','true');
                $(this).find("td:eq(2) input[type='text']").attr('readOnly','true');
                
                id= $(this).attr("id").split("_")[1];
                var saldo= $(this).find("td:eq(2) i").text()*1;
                $("#txt_monto_pago_certificado_ico"+id+",#i_monto_deuda_certificado_ico"+id).removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
                $("#i_monto_deuda_certificado"+id).text(saldo.toFixed(2));
            });

            $("#txt_nro_promocion").removeAttr('disabled');
            $("#txt_monto_promocion").removeAttr('disabled');
            $("#txt_fecha_pago_pro").removeAttr('disabled');
            $("#slct_tipo_pago").removeAttr('disabled');
            $("#txt_nro_promocion").val('');
            $("#txt_monto_promocion").val('');
            $("#txt_fecha_pago_pro").val('');
            $("#slct_tipo_pago").val('');
            $("#i_costo_promocion").text(total.toFixed(2));
        }
        else{
            //msjG.mensaje('warning','Para activar la promoción, requiere de '+PromocionG+' seminarios seleccionados',3000);
            msjG.mensaje('warning','El pago único solo puede darse hasta un máximo de 15 seminarios seleccionados',3000);
        }
    }
    else{
        $("#t_pago_promocion").hide(1500);
        $("#t_pago").show(1500);
        $("#t_pago>tbody tr").each(function(){
            $(this).find("td:eq(1) input[type='text']").val('');
            $(this).find("td:eq(2) input[type='text']").val('');
            total= $(this).find("td:eq(2) i").text()*1 + total*1;
            $(this).find("td:eq(3) select").val('');

            $(this).find("td:eq(1) input[type='text']").removeAttr('readOnly');
            $(this).find("td:eq(2) input[type='text']").removeAttr('readOnly');
        });
        $("#txt_nro_promocion").attr('disabled','true');
        $("#txt_monto_promocion").attr('disabled','true');
        $("#txt_fecha_pago_pro").attr('disabled','true');
        $("#slct_tipo_pago").attr('disabled','true');

        $("#txt_nro_promocion").val('0');
        $("#txt_monto_promocion").val('0');
        $("#txt_fecha_pago_pro").val('');
        $("#slct_tipo_pago").val('0');
        $("#i_costo_promocion").text(total.toFixed(2));
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

ValidaDeuda3 = function(c,t){
    $("#txt_monto_pago_matricula_ico,#i_monto_deuda_matricula_ico").removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_pago_matricula_ico,#i_monto_deuda_matricula_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda_matricula").text(saldo.toFixed(2));
}
</script>
