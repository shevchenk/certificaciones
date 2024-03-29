<?php 
use App\Models\Proceso\Promocion;
$promocionG=Promocion::where('estado',1)->first(); 
?>
<script type="text/javascript">
var LDfiltrosG='';
var LDTipoTabla=0;
var PromocionG="<?php echo $promocionG->pae ?>";
var CheckG=0; // Indica los checks q tiene actualmente
var CheckedG=0; // Indica cuantos tiene
var buttonG='';
var bfiltrosG='';
$(document).ready(function() {
    $("#TableListaprogramacion,#TableListaprogramacion2").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });

    $("#ListaprogramacionForm2 #TableListaprogramacion2 select").change(function(){ AjaxListaprogramacion.Cargar2(HTMLCargarProgramacion2); });
    $("#ListaprogramacionForm2 #TableListaprogramacion2 input").blur(function(){ AjaxListaprogramacion.Cargar2(HTMLCargarProgramacion2); });

    $('#ModalListaprogramacion').on('shown.bs.modal', function (event) {
          buttonG = $(event.relatedTarget); // captura al boton
          bfiltrosG= buttonG.data('filtros');
          $("#ModalListaprogramacion #slct_tipo_modalidad_id").selectpicker('val','0');
          $("#ModalListaprogramacion #slct_tipo_modalidad_id").change();
    });

    $('#ModalListaprogramacion').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
//        $("ModalDocenteForm input").val('');
    });

    $( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).change(function() {
            LDfiltrosG='';
            if( typeof (bfiltrosG)!='undefined'){
                LDfiltrosG=bfiltrosG+'|tipo_modalidad:'+$( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).val();
                console.log($("#slct_especialidad2_id").val());
                if( typeof ($("#slct_especialidad2_id").val())!='undefined' && $("#slct_especialidad2_id" ).val()!='' && $("#slct_especialidad2_id" ).val()!='0' ){
                    LDfiltrosG=LDfiltrosG+'|especialidad_id:'+$("#slct_especialidad2_id" ).val();
                }
                if( typeof ($("#txt_persona_id").val())!='undefined' && $("#txt_persona_id" ).val()!='' && $("#txt_persona_id" ).val()!='0' ){
                    LDfiltrosG=LDfiltrosG+'|especialidad_persona_id:'+$("#txt_persona_id" ).val();
                }
            }
            if( typeof (buttonG.data('tipotabla'))!='undefined'){
                LDTipoTabla=buttonG.data('tipotabla');
            }
          AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);
  });

    $('#ModalListaprogramacion2').on('shown.bs.modal', function (event) {
          buttonG = $(event.relatedTarget); // captura al boton
          LDfiltrosG='';
          bfiltrosG= buttonG.data('filtros');
          if( typeof (bfiltrosG)!='undefined'){
                if( typeof ($("#slct_especialidad2_id").val())!='undefined' && $("#slct_especialidad2_id" ).val()!='' && $("#slct_especialidad2_id" ).val()!='0' ){
                    LDfiltrosG=LDfiltrosG+'|especialidad_id:'+$("#slct_especialidad2_id" ).val();
                }
                if( typeof ($("#txt_persona_id").val())!='undefined' && $("#txt_persona_id" ).val()!='' && $("#txt_persona_id" ).val()!='0' ){
                    LDfiltrosG=LDfiltrosG+'|especialidad_persona_id:'+$("#txt_persona_id" ).val();
                }
            }
            if( typeof (buttonG.data('tipotabla'))!='undefined'){
                LDTipoTabla=buttonG.data('tipotabla');
            }
          AjaxListaprogramacion.Cargar2(HTMLCargarProgramacion2);
    });
});

ValidaCheck=function(){
    cont = 0;
    $("#ModalListaprogramacion table tbody input[type='checkbox']").each(function(t){
        if( $(this).is(":checked") ){
            cont++;
            SeleccionarProgramacion(0, $(this).val());
        }
    });

    if( cont == 0 ){
        msjG.mensaje('warning',"Seleccione almenos 1 check",6000);
    }
    else{
        $("#ModalListaprogramacion").modal('hide');
    }
}

ValidaCheck2=function(){
    cont = 0;
    $("#ModalListaprogramacion2 table tbody input[type='checkbox']").each(function(t){
        if( $(this).is(":checked") ){
            cont++;
            SeleccionarProgramacion(0, $(this).val(),2);
        }
    });

    if( cont == 0 ){
        msjG.mensaje('warning',"Seleccione almenos 1 check",6000);
    }
    else{
        $("#ModalListaprogramacion2").modal('hide');
    }
}

