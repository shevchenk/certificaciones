<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var hoyG = '<?php echo date('Y-m-d'); ?>';
var PEG={id:0}; // Datos Globales
$(document).ready(function() {
    
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#TableProgramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $('#ModalProgramacion').css('z-index', 1050);
    
    AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    AjaxProgramacion.CargarTipoEvaluacion(SlctCargarTipoEvaluacion);
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalProgramacion').on('shown.bs.modal', function (event) {
        $('#tb_te').html('');
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalProgramacionForm").append("<input type='hidden' value='"+PEG.id+"' name='id'>");
            $("#ModalProgramacionForm #txt_docente").val( $("#trid_"+PEG.id+" .persona").text() );
            $("#ModalProgramacionForm #txt_sucursal").val( $("#trid_"+PEG.id+" .sucursal").text() );
            $("#ModalProgramacionForm #txt_curso").val( $("#trid_"+PEG.id+" .curso").text() );
            $("#ModalProgramacionForm #slct_trabajo_final").selectpicker( 'val', $("#trid_"+PEG.id+" .trabajo_final").val() );
            $("#ModalProgramacionForm #slct_peso_trabajo_final").selectpicker( 'val', $("#trid_"+PEG.id+" .peso_trabajo_final").val() );
            $("#ModalProgramacionForm #slct_activa_evaluacion").selectpicker( 'val', $("#trid_"+PEG.id+" .activa_evaluacion").val() );
            validaActivaEvaluacion($("#trid_"+PEG.id+" .activa_evaluacion").val());

            $("#ModalProgramacionForm #slct_peso_trabajo_final").removeAttr("disabled");
            if( $("#ModalProgramacionForm #slct_trabajo_final").val()==0 ){
                $("#ModalProgramacionForm #slct_peso_trabajo_final").attr("disabled","true");
            }
            AjaxProgramacion.CargarEvaluaciones(HTMLCargarEvaluaciones);
    });

    $('#ModalProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
    });
    
});

