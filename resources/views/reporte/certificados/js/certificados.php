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

});

function DataToFilter(){
    var fecha_inicial = $('#txt_fecha_inicial').val();
    var fecha_final = $('#txt_fecha_final').val();
    var r=true;
    if ( fecha_inicial=="" || fecha_final=="") {
        r=false;
        swal("Mensaje", "Por favor seleccione la fecha inicial y la fecha final!")
    }
    return r;
}

function Exportar(){
    var r = DataToFilter();
    if( r ){
        $('#btnexportdetalle').attr('href','ReportDinamic/Reporte.SeminarioEM@ExportAlumnosInscritos'+'?fecha_ini='+$('#txt_fecha_inicial').val()+'&fecha_fin='+$('#txt_fecha_final').val());
    }else{
        event.preventDefault();
    }
}
</script>