SeleccionarProgramacion = function(val,id, tipo){
    tipo = $.trim(tipo); //Para identificar q tabla es
    var existe=$("#t_matricula #trid_"+id+"").val();
    if( val==0 && typeof(existe)=='undefined'){
        var mod='PRESENCIAL';
        var docente=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .docente").text();
        var persona_id=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .persona_id").val();
        var sucursal_id=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .sucursal_id").val();
        var sucursal=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .sucursal:eq(0)").text();
        var curso_id=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .curso_id").val();
        var dia=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .dia").val();
        var turno=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .turno").val();
        var curso=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .curso").text();
        var costo=$("#TableListaprogramacion"+tipo+" #trid_"+id+" .costo").val();
        var fecha_inicio=$.trim($("#TableListaprogramacion"+tipo+" #trid_"+id+" .fecha_inicio").val());
        var fecha_final=$.trim($("#TableListaprogramacion"+tipo+" #trid_"+id+" .fecha_final").val());
        var fecha_i=fecha_inicio.split(" ");
        var fecha_f=fecha_final.split(" ");
        if(sucursal_id==1 || sucursal == 'ONLINE' || sucursal == 'ON LINE'){
            var mod='ONLINE';
        }
        if(turno==''){
            var turno='S/T';
        }
        var html='';
          html+="<tr id='trid_"+id+"'>"+
            "<td>"+
            "<input type='text' class='form-control' value='"+mod+"' disabled></td>"+
            "<td><textarea class='form-control' rows='3' disabled>"+curso+"</textarea></td>"+
            "<td><input type='text' class='form-control' value='"+fecha_i[0]+"' disabled></td>"+
            "<td><textarea class='form-control' rows='3' disabled>Hora: "+fecha_i[1].substr(0,8)+"-"+fecha_f[1].substr(0,8)+"\nDia(s): "+dia+" \nTurno: "+turno+"</textarea></td>"+
            "<td><input type='text' class='form-control' value='"+sucursal+"' disabled></td>"+
            '<td><a onClick="Eliminar('+id+')" class="btn btn-danger" ><i class="fa fa-trash fa-lg"></i></a></td>';
          html+="</tr>";
        
        $("#t_matricula").append(html);
        CheckG++;
        //CheckedG++;
        var checkedlocal=""; //checked
        if( CheckG>PromocionG ){
            //CheckedG--;
            checkedlocal="";
        }
        
          var html1='';
          if(LDTipoTabla==0){
          html1="<tr id='trid_"+id+"'>"+
            "<td><input type='hidden' id='txt_programacion_id' name='txt_programacion_id[]' class='form-control' value='"+id+"' readOnly>"+
            "<input type='hidden' name='txt_curso_id[]' class='form-control' value='"+curso_id+"' readOnly>"+
            "<div class='input-group margin'>"+
                "<span class='input-group-btn'><label>"+
                  "<input type='checkbox' name='checks[]' value='100' class='flattodo"+id+"'>"+
                "</label></span>"+
                "<span class='input-group-btn'><label>"+
                  "<input type='checkbox' name='checks[]' value='100' class='flatcurso"+id+"'>"+
                "</label></span>"+
                "<input type='text' class='form-control'  value='"+curso+"'  disabled>"+
            "</div>"+
            "</td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago"+id+"' name='txt_nro_pago[]'></td>"+
            "<td><input type='text' class='form-control'  id='txt_monto_pago"+id+"' name='txt_monto_pago[]' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>"+
            "<td>"+
                '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre'+id+'"  name="pago_nombre[]" value="">'+
                '<input type="text" style="display: none;" id="pago_archivo'+id+'" name="pago_archivo[]">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre'+id+'\',\'#pago_archivo'+id+'\',\'#pago_img'+id+'\');" >'+
                '</label>'+
                '<div><a id="pago_href'+id+'">'+
            '<img id="pago_img'+id+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a></div>'+
            "</td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago_certificado"+id+"' name='txt_nro_pago_certificado[]' value='0'></td>"+
            "<td><input type='text' class='form-control'  id='txt_monto_pago_certificado"+id+"' name='txt_monto_pago_certificado[]' value='0' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>"+
            "<td><select class='form-control'  id='slct_tipo_pago_detalle"+id+"' name='slct_tipo_pago_detalle[]'>"+
                $("#ModalMatriculaForm #slct_tipo_pago_inscripcion").html()+
                "</select></td>"+
            '<td>'+
                '<div class="input-group">'+
                    '<span id="spn_fecha_pago_certificado'+id+'" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>'+
                    '<input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_pago_certificado'+id+'" name="txt_fecha_pago_certificado[]" readonly/>'+
                '</div>'+
            '</td>'+
            "<td>"+
                '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre_certificado'+id+'"  name="pago_nombre_certificado[]" value="">'+
                '<input type="text" style="display: none;" id="pago_archivo_certificado'+id+'" name="pago_archivo_certificado[]">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre_certificado'+id+'\',\'#pago_archivo_certificado'+id+'\',\'#pago_certificado_img'+id+'\');" >'+
                '</label>'+
                '<div><a id="pago_certificado_href'+id+'">'+
            '<img id="pago_certificado_img'+id+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a></div>'+
            "</td>"+
            "<td>"+
                "<label>"+
                  "<input type='checkbox' name='checks[]' value='100' class='flat"+id+" promo' "+checkedlocal+">"+
                "</label>"+
            "</td>";
          html1+="</tr>";
        }else {
          html1="<tr id='trid_"+id+"'>"+
            "<td><input type='hidden' id='txt_programacion_id' name='txt_programacion_id[]' class='form-control' value='"+id+"' readOnly>"+
            "<input type='hidden' name='txt_curso_id[]' class='form-control' value='"+curso_id+"' readOnly>"+
            "<div class=''>"+
                "<span class='input-group-btn' style='display:none;'><label>"+
                  "<input type='checkbox' name='checks[]' value='100' class='flattodo"+id+"'>"+
                "</label></span>"+
                "<textarea type='text' class='form-control' rows='3' disabled>"+curso+"</textarea>"+
            "</div>"+
            "</td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago_certificado"+id+"' name='txt_nro_pago_certificado[]'></td>"+
            "<td>"+
                "<div class='input-group'>"+
                    "<div class='input-group-addon'>"+
                    "<i>"+costo+"</i>"+
                    "</div>"+
                    '<div id="txt_monto_pago_certificado_ico'+id+'" class="has-warning has-feedback">'+
                        "<input type='text' class='form-control'  id='txt_monto_pago_certificado"+id+"' name='txt_monto_pago_certificado[]'"+
                        " onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(\""+costo+"\",this,\""+id+"\");'>"+
                        '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                    '</div>'+
                "</div>"+
                '<div id="i_monto_deuda_certificado_ico'+id+'" class="has-warning has-feedback">'+
                    "<div class='input-group-addon'>"+
                    "<label>Deuda:</label>"+
                    "<label id='i_monto_deuda_certificado"+id+"'>"+costo+"</label>"+
                    '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                    "</div>"+
                '</div>'+
            "</td>"+
            "<td><select class='form-control'  id='slct_tipo_pago_detalle"+id+"' name='slct_tipo_pago_detalle[]'>"+
                $("#ModalMatriculaForm #slct_tipo_pago_inscripcion").html()+
                "</select></td>"+
            '<td>'+
                '<div class="input-group">'+
                    '<span id="spn_fecha_pago_certificado'+id+'" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>'+
                    '<input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_pago_certificado'+id+'" name="txt_fecha_pago_certificado[]" readonly/>'+
                '</div>'+
            '</td>'+
            "<td>"+
                '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre_certificado'+id+'"  name="pago_nombre_certificado[]" value="">'+
                '<input type="text" style="display: none;" id="pago_archivo_certificado'+id+'" name="pago_archivo_certificado[]">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre_certificado'+id+'\',\'#pago_archivo_certificado'+id+'\',\'#pago_certificado_img'+id+'\');" >'+
                '</label>'+
                '<div><a id="pago_certificado_href'+id+'">'+
            '<img id="pago_certificado_img'+id+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a></div>'+
            "</td>"+
            "<td style='display:none;'>"+
                '<input type="text"  readOnly class="form-control input-sm" id="dni_nombre_detalle'+id+'"  name="dni_nombre_detalle[]" value="">'+
                '<input type="text" style="display: none;" id="dni_archivo_detalle'+id+'" name="dni_archivo_detalle[]">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#dni_nombre_detalle'+id+'\',\'#dni_archivo_detalle'+id+'\',\'#dni_archivo_img'+id+'\');" >'+
                '</label>'+
                '<div><a id="dni_archivo_href'+id+'">'+
            '<img id="dni_archivo_img'+id+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a></div>'+
            "</td>"+
            "<td style='display:none;'>"+
                "<label>"+
                  "<input type='checkbox' name='checks[]' value='100' class='flat"+id+" promo' "+checkedlocal+">"+
                "</label>"+
            "</td>";
          html1+="</tr>";
        }
        $("#t_pago").append(html1);
        $("#spn_fecha_pago_certificado"+id).click(()=>{ $("#txt_fecha_pago_certificado"+id).focus(); });
        $(".fecha").datetimepicker({
            format: "yyyy-mm-dd",
            language: 'es',
            showMeridian: false,
            time:false,
            minView:2,
            autoclose: true,
            todayBtn: false
        });
        $("#promocion_seminario").text('');
        $("#t_pago textarea").each(function(){
            salto='';
            if( $("#promocion_seminario").text()!='' ){
                salto='<hr>';
            }
            $("#promocion_seminario").append(salto+$(this).text());
        })

        $('input[type="checkbox"].flat'+id).iCheck({
          checkboxClass: 'icheckbox_flat-green'
        }).on('ifChanged', function(e) {
            // Get the field name
            var isChecked = e.currentTarget.checked;

            if ($('input[type="checkbox"].flattodo'+id).is(':checked')) {
            }
            else{
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id).val("0");
                if (isChecked == true) {
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id).attr("readOnly","true");
                    CheckedG++;
                }
                else{
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id).removeAttr("readOnly");
                    CheckedG--;
                }
            }

            $("#txt_nro_promocion,#txt_monto_promocion").removeAttr("disabled");
            $("#txt_nro_promocion,#txt_monto_promocion").val("");
            var cont=0;
            $("#t_pago tr").each(function(){
              if( $(this).find("input[type='checkbox'].promo").is(':checked') ){
                cont++;
              }
            });

            if(cont<PromocionG){
                $("#txt_nro_promocion,#txt_monto_promocion").attr("disabled","true");
                $("#txt_nro_promocion,#txt_monto_promocion").val("0");
            }

        });

        $('input[type="checkbox"].flatcurso'+id).iCheck({
          checkboxClass: 'icheckbox_flat-green'
        }).on('ifChanged', function(e) {
            // Get the field name
            var isChecked = e.currentTarget.checked;
            if ($('input[type="checkbox"].flattodo'+id).is(':checked')) {
            }
            else{
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).val("0");
                if (isChecked == true) {
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).attr("readOnly","true");
                    CheckedG++;
                }
                else{
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).removeAttr("readOnly");
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).val("");
                    CheckedG--;
                }
            }

        });

        $('input[type="checkbox"].flattodo'+id).iCheck({
          checkboxClass: 'icheckbox_flat-blue'
        }).on('ifChanged', function(e) {
            // Get the field name
            var isChecked = e.currentTarget.checked;

            if ($('input[type="checkbox"].flat'+id).is(':checked')) {
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).val("0");
                if (isChecked == true) {
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).attr("readOnly","true");
                    CheckedG++;
                }
                else{
                    $('input[type="checkbox"].flatcurso'+id).iCheck('uncheck');
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).removeAttr("readOnly");
                    $("#txt_nro_pago"+id+",#txt_monto_pago"+id).val("");
                    CheckedG--;
                }
            }
            else{
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id+",#txt_nro_pago"+id+",#txt_monto_pago"+id).val("0");
                if (isChecked == true) {
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id+",#txt_nro_pago"+id+",#txt_monto_pago"+id).attr("readOnly","true");
                    CheckedG++;
                }
                else{
                    $('input[type="checkbox"].flatcurso'+id).iCheck('uncheck');
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id+",#txt_nro_pago"+id+",#txt_monto_pago"+id).removeAttr("readOnly");
                    $("#txt_nro_pago_certificado"+id+",#txt_monto_pago_certificado"+id+",#txt_nro_pago"+id+",#txt_monto_pago"+id).val("");
                    CheckedG--;
                }
            }

        });


        
        $('#ModalListaprogramacion').modal('hide');
    }else {
        msjG.mensaje('warning',"Ya se agregó el  Curso",3000);
    }
};

