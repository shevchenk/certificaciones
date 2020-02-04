<script type="text/javascript">
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var PersonaIdG=0;

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
    $("#TableDatos").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxEspecialidad.Cargar(HTMLCargar);
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
});

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data,function(index,r){ 
        html+="<tr id='trid_"+r.matricula_detalle_id+"'>";
   
        html+=  "<td class='dni'>"+r.dni+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='curso'>"+r.curso+"</td>"+
                "<td class='saldo'>"+r.saldo+"</td>";
        if(r.curso==r.dni){
            html+=  '<td><a id="btn_c'+r.matricula_detalle_id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verSaldos(HTMLCargaCurso, '+r.matricula_detalle_id+', '+r.matricula_id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
        }
        else{
            html+=  '<td><a id="btn_'+r.matricula_detalle_id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verCursos(HTMLCargaCurso, '+r.matricula_detalle_id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
        }
        html+="</tr>";
    });

    $("#TableDatos tbody").html(html); 
    $("#TableDatos").DataTable({ //INICIO DATATABLE
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
            $('#TableDatos_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargar','AjaxEspecialidad',result.data,'#TableDatos_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

// PROCESOS NUEVOS
HTMLCargaCurso=function(result){ //INICIO HTML
    var html="";
    $.each(result.data,function(index,r){
        if( index==0 ){
        html+="<tr>"+
        "<td>&nbsp;</td>"+
        "<td>&nbsp;</td>";
        html+="<td><input type='text' class='form-control'  id='txt_nro_pago' name='txt_nro_pago'></td>";
        html+="<td>"+
            "<div class='input-group'>"+
                "<div class='input-group-addon'>"+
                "<i>"+r.saldo+"</i>"+
                "</div>"+
                '<div id="txt_monto_pago_ico" class="has-warning has-feedback">'+
                    "<input type='text' class='form-control'  id='txt_monto_pago' name='txt_monto_pago'"+
                    " onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(\""+r.saldo+"\",this);'>"+
                    '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                '</div>'+
            "</div>"+
            '<div id="i_monto_deuda_ico" class="has-warning has-feedback">'+
                "<div class='input-group-addon'>"+
                "<label>Deuda:</label>"+
                "<label id='i_monto_deuda'>"+r.saldo+"</label>"+
                '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>'+
                "</div>"+
            '</div>'+
        "</td>";
        html+="<td><select class='form-control'  id='slct_tipo_pago' name='slct_tipo_pago'>"+
            "<option value='0'>.::Seleccione::.</option>"+
            "<option value='1.1'>Transferencia - BCP</option>"+
            "<option value='1.2'>Transferencia - Scotiabank</option>"+
            "<option value='1.3'>Transferencia - BBVA</option>"+
            "<option value='1.4'>Transferencia - Interbank</option>"+
            "<option value='2.1'>Depósito - BCP</option>"+
            "<option value='2.2'>Depósito - Scotiabank</option>"+
            "<option value='2.3'>Depósito - BBVA</option>"+
            "<option value='2.4'>Depósito - Interbank</option>"+
            "<option value='3.0'>Caja</option>"+
            "</select>"+
        "</td>";
        html+="<td>"+
                '<input type="text"  readOnly class="form-control input-sm" id="txt_pago_nombre"  name="txt_pago_nombre" value="">'+
                '<input type="text" style="display: none;" id="txt_pago_archivo" name="txt_pago_archivo">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#txt_pago_nombre\',\'#txt_pago_archivo\',\'#pago_img\');" >'+
                '</label>'+
                '<div>'+
                '<a id="pago_href">'+
                '<img id="pago_img" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a>'+
                '</div><input type="button" class="btn btn-primary" id="btnPagoCuota" onClick="GuardarPago('+r.id+');" value="GuardarPago">'
        "</td>";
        html+="</tr>";
        }

        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.precio+"</td>";
        html+="<td>"+r.pago;
        if( $.trim(r.archivo)!=''){
            html+='<img src="'+r.archivo+'" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">';
        }
        html+="</td>";
        html+="<td>"+r.nro_pago+"</td>";
        html+="<td>"+r.saldo+"</td>";
        html+="<td>"+r.tipo_pago+"</td>"+
        "<td>&nbsp;</td>";
        html+="</tr>";
    });

    $("#tb_matricula").html(html); 
};

ValidaDeuda = function(c,t){
    $("#txt_monto_pago_ico,#i_monto_deuda_ico").removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_pago_ico,#i_monto_deuda_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda").text(saldo.toFixed(2));
}

btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
}

GuardarPago=function(id){
    $("#form_saldos input[type='hidden']").remove();
    if( ValidaForm() ){
        $("#form_saldos").append("<input type='hidden' name='id' value='"+id+"'>");
        AjaxEspecialidad.GuardarPago(HTMLGuardarPago);
    }
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#txt_nro_pago").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese el nro de pago',4000);
    }
    else if( $.trim( $("#txt_monto_pago").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese el monto de pago',4000);
    }
    else if( $.trim( $("#slct_tipo_pago").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione el tipo de operación',4000);
    }
    else if( $.trim( $("#txt_pago_archivo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese archivo del pago',4000);
    }
    return r;
}

HTMLGuardarPago=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $("#div_saldo").html(result.saldo);
        AjaxEspecialidad.Cargar(HTMLCargar);
        if( $.trim(result.matricula_detalle_id)!='' ){
            AjaxEspecialidad.verCursos(HTMLCargaCurso, result.matricula_detalle_id);
        }
        else{
            AjaxEspecialidad.verSaldos(HTMLCargaCurso, result.cuota, result.matricula_id);
        }
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
