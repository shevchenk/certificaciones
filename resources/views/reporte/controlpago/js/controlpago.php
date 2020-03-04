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

    $(document).on('click', '#btnexport', function(event) {
        if( DataToFilter() ){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportControlPago'+'?paterno='+$("#txt_paterno").val()+'&materno='+$("#txt_materno").val()+'&nombre='+$("#txt_nombre").val()+'&especialidad2='+$.trim($("#slct_especialidad").val())+'&curso2='+$.trim($("#slct_curso").val())+'&dni='+$.trim($("#txt_dni").val()));
        }else{
            event.preventDefault();
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

    $.each(result.data,function(index,r){
        var pagos= r.pagos.split('\n').join("</li><li>");
        var programacion_cuota= r.programacion_cuota.split('\n').join("</li><li>");
        var pagos_cuota= r.pagos_cuota.split('\n').join("</li><li>");
        var pagos2_cuota= r.pagos2_cuota.split('\n').join("</li><li>");
        var deuda_cuota= r.deuda_cuota.split('\n').join("</li><li>");
        html+="<tr id='trid_"+r.id+"'>"+
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
            
            "<td>"+$.trim(r.nro_pago)+"</td>"+
            "<td>"+$.trim(r.monto_pago)+"</td>"+
            "<td>"+$.trim(r.tipo_pago)+"</td>"+
            
            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_promocion)+"</td>"+

            "<td>"+$.trim(r.nro_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.monto_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_inscripcion)+"</td>"+

            "<td><ul><li>"+$.trim(pagos)+"</li></ul></td>"+

            "<td>"+$.trim(r.deuda)+"</td>"+
            "<td>"+$.trim(r.nota)+"</td>"+
            
            "<td><ul><li>"+$.trim(programacion_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(pagos2_cuota)+"</li></ul></td>"+
            
            "<td><ul><li>"+$.trim(pagos_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(pagos_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.deuda_total)+"</li></ul></td>";

        html+="</tr>";
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

</script>