onImagen= function (ev,nombre,archivo,src) {
        var files = ev.target.files || ev.dataTransfer.files;
        if (!files.length)
            return;
        var image = new Image();
        var reader = new FileReader();
        reader.onload = (e) => {
            $(archivo).val(e.target.result);
            $(src).attr('src',e.target.result);
        };
        reader.onprogress=() => {
            //msjG.mensaje('warning','Cargando yo ando',2000);
        }
        reader.readAsDataURL(files[0]);
        $(nombre).val(files[0].name);
        console.log(files[0].name);
    }
    
onPagos=function(item,val){
    if(val==1){ etiqueta="_certificado";}
    if(val==3){ etiqueta="_matricula";}
    if(val==4){ etiqueta="_inscripcion";}
    if(val==5){ etiqueta="_promocion";}
    if(val==2){etiqueta="";}
    
    var files = event.target.files || event.dataTransfer.files;
    if (!files.length)
      return;
    var image = new Image();
    var reader = new FileReader();
    reader.onload = (e) => {
        if(val==3){
            $("#t_pago_matricula  #pago_archivo"+etiqueta).val(event.target.result);
        }else if(val==4){
            $("#t_pago_inscripcion  #pago_archivo"+etiqueta).val(event.target.result);
        }else if(val==5){
            $("#t_pago_promocion  #pago_archivo"+etiqueta).val(event.target.result);
        }else {
            $("#t_pago #trid_"+item+" #pago_archivo"+etiqueta+item).val(event.target.result);
        }
        //console.log(event.target.result);
    };
    reader.readAsDataURL(files[0]);
    if(val==3){
         $("#t_pago_matricula  #pago_nombre"+etiqueta).val(files[0].name);
    }else if(val==4){
         $("#t_pago_inscripcion  #pago_nombre"+etiqueta).val(files[0].name);
    }else if(val==5){
         $("#t_pago_promocion  #pago_nombre"+etiqueta).val(files[0].name);
    }else {
         $("#t_pago #trid_"+item+" #pago_nombre"+etiqueta+item).val(files[0].name);
    }
    
//    console.log(files[0].name);
};    