HTMLCargarEvaluaciones=function(result){
    $('#tb_te').html('');
    $.each(result.data,function(index,r){
        var html='';
            html='<tr class="trclass_'+r.id+'">'+
                '<td>'+(index+1)+'</td>'+
                '<td><input type="hidden" name="txt_tipo_evaluacion[]" value="'+r.id+'">'+r.tipo_evaluacion+'</td>'+
                '<td><select name="slct_peso_evaluacion[]">'+
                    '<option value="">.::Seleccione::.</option>';
                for (var i = 1; i < 6; i++) {
                    selected='';
                    if(i==r.peso_evaluacion){
                        selected='selected';
                    }
                    html+='<option value="'+i+'" '+selected+'>'+i+'</option>';
                }
                selectedno="selected";
                selectedsi="";
                disabled="style='display:none;'";
                if( r.activa_fecha==1 ){
                    selectedno="";
                    selectedsi="selected";
                    disabled="";
                }
            html+='</select></td>'+
                '<td><select name="slct_activa_fecha[]" onChange="activaFechas(this);"><option value="0" '+selectedno+'>No</option><option value="1" '+selectedsi+'>Si</option></select></td>'+
                '<td><input type="text" class="form-control fecha" value="'+r.fecha_evaluacion_ini+'" name="txt_fecha_inicio[]" placeholder="Fecha Inicio" '+disabled+'></td>'+
                '<td><input type="text" class="form-control fecha" value="'+r.fecha_evaluacion_fin+'" name="txt_fecha_final[]" placeholder="Fecha Final" '+disabled+'></td>'+
                '<td><a onClick="QuitarTipoEvaluacion('+r.id+');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td>'+
            '</tr>';
            $('#tb_te').append(html);
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
}

validaProyectoFinal=function(t){
    $("#ModalProgramacionForm #slct_peso_trabajo_final").removeAttr("disabled");
    $("#ModalProgramacionForm #slct_peso_trabajo_final").selectpicker("val",1);
    if( t.value==0 ){
        $("#ModalProgramacionForm #slct_peso_trabajo_final").selectpicker("val",0);
        $("#ModalProgramacionForm #slct_peso_trabajo_final").attr("disabled","true");
    }
}

CambiarEstado=function(estado,id){
    AjaxProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProgramacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProgramacion').modal('hide');
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

ValidarTipoEvaluacion=function(){
    if( $('#slct_tipo_evaluacion').val()!='' ){
        if( $.trim( $(".trclass_"+$('#slct_tipo_evaluacion').val()).html() )=='' ){
            var html='';
            html='<tr class="trclass_'+$('#slct_tipo_evaluacion').val()+'">'+
                '<td>'+($('#tb_te tr').length+1)+'</td>'+
                '<td><input type="hidden" name="txt_tipo_evaluacion[]" value="'+$('#slct_tipo_evaluacion').val()+'">'+$('#slct_tipo_evaluacion').find('option:selected').text()+'</td>'+
                '<td><select name="slct_peso_evaluacion[]">'+
                    '<option value="">.::Seleccione::.</option>'+
                    '<option value="1" selected>1</option>'+
                    '<option value="2" >2</option>'+
                    '<option value="3" >3</option>'+
                    '<option value="4" >4</option>'+
                    '<option value="5" >5</option>'+
                '</select></td>'+
                '<td><select name="slct_activa_fecha[]" onChange="activaFechas(this);"><option value="0" seleted>No</option><option value="1">Si</option></select></td>'+
                '<td><input type="text" class="form-control fecha" value="'+hoyG+'" name="txt_fecha_inicio[]" placeholder="Fecha Inicio" style="display:none;"></td>'+
                '<td><input type="text" class="form-control fecha" value="'+hoyG+'" name="txt_fecha_final[]" placeholder="Fecha Final" style="display:none;"></td>'+
                '<td><a onClick="QuitarTipoEvaluacion('+$('#slct_tipo_evaluacion').val()+');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td>'+
            '</tr>';
            $('#tb_te').append(html);
            $(".fecha").datetimepicker({
                format: "yyyy-mm-dd",
                language: 'es',
                showMeridian: true,
                time:true,
                minView:2,
                autoclose: true,
                todayBtn: false
            });
        }
        else{
            msjG.mensaje('warning','Tipo de evaluación ya existe!',3000);
        }
    }
    else{
        msjG.mensaje('warning','Seleccione un tipo de evaluación',3000);
    }
}

activaFechas=function(t){
    $(t).parent().parent().find('.fecha').css('display','block');
    if($(t).val()==0){
        $(t).parent().parent().find('.fecha').val(hoyG);
        $(t).parent().parent().find('.fecha').css('display','none');
    }
}

QuitarTipoEvaluacion=function(te){
    $(".trclass_"+te).remove();
    $('#tb_te tr').each(function(index,val){
        $(val).find('td:eq(0)').text( (index+1) );
    });
}

SlctCargarTipoEvaluacion=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_evaluacion+"</option>";
    });
    $("#ModalProgramacion #slct_tipo_evaluacion").html(html); 
    $("#ModalProgramacion #slct_tipo_evaluacion").selectpicker('refresh');
};

ValidaForm=function(){
    var r=true; var fi=''; var ff=''; var ex='';
    $('#tb_te tr').each(function(){
        ex=$(this).find("td:eq(1)").text();
        fi=$(this).find("input:eq(1)").val();
        ff=$(this).find("input:eq(2)").val();
        if( r==true && fi>ff ){
            r=false;
            msjG.mensaje('warning',ex+' => Fecha inicial('+fi+') no puede ser mayor a la fecha final('+ff+')',6000);
        }
    })
    return r;
}

ProgramarEvaluacion=function(id){
    PEG.id= id;
    $('#ModalProgramacion').modal('show');
}

HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableProgramacion').DataTable().destroy();
    
    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            //"<td class='aula'>"+r.aula+"</td>"+
            "<td class='dias'>"+r.dia+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+
            "<input type='hidden' class='peso_trabajo_final' value='"+r.peso_trabajo_final+"'>"+
            "<input type='hidden' class='trabajo_final' value='"+r.trabajo_final+"'>"+
            "<input type='hidden' class='activa_evaluacion' value='"+r.activa_evaluacion+"'>"+
            "</td>"+
            "<td><ul><li>"+$.trim(r.evaluacion).split("|").join("</li><li>")+"</li></ul></td>";
            //"<td class='fecha_final'>"+r.fecha_final+"</td>";
            //"<td class='fecha_campaña'>"+r.fecha_campaña+"</td>";
        html+='<td><a class="btn btn-primary btn-sm" onClick="ProgramarEvaluacion('+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableProgramacion tbody").html(html); 
    $("#TableProgramacion").DataTable({
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
            $('#TableProgramacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxProgramacion',result.data,'#TableProgramacion_paginate');
        }
    });

};

validaActivaEvaluacion=function(val){
    $(".validaactiva_evaluacion").css('display','none');
    if( val==1 ){
        $(".validaactiva_evaluacion").css('display','block');
    }
}
</script>
