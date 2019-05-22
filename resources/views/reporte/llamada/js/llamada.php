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

    $(document).on('click', '#btnexport', function(event) {
        var r = DataToFilter();
        if( r ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportLlamadas'+'?fecha_ini='+$('#txt_fecha_ini').val()+'&fecha_fin='+$('#txt_fecha_fin').val());
        }else{
            event.preventDefault();
        }
    });

});

</script>