ValidaDeuda = function(c,t,id){
    $("#txt_monto_pago_certificado_ico"+id+",#i_monto_deuda_certificado_ico"+id).removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_pago_certificado_ico"+id+",#i_monto_deuda_certificado_ico"+id).removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda_certificado"+id).text(saldo.toFixed(2));
}

Eliminar = function (tr) {
        var c = confirm("¿Está seguro de Eliminar el Curso?");
        if (c) {

            if( $("#t_matricula #trid_"+tr+" input[type='checkbox'].flat").is(':checked') ){
                CheckedG--;
            }

            $("#t_matricula #trid_"+tr+"").remove();
            $("#t_pago #trid_"+tr+"").remove();
            CheckG--;
        }

        $("#promocion_seminario").text('');
        $("#t_pago textarea").each(function(){
            console.log($(this).text());
            salto='';
            if( $("#promocion_seminario").text()!='' ){
                salto='<hr>';
            }
            $("#promocion_seminario").append(salto+$(this).text());
        })
};
    
    
HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableListaprogramacion').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        validasem="style='display:none;'";
        if(r.tipo_curso==1){
            validasem='';
        }
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='turnos'>"+r.turno+"</td>"+
            "<td "+validasem+">"+r.dia+"</td>"+
            "<td class='aula' "+validasem+">"+r.aula+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[0]+"</td>"+
            "<td "+validasem+">"+r.fecha_final.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[1]+"</td>"+
            "<td "+validasem+">"+r.fecha_final.split(" ")[1]+"</td>"+
            "<td><input type='checkbox' value='"+r.id+"'>"+
            //'<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='costo' value='"+r.costo+"'>"+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='turno' value='"+r.turno+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='fecha_inicio' value='"+r.fecha_inicio+"'>"+
            "<input type='hidden' class='fecha_final' value='"+r.fecha_final+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaprogramacion tbody").html(html); 
    $("#TableListaprogramacion").DataTable({
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
            $('#TableListaprogramacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxListaprogramacion',result.data,'#TableListaprogramacion_paginate');
        } 
    });
};

HTMLCargarProgramacion2=function(result){
    var html="";
    $('#TableListaprogramacion2').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        validasem="style='display:none;'";
        if(r.tipo_curso==1){
            validasem='';
        }
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='turnos'>"+r.turno+"</td>"+
            "<td "+validasem+">"+r.dia+"</td>"+
            "<td class='aula' "+validasem+">"+r.aula+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[1]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[1]+"</td>"+
            "<td><input type='checkbox' value='"+r.id+"'>"+
            //'<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='costo' value='"+r.costo+"'>"+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='turno' value='"+r.turno+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='fecha_inicio' value='"+r.fecha_inicio+"'>"+
            "<input type='hidden' class='fecha_final' value='"+r.fecha_final+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaprogramacion2 tbody").html(html); 
    $("#TableListaprogramacion2").DataTable({
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
            $('#TableListaprogramacion2_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion2','AjaxListaprogramacion.Cargar2',result.data,'#TableListaprogramacion2_paginate');
        } 
    });
};
</script>
