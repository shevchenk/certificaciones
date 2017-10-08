<script type="text/javascript">
$(document).ready(function() {

    function DataToFilter(){
        var formato = $('#slct_formato_carga').val();
        var data = [];
        if ( formato != '0') {
            data.push({formato:formato});
        } else {
            swal("Mensaje", "Por favor seleccione un tipo formato!")
        }
        return data;
    }

    $(document).on('click', '#btnexport', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.FormatoCargaAlumEM@Export'+'?formato='+data[0]['formato']);
        }else{
            event.preventDefault();
        }
    });

});
</script>
