<script type="text/javascript">
$(document).ready(function() {

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        startView:2,
        autoclose: true,
        todayBtn: false
    });

    $('#spn_fecha_ini').on('click', function(){
        $('#txt_fecha_inicial').focus();
    });
    $('#spn_fecha_fin').on('click', function(){
        $('#txt_fecha_final').focus();
    });
    $("#spn_fecha_pago").click(()=>{ $("#txt_fecha_pago").focus(); });

    
    $("#TableReporte").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_inicial').val();
        var fecha_final = $('#txt_fecha_final').val();
        data=true;
        
        return data;
    }
    
    $("#btn_generar").click(function (){
        
        if( DataToFilter() ){
            Reporte.Cargar(HTMLCargarReporte);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        if( DataToFilter() ){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportControlPago'+'?paterno='+$("#txt_paterno").val()+'&materno='+$("#txt_materno").val()+'&nombre='+$("#txt_nombre").val()+'&especialidad2='+$.trim($("#slct_especialidad").val())+'&curso2='+$.trim($("#slct_curso").val())+'&dni='+$.trim($("#txt_dni").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarEspecialidad(HTMLCargarEspecialidad);
    Reporte.CargarCurso(HTMLCargarCurso);
    Reporte.CargarBanco(SlctCargarBanco);

});

SlctCargarBanco=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.banco+"</option>";
    });
    $("#PagoForm #slct_tipo_pago").html(html);
    $("#PagoForm #slct_tipo_pago").selectpicker('refresh');
}

HTMLCargarEspecialidad=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.especialidad+'</option>';
    })
    $("#slct_especialidad").html(html);
    $("#slct_especialidad").selectpicker('refresh');
}

HTMLCargarCurso=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.curso+'</option>';
    })
    $("#slct_curso").html(html);
    $("#slct_curso").selectpicker('refresh');
}


