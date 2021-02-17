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

    Reporte.CargarEspecialidad(HTMLCargarEspecialidad);
    Reporte.CargarCurso(HTMLCargarCurso);

});

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
    var html="";
    $('#TableReporte').DataTable().destroy();
    $("#TableReporte tbody").html('');

    $.each(result.data,function(index,r){
        var pagos= r.pagos.split('\n').join("</li><li>");
        var programacion_cuota= r.programacion_cuota.split('\n').join("</li><li>");
        var pagos_cuota= r.pagos_cuota.split('\n').join("</li><li>");
        var pagos2_cuota= r.pagos2_cuota.split('\n').join("</li><li>");
        var deuda_cuota= r.deuda_cuota.split('\n').join("</li><li>");
        html = "<tr id='trid_"+r.id+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.celular+"</td>"+
            "<td>"+r.email+"</td>"+

            "<td>"+r.empresa_inscripcion+"</td>"+
            "<td>"+r.fecha_matricula+"</td>"+
            "<td>"+r.tipo_formacion+"</td>"+
            "<td>"+r.formacion+"</td>"+
            "<td>"+r.curso+"</td>"+
            "<td>"+$.trim(r.local)+"</td>"+
            "<td>"+$.trim(r.frecuencia)+"</td>"+
            "<td>"+$.trim(r.horario)+"</td>"+
            "<td>"+$.trim(r.turno)+"</td>"+
            "<td>"+$.trim(r.inicio)+"</td>"+
            
            "<td><ul><li>"+$.trim(r.deuda_total)+"</li></ul></td>"+
            "<td>"+
                '<div class="col-md-12">'+
                    '<label>Archivo del certificado:</label>'+
                    '<input type="text"  readOnly class="form-control input-sm" id="txt_pago_nombre'+index+'"  name="txt_pago_nombre[]" value="'+$.trim(r.archivo_certificado)+'">'+
                    '<input type="text" style="display: none;" id="txt_pago_archivo'+index+'" name="txt_pago_archivo[]">'+
                    '<input type="text" style="display: none;" id="txt_matricula_detalle_id'+index+'" value="'+r.matricula_detalle_id+'">'+
                    '<label class="btn btn-default btn-flat margin btn-xs">'+
                        '<i class="fa fa-file-image-o fa-3x"></i>'+
                        '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                        '<i class="fa fa-file-word-o fa-3x"></i>'+
                        '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#txt_pago_nombre'+index+'\',\'#txt_pago_archivo'+index+'\',\'#pago_img'+index+'\');" >'+
                    '</label>'+
                    '<div>'+
                        '<a id="pago_href'+index+'">'+
                        '<img id="pago_img'+index+'" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                        '</a>'+
                    '</div>'+
                '</div>'+
                '<hr>'+
                '<div class="col-md-12">'+
                    '<input type="button" class="btn btn-primary" onClick="GuardarArchivo('+index+');" value="Guardar Archivo">'+
                    '<input type="button" class="btn btn-danger" onClick="EliminarArchivo('+index+');" value="Eliminar Archivo">'+
                '</div>'+
            "</td>"+
            "</tr>";

        $("#TableReporte tbody").append(html);
        if( $.trim(r.archivo_certificado) != '' ){
            masterG.SelectImagen(r.archivo_certificado, "#pago_img"+index, "#pago_href"+index);
        }
    });

    $("#TableReporte").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

GuardarArchivo = (index)=> {
    nombre = $('#txt_pago_nombre'+index).val();
    archivo = $('#txt_pago_archivo'+index).val();
    matricula_detalle_id = $("#txt_matricula_detalle_id"+index).val();
    data = {matricula_detalle_id: matricula_detalle_id, archivo: archivo, nombre: nombre};
    Reporte.GuardarArchivo(HTMLGuardarArchivo, data);
}

EliminarArchivo = (index)=> {
    matricula_detalle_id = $("#txt_matricula_detalle_id"+index).val();
    data = {matricula_detalle_id: matricula_detalle_id};
    Reporte.EliminarArchivo(HTMLGuardarArchivo, data);
}

HTMLGuardarArchivo = (result)=> {
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $("#btn_generar").click();
    }
    else if( result.rst==2 ){
        msjG.mensaje('warning',result.msj,4000);
    }
}

</script>
