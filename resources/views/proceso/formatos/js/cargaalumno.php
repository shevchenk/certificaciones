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

    function DataToFilter(){
        var formato = $('#slct_formato_carga').val();
        var fecha_inicial = $('#txt_fecha_inicial').val();
        var fecha_final = $('#txt_fecha_final').val();
        var data = [];
        if ( fecha_inicial!=="" && fecha_final!=="" && formato != '0') {
            data.push({formato:formato, fecha_inicial:fecha_inicial, fecha_final:fecha_final});

        } else {
            swal("Mensaje", "Por favor seleccione todos los campos!")
        }
        return data;
    }

    $(document).on('click', '#btnexport', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.FormatoCargaAlumEM@Export'+'?formato='+data[0]['formato']+'&fecha_inicial_dia='+data[0]['fecha_inicial']+'&fecha_final_dia='+data[0]['fecha_final']);
        }else{
            event.preventDefault();
        }
    });

});
</script>
