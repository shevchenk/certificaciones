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
        container : 'body',
        todayBtn: false
    });

    $('#spn_fecha_ini').on('click', function(){
        $('#txt_fecha_inicial').focus();
    });
    $('#spn_fecha_fin').on('click', function(){
        $('#txt_fecha_final').focus();
    });
    
    $("#TablePae").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var r=true;
        if ( fecha_inicial=="" || fecha_final=="") {
            r=false;
            swal("Mensaje", "Por favor seleccione todos los campos!")
        }
        return r;
    }
    
    $("#btn_generar").click(function (){
        var r = DataToFilter();
        if( r ){
            AjaxVisita.Cargar(HTMLCargarVisita);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        var r = DataToFilter();
        if( r ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportVisita'+'?fecha_ini='+$('#txt_fecha_ini').val()+'&fecha_fin='+$('#txt_fecha_fin').val());
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarVisita=function(result){
    var html="";
    $('#TableVisita').DataTable().destroy();
    //swal('tittle','hola');
    $.each(result.data,function(index,r){
        html+="<tr>"+
            "<td>"+(index+1)+"</td>"+
            "<td>"+$.trim(r.created_at)+"</td>"+
            "<td>"+$.trim(r.paterno)+"</td>"+
            "<td>"+$.trim(r.materno)+"</td>"+
            "<td>"+$.trim(r.nombre)+"</td>"+
            "<td>"+$.trim(r.celular)+"</td>"+
            "<td>"+$.trim(r.email)+"</td>"+

            "<td>"+$.trim(r.distrito)+"</td>"+
            "<td>"+$.trim(r.provincia)+"</td>"+
            "<td>"+$.trim(r.region)+"</td>"+
            "<td>"+$.trim(r.referencia)+"</td>"+

            "<td>"+$.trim(r.sucursal)+"</td>"+
            "<td>"+$.trim(r.medio_publicitario)+"</td>"+
            "<td>"+$.trim(r.carrera)+"</td>"+
            "<td>"+$.trim(r.frecuencia)+"</td>"+
            "<td>"+$.trim(r.hora_inicio)+" "+$.trim(r.hora_final)+"</td>"+

            "<td>"+$.trim(r.tipo_llamada)+"</td>"+
            "<td>"+$.trim(r.tipo_llamada_sub)+"</td>"+
            "<td>"+$.trim(r.tipo_llamada_sub_detalle)+"</td>"+
            "<td>"+$.trim(r.fechas)+"</td>"+
            "<td>"+$.trim(r.registro)+"</td>";
        html+="</tr>";
    });
    $("#TableVisita tbody").html(html); 
    $("#TableVisita").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
};

</script>
