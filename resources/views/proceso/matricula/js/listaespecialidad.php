<script type="text/javascript">
var LDfiltrosG='';
var LDTipoTabla=0;
var EspecialidadIDG=0;
$(document).ready(function() {
    $("#TableListaespecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListaespecialidadForm #TableListaespecialidad input").blur(function(){ AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad); });
    $("#ListaespecialidadForm #slct_ode_estudio_id").change(function(){ AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad); });

    $('#ModalListaespecialidad').on('shown.bs.modal', function (event) {
          var button = $(event.relatedTarget); // captura al boton
          if( $("#ModalMatriculaForm #txt_persona_id").val()=='' ){
            msjG.mensaje('warning','Seleccione Persona',4000);
            $("#ModalListaespecialidad").modal('hide');
          }
          else{
            AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad);
          }
    });

    $('#ModalListaespecialidad').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
    });

    $("#TableListaprogramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $("#ModalListaprogramacion #slct_tipo_modalidad_id").attr("onChange","ListaProgramacionModalidad()");
   
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });

    $('#ModalListaprogramacion').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget); // captura al boton
        var bfiltros= button.data('filtros');
        if( typeof (bfiltros)!='undefined'){
            //LDfiltrosG=bfiltros+'|tipo_modalidad:'+$("#ModalListaprogramacion #slct_tipo_modalidad_id" ).val();
            LDfiltrosG=bfiltros;
        }
        $("#ModalListaprogramacion #slct_tipo_modalidad_id").selectpicker('val','0');
        ListaProgramacionModalidad();
    });
    AjaxListaespecialidad.CargarSucursalTotal(SlctCargarSucursalPago);
});

SlctCargarSucursalPago=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ListaespecialidadForm #slct_ode_estudio_id").html(html); 
    $("#ListaespecialidadForm #slct_ode_estudio_id").selectpicker('refresh');
};


ListaProgramacionModalidad=function(){
    AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);
}