HTMLCargarReporte=function(result){
    var html=""; var auxid='';
    $('#TableReporte').DataTable().destroy();

    $.each(result.data,function(index,r){
        var ds = '';
        var ddc = '';
        var importe = 0;
        var detalle ='';
        if( $.trim(r.saldo)!='' ){
            ds = 'Curso';
            detalle ='Curso';
            importe = r.saldo;
        }

        if( $.trim(r.salcd)!='' ){
            ds = r.cuotacd;
            detalle = r.cuotacd;
            importe = r.salcd;
        }
        else if( $.trim(r.cuota_cronograma)!='' ){
            ddc = r.cuota_cronograma+' / FV:'+r.fecha_cronograma;
            detalle = r.cuota_cronograma;
            importe = r.monto_cronograma;
        }

        if( r.matricula_id!=auxid ){
            auxid = r.matricula_id;
            if( $.trim(r.salsi)!='' ){
                html+="<tr id='trid_"+index+"_0'>"+
                    "<td>"+r.dni+"</td>"+
                    "<td>"+r.nombre+"</td>"+
                    "<td>"+r.paterno+"</td>"+
                    "<td>"+r.materno+"</td>"+

                    "<td>"+r.empresa_inscripcion+"</td>"+
                    "<td>"+r.fecha_matricula+"</td>"+
                    "<td>"+r.tipo_formacion+"</td>"+
                    "<td>"+r.formacion+"</td>"+
                    "<td>"+r.curso+"</td>"+
                    "<td>"+$.trim(r.local)+"</td>"+
                    "<td>"+$.trim(r.frecuencia)+"</td>"+
                    "<td>"+$.trim(r.turno)+"</td>"+
                    "<td>"+$.trim(r.inicio)+"</td>"+
                    
                    "<td>"+$.trim('Inscripción')+"</td>"+
                    "<td>"+$.trim('')+"</td>"+
                    "<td>"+$.trim(r.salsi)+"</td>"+
                    "<td>"+
                        "<input type='hidden' class='mmd' value='"+$.trim(r.matricula_detalle_id)+"'>"+
                        "<input type='hidden' class='mm' value='"+$.trim(r.matricula_id)+"'>"+
                        "<input type='hidden' class='c' value='-1'>"+
                        "<input type='hidden' class='saldo' value='"+$.trim(r.salsi)+"'>"+
                        "<input type='hidden' class='detalle' value='Inscripción'>"+
                        "<a class='btn btn-flat btn-success' onClick='ValidarPago(\""+index+"_0\");'><i class='fa fa-money fa-lg'></i></a></td>";
                html+="</tr>";
            }

            if( $.trim(r.salsm)!='' ){
                html+="<tr id='trid_"+index+"_0m'>"+
                    "<td>"+r.dni+"</td>"+
                    "<td>"+r.nombre+"</td>"+
                    "<td>"+r.paterno+"</td>"+
                    "<td>"+r.materno+"</td>"+

                    "<td>"+r.empresa_inscripcion+"</td>"+
                    "<td>"+r.fecha_matricula+"</td>"+
                    "<td>"+r.tipo_formacion+"</td>"+
                    "<td>"+r.formacion+"</td>"+
                    "<td>"+r.curso+"</td>"+
                    "<td>"+$.trim(r.local)+"</td>"+
                    "<td>"+$.trim(r.frecuencia)+"</td>"+
                    "<td>"+$.trim(r.turno)+"</td>"+
                    "<td>"+$.trim(r.inicio)+"</td>"+
                    
                    "<td>"+$.trim('Matrícula')+"</td>"+
                    "<td>"+$.trim('')+"</td>"+
                    "<td>"+$.trim(r.salsm)+"</td>"+
                    "<td>"+
                        "<input type='hidden' class='mmd' value='"+$.trim(r.matricula_detalle_id)+"'>"+
                        "<input type='hidden' class='mm' value='"+$.trim(r.matricula_id)+"'>"+
                        "<input type='hidden' class='c' value='0'>"+
                        "<input type='hidden' class='saldo' value='"+$.trim(r.salsm)+"'>"+
                        "<input type='hidden' class='detalle' value='Matrícula'>"+
                        "<a class='btn btn-flat btn-success' onClick='ValidarPago(\""+index+"_0m\");'><i class='fa fa-money fa-lg'></i></a></td>";
                html+="</tr>";
            }

        }

        if( importe*1>0 ){
            html+="<tr id='trid_"+index+"'>"+
                "<td>"+r.dni+"</td>"+
                "<td>"+r.nombre+"</td>"+
                "<td>"+r.paterno+"</td>"+
                "<td>"+r.materno+"</td>"+

                "<td>"+r.empresa_inscripcion+"</td>"+
                "<td>"+r.fecha_matricula+"</td>"+
                "<td>"+r.tipo_formacion+"</td>"+
                "<td>"+r.formacion+"</td>"+
                "<td>"+r.curso+"</td>"+
                "<td>"+$.trim(r.local)+"</td>"+
                "<td>"+$.trim(r.frecuencia)+"</td>"+
                "<td>"+$.trim(r.turno)+"</td>"+
                "<td>"+$.trim(r.inicio)+"</td>"+
                
                "<td>"+$.trim(ds)+"</td>"+
                "<td>"+$.trim(ddc)+"</td>"+
                "<td>"+$.trim(importe)+"</td>"+
                "<td>"+
                    "<input type='hidden' class='mmd' value='"+$.trim(r.matricula_detalle_id)+"'>"+
                    "<input type='hidden' class='mm' value='"+$.trim(r.matricula_id)+"'>"+
                    "<input type='hidden' class='c' value='"+$.trim(r.cuota)+"'>"+
                    "<input type='hidden' class='saldo' value='"+importe+"'>"+
                    "<input type='hidden' class='detalle' value='"+detalle+"'>"+
                    "<a class='btn btn-flat btn-success' onClick='ValidarPago("+index+");'><i class='fa fa-money fa-lg'></i></a></td>";
            html+="</tr>";
        }
    });
    $("#TableReporte tbody").html(html); 
    $("#TableReporte").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

ValidarPago=function(v){
    console.log(v);
    var dni = $('#trid_'+v).find('td:eq(0)').text();
    var paterno = $('#trid_'+v).find('td:eq(1)').text();
    var materno = $('#trid_'+v).find('td:eq(2)').text();
    var nombre = $('#trid_'+v).find('td:eq(3)').text();
    var modulo = $('#trid_'+v).find('td:eq(7)').text();
    var fc = $('#trid_'+v).find('td:eq(8)').text();
    var local = $('#trid_'+v).find('td:eq(9)').text();

    var mmd=$('#trid_'+v).find('.mmd').val();
    var mm=$('#trid_'+v).find('.mm').val();
    var c=$('#trid_'+v).find('.c').val();
    var saldo=$('#trid_'+v).find('.saldo').val();
    var detalle=$('#trid_'+v).find('.detalle').val();

    var alumno = 'DNI:'+dni+' / '+paterno +' '+ materno +', '+ nombre;
    var curso = modulo + ' / '+fc+' / '+local;
    $('#ModalPago #txt_alumno').val(alumno);
    $('#ModalPago #txt_detalle').val(curso);
    $('#ModalPago #txt_nro_pago').val('');
    $('#ModalPago #txt_monto_pago').val('');
    $('#ModalPago #slct_tipo_pago').val('0');
    $('#ModalPago #txt_pago_archivo').val('');
    $('#ModalPago #txt_pago_nombre').val('');
    $('#ModalPago #txt_persona_caja_id').val('');
    $('#ModalPago #txt_persona_caja').val('');
    $('#ModalPago .modal-title').text('Pagos de '+detalle);
    masterG.SelectImagen('','#pago_img','');

    if( c!='' ){
        mmd='';
    }

    $('#ModalPago #txt_matricula_id').val(mm);
    $('#ModalPago #txt_matricula_detalle_id').val(mmd);
    $('#ModalPago #txt_cuota').val(c);


    $('#ModalPago #i_monto_saldo,#ModalPago #i_monto_deuda').text(saldo);
    $('#ModalPago #txt_monto_pago').attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda(\''+saldo+'\',this);');
    $("#txt_monto_pago_ico,#i_monto_deuda_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    Reporte.VerSaldos(HTMLVerSaldos,mmd, mm, c);

    $('#ModalPago').modal('show');
}

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

HTMLVerSaldos=function(result){
    var html="";
    $("#tb_pago").html('');
    $.each(result.data,function(index,r){
        html="<tr>";
        html+="<td>"+r.precio+"</td>";
        html+="<td>"+r.pago+"</td>";
        html+="<td>"+r.nro_pago+"</td>";
        html+="<td>"+r.saldo+"</td>";
        html+="<td>"+r.tipo_pago+"</td>";
        html+="<td>"+r.fecha_pago+"</td>";
        html+="<td>";
        if( $.trim(r.archivo)!=''){
            html+='<a class="btn btn-flat btn-info btn-lg" href="'+r.archivo+'" target="blank"><i class="fa fa-download fa-lg"></i></a>';
            //html+='<img src="'++'" class="img-circle" style="height: 100px;width: 180px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">';
        }
        html+="</td>";
        html+="</tr>";
        $("#tb_pago").append(html);
    });

}

GuardarPago=function(){
    if( $.trim($('#ModalPago #txt_nro_pago').val())=='' ){
        msjG.mensaje('warning','Ingrese el nro de pago',4000);
    }
    else if( $.trim($('#ModalPago #txt_monto_pago').val())=='' ){
        msjG.mensaje('warning','Ingrese el monto de pago',4000);
    }
    else if( $.trim($('#ModalPago #slct_tipo_pago').val())=='0' ){
        msjG.mensaje('warning','Seleccione el tipo de operación',4000);
    }
    else if( $.trim($('#ModalPago #txt_fecha_pago').val())=='' ){
        msjG.mensaje('warning','Seleccione fecha de pago',4000);
    }
    /*else if( $.trim($('#ModalPago #txt_pago_archivo').val())=='' ){
        msjG.mensaje('warning','Ingrese un archivo de pago',4000);
    }*/
    else if( $.trim($('#ModalPago #txt_persona_caja_id').val())=='' ){
        msjG.mensaje('warning','Busque y seleccione un responsable de caja',4000);
    }
    else{
        Reporte.GuardarPago(HTMLGuardarPago);
    }
}

HTMLGuardarPago=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        if(result.saldo*1>0){
            $('#ModalPago #txt_nro_pago').val('');
            $('#ModalPago #txt_monto_pago').val('');
            $('#ModalPago #slct_tipo_pago').val('0');
            $('#ModalPago #txt_pago_archivo').val('');
            $('#ModalPago #txt_pago_nombre').val('');
            $('#ModalPago #txt_persona_caja_id').val('');
            $('#ModalPago #txt_persona_caja').val('');
            masterG.SelectImagen('','#pago_img','');
            $('#ModalPago #i_monto_saldo,#ModalPago #i_monto_deuda').text(result.saldo);
            $('#ModalPago #txt_monto_pago').attr('onkeyup','masterG.DecimalMax(this, 2);ValidaDeuda(\''+result.saldo+'\',this);');
            $("#txt_monto_pago_ico,#i_monto_deuda_ico").removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
            Reporte.VerSaldos(HTMLVerSaldos,result.matricula_detalle_id, result.matricula_id, result.cuota);
        }
        else{
            Reporte.Cargar(HTMLCargarReporte);
            $('#ModalPago').modal('hide');
        }
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