HTMLCargarEspecialidad=function(result){
    var html="";
    $('#TableListaespecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        detalle= "<ul><li> Tipo de Pago: <span style='color:red;'>Por Curso</span> </li></ul>";
        cuota='';
        if( r.tipo==1 ){
            detalle= "<ul><li> Tipo de Pago: <span style='color:red;'>Por Cuota(s)</span> </li><li> Escala: <span style='color:red;' class='costo'>"+r.nro_cuota+"</span> </li><li> Costo Ins.: <span style='color:red;' class='precio'>"+r.costo+"</span> </li><li> Costo Mat.: <span style='color:red;' class='precio_mat'>"+r.costo_mat+"</span> </li></ul>";
            cuota="<ol><li>C - "+$.trim(r.cronograma).split("|").join("</li><li>C - ")+"</li></ol>";
        }
        if( $.trim(r.adicional) != '' ){
            cuota +="<hr><h3 class='text-bold'>Adicional:</h3><ul><li>"+$.trim(r.adicional).split("|").join("</li><li>")+"</li></ul>";
        }
        // +r.fecha_inicio+"<hr>" para limpiar fecha
        html+="<tr id='trides_"+r.id+"'>"+
            "<td class='col-md-4'><div class='especialidad'>"+r.especialidad+"</div><hr><input type='hidden' class='tipo' value='"+r.tipo+"'>"+detalle+"</td>"+
            "<td class='fecha_inicio col-md-1'>"+cuota+"</td>"+
            "<td class='curso col-md-5'>"+
                "<table class='table table-bordered table-hover'>"+
                    "<thead><tr>"+
                        "<th class='col-md-2'>N° Orden</th>"+
                        "<th class='col-md-10'>Inicio / Curso</th>"+
                        /*"<th>N Veces</th>"+
                        "<th>Nota</th>"+*/
                    "</tr></thead>"+
                    "<tbdoy id='table_"+r.id+"'><tr>"+
                        "<td>"+$.trim(r.cursos).split('|').join('</td><td>').split("^^").join("</td></tr><tr><td>")+"</td>"+
                    "</tr></tbody>"+
                "</table>"+
            "</td>"+
            "<td class='col-md-1'>"+
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarEspecialidad(\''+r.id+'\')"+><i class="glyphicon glyphicon-ok"></i></span>';
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaespecialidad tbody").html(html); 
    $("#TableListaespecialidad").DataTable({
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
            $('#TableListaespecialidad_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEspecialidad','AjaxListaespecialidad',result.data,'#TableListaespecialidad_paginate');
        } 
    });
};

SeleccionarEspecialidad=function(id){
    ProgramacionIdG = false; // este campo viene de especialidad js y resetea el valor de programacion seleccionado
    EspecialidadIDG=id;
    $("#ModalMatriculaForm #txt_ode_estudio_id").val($("#ModalListaespecialidad #slct_ode_estudio_id option:selected").val());
    $("#TableListaprogramacion #txt_sucursal").val($("#ModalListaespecialidad #slct_ode_estudio_id option:selected").text());
    $("#TableListaprogramacion #txt_sucursal").hide();
    var html=''; var cursos=''; var seminario=''; var html2='';
        $("#trides_"+id+" table>tbody>tr").each( function(index){
          html+="<tr id='tres_"+id+"_"+$(this).find('td:eq(0) .curso_id').val()+"'>"+
            "<td><input type='text' class='form-control' value='"+$('#trides_'+id+' .especialidad').text()+"' disabled></td>"+
            "<td><textarea rows='2' class='form-control' disabled>"+$(this).find('td:eq(1)').text()+"</textarea></td>"+
            "<td><input type='hidden' class='form-control' id='txt_programacion_id' name='txt_programacion_id[]' value=''><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled>"+
                "<input type='hidden' class='form-control' name='txt_curso_id[]' value='"+$(this).find('td:eq(0) .curso_id').val()+"'>"+
                "<input type='hidden' class='form-control' id='txt_especialidad_id' name='txt_especialidad_id[]' value='"+id.split("_")[0]+"'>"+
                "<input type='hidden' class='form-control' id='txt_especialidad_programacion_id' name='txt_especialidad_programacion_id[]' value='"+id.split("_")[1]+"'>"+
            "</td>"+
            '<td><button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1|tipo_curso:1|curso_id:'+$(this).find('td:eq(0) .curso_id').val()+'" data-tipotabla="1">Buscar Inicio / Curso</button></td>';
          html+="</tr>";

          costo = $('#trides_'+id).find('td:eq(0) .costo').text().split('-')[1];
          html2+=''+
            '<tr>'+
                '<td>'+$(this).find('td:eq(1)').text()+'</td>'+
                '<td><input type="text" class="form-control" id="txt_nro_certificado" name="txt_nro_certificado[]" value="" placeholder="Nro"></td>'+
                '<td>'+
                    "<div class='input-group'>"+
                        "<div class='input-group-addon'>"+
                        "<i>"+costo+"</i>"+
                        "</div>"+
                        '<div id="txt_monto_certificado_ico'+index+'" class="has-warning has-feedback">'+
                            "<input type='text' class='form-control'  id='txt_monto_certificado"+index+"' name='txt_monto_certificado[]'"+
                            " onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(\""+costo+"\",this,\""+index+"\");'>"+
                            '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                        '</div>'+
                    "</div>"+
                    '<div id="i_monto_deuda_certificado_ico'+index+'" class="has-warning has-feedback">'+
                        "<div class='input-group-addon'>"+
                        "<label>Deuda:</label>"+
                        "<label id='i_monto_deuda_certificado"+index+"'>"+costo+"</label>"+
                        '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                        "</div>"+
                    '</div>'+
                '</td>'+
                '<td><select class="form-control"  id="slct_tipo_pago_certificado" name="slct_tipo_pago_certificado[]">'+
                    '<option value=\'0\'>.::Seleccione::.</option>'+
                    "<option value='1.1'>Transferencia - BCP</option>"+
                    "<option value='1.2'>Transferencia - Scotiabank</option>"+
                    "<option value='1.3'>Transferencia - BBVA</option>"+
                    "<option value='1.4'>Transferencia - Interbank</option>"+
                    "<option value='2.1'>Depósito - BCP</option>"+
                    "<option value='2.2'>Depósito - Scotiabank</option>"+
                    "<option value='2.3'>Depósito - BBVA</option>"+
                    "<option value='2.4'>Depósito - Interbank</option>"+
                    '<option value=\'3.0\'>Caja</option>'+
                    '</select></td>'+
                '<td>'+
                    '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre_certificado'+index+'"  name="pago_nombre_certificado[]" value="">'+
                    '<input type="text" style="display: none;" id="pago_archivo_certificado'+index+'" name="pago_archivo_certificado[]">'+
                    '<label class="btn btn-default btn-flat margin btn-xs">'+
                        '<i class="fa fa-file-image-o fa-3x"></i>'+
                        '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                        '<i class="fa fa-file-word-o fa-3x"></i>'+
                        '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre_certificado'+index+'\',\'#pago_archivo_certificado'+index+'\',\'#pago_img_certificado'+index+'\');" >'+
                    '</label>'+
                    '<div>'+
                    '<a id="pago_href">'+
                    '<img id="pago_img_certificado'+index+'" class="img-circle" style="height: 80px;width: 120px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                    '</a>'+
                    '</div>'+
                '</td>'+
            '</tr>';

          cursos+="<li>"+$(this).find('td:eq(1)').text()+"</li>";
        })
          seminario="<li>"+$('#trides_'+id+' .especialidad').text()+"</li>";
          precio=$('#trides_'+id+' .precio').text();
          precio_mat=$('#trides_'+id+' .precio_mat').text();

          $('#exonerar_inscripcion,#exonerar_matricula').prop('checked', true);
          if( precio*1==0 ){
            $('#exonerar_inscripcion').prop('checked', false);
          }
          if( precio_mat*1==0 ){
            $('#exonerar_matricula').prop('checked', false);
          }
          ValidaCheckInscripcion();
          ValidaCheckMatricula();

        $(".promocion_seminario").html("<ul>"+seminario+"</ul>");
        $("#precio").html(precio);
        $("#txt_monto_pago_inscripcion").attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda2('+precio+',this);');
        $("#precio_mat").html(precio_mat);
        $("#txt_monto_pago_matricula").attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda3('+precio_mat+',this);');
        $("#tb_matricula").html(html);

        html=''; htmladicional = '';
        $("#t_adicional").hide();
        $("#titpago").text("Pago de Cuotas de la Especialidad");
        $("#subtitpago").text("Cuota(s) Programadas");
        if( $('#trides_'+id+' .tipo').val()==2 ){
            $("#titpago").text("Pago por Curso de la Especialidad");
            $("#subtitpago").text("Curso(s) de la Especialidad");
            html= html2;
        }
        else{
             //costo = $('#trides_'+id).find('td:eq(0) .costo').text().split('-')[1];
            $("#trides_"+id+" ol>li").each( function(index){
                costo = $(this).text().split(" - ")[2];
                html+=''+
                '<tr>'+
                    '<td><input type="hidden" value="'+(index*1+1)+'" name="txt_cuota[]">'+(index*1+1)+$(this).text()+'</td>'+
                    '<td><input type="text" class="form-control" id="txt_nro_cuota" name="txt_nro_cuota[]" value="" placeholder="Nro"></td>'+
                    '<td>'+
                        "<div class='input-group'>"+
                            "<div class='input-group-addon'>"+
                            "<i>"+costo+"</i>"+
                            "</div>"+
                            '<div id="txt_monto_cuota_ico'+index+'" class="has-warning has-feedback">'+
                                "<input type='text' class='form-control'  id='txt_monto_cuota"+index+"' name='txt_monto_cuota[]'"+
                                " onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(\""+costo+"\",this,\""+index+"\");'>"+
                                '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                            '</div>'+
                        "</div>"+
                        '<div id="i_monto_deuda_certificado_ico'+index+'" class="has-warning has-feedback">'+
                            "<div class='input-group-addon'>"+
                            "<label>Deuda:</label>"+
                            "<label id='i_monto_deuda_certificado"+index+"'>"+costo+"</label>"+
                            '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                            "</div>"+
                        '</div>'+
                    '</td>'+
                    '<td><select class="form-control"  id="slct_tipo_pago_cuota" name="slct_tipo_pago_cuota[]">'+
                        '<option value=\'0\'>.::Seleccione::.</option>'+
                        "<option value='1.1'>Transferencia - BCP</option>"+
                        "<option value='1.2'>Transferencia - Scotiabank</option>"+
                        "<option value='1.3'>Transferencia - BBVA</option>"+
                        "<option value='1.4'>Transferencia - Interbank</option>"+
                        "<option value='2.1'>Depósito - BCP</option>"+
                        "<option value='2.2'>Depósito - Scotiabank</option>"+
                        "<option value='2.3'>Depósito - BBVA</option>"+
                        "<option value='2.4'>Depósito - Interbank</option>"+
                        '<option value=\'3.0\'>Caja</option>'+
                        '</select></td>'+
                    '<td>'+
                        '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre_cuota'+index+'"  name="pago_nombre_cuota[]" value="">'+
                        '<input type="text" style="display: none;" id="pago_archivo_cuota'+index+'" name="pago_archivo_cuota[]">'+
                        '<label class="btn btn-default btn-flat margin btn-xs">'+
                            '<i class="fa fa-file-image-o fa-3x"></i>'+
                            '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                            '<i class="fa fa-file-word-o fa-3x"></i>'+
                            '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre_cuota'+index+'\',\'#pago_archivo_cuota'+index+'\',\'#pago_img_cuota'+index+'\');" >'+
                        '</label>'+
                        '<div>'+
                        '<a id="pago_href">'+
                        '<img id="pago_img_cuota'+index+'" class="img-circle" style="height: 80px;width: 120px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                        '</a>'+
                        '</div>'+
                    '</td>'+
                '</tr>';
            })

            $("#trides_"+id+" td:eq(1) ul>li").each( function(index){
                htmladicional+=''+
                '<tr>'+
                    '<td>'+$(this).text()+'</td>'+
                '</tr>';
            });
        }
        if( htmladicional != '' ){
            $("#t_adicional").show();
        }
        $("#tb_adicional").html(htmladicional);
        $("#tb_pago_cuota").html(html);
        $("#ModalListaespecialidad").modal('hide');
}

ValidaDeuda = function(c,t,id){
    $("#txt_monto_certificado_ico"+id+",#txt_monto_cuota_ico"+id+",#i_monto_deuda_certificado_ico"+id).removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_certificado_ico"+id+",#txt_monto_cuota_ico"+id+",#i_monto_deuda_certificado_ico"+id).removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda_certificado"+id).text(saldo.toFixed(2));
}

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
            "<td>"+r.turno+"</td>"+
            "<td>"+r.dia+"</td>"+
            "<td class='aula' "+validasem+">"+r.aula+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[1]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[1]+"</td>"+
            "<td>"+
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion('+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='fecha_inicio' value='"+r.fecha_inicio+"'>"+
            "<input type='hidden' class='fecha_final' value='"+r.fecha_final+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
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

SeleccionarProgramacion=function(id){
    var mod='PRESENCIAL';
    var docente=$("#TableListaprogramacion #trid_"+id+" .docente").text();
    var persona_id=$("#TableListaprogramacion #trid_"+id+" .persona_id").val();
    var sucursal_id=$("#TableListaprogramacion #trid_"+id+" .sucursal_id").val();
    var sucursal=$("#TableListaprogramacion #trid_"+id+" .sucursal").text();
    var curso_id=$("#TableListaprogramacion #trid_"+id+" .curso_id").val();
    var dia=$("#TableListaprogramacion #trid_"+id+" .dia").val();
    var curso=$("#TableListaprogramacion #trid_"+id+" .curso").text();
    var fecha_inicio=$("#TableListaprogramacion #trid_"+id+" .fecha_inicio").val();
    var fecha_final=$("#TableListaprogramacion #trid_"+id+" .fecha_final").val();
    var fecha_i=fecha_inicio.split(" ");
    var fecha_f=fecha_final.split(" ");
    if(sucursal_id==1){
        var mod='ONLINE';
    }

    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(1)").val(id);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(2)").val(mod);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(3)").val(fecha_i[0]);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(4)").val(fecha_i[1]+"-"+fecha_f[1]);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(5)").val(dia);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(6)").val(sucursal);

    $("#ModalListaprogramacion").modal('hide');
}
</script>
