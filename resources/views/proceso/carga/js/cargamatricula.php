<script type="text/javascript">
$(document).ready(function() {

    $("#log_fallas").hide();

    $('#btn_cargar').on('click', function(){
    	$(this).prop('disabled', true).html('<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Procesando..');
    	Cargar.Programacion();
    });

    $('#btn_cargar_m').on('click', function(){
        $(this).prop('disabled', true).html('<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Procesando..');
        Cargar.Matriculas();
    });
});

HTMLMsg=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
    }
    else if( result.rst==3 ){
        msjG.mensaje('warning',result.msj,4000);
        $('#resultado').html('<tr><td style="text-align: center; font-weight: bold; color: red;">'+result.no_pasa+' filas</td></tr>');
    }
    else if( result.rst==4 ){
        $("#log_fallas").show();

        msjG.mensaje('warning',result.msj,4000);
        
        var html_rl = '<tr><td style="text-align: center; font-weight: bold; color: red;"><pre>';
        //$.each(result.error_carga,function(index, data){
            html_rl += result.error_carga;
        //});
        html_rl += '</pre></td></tr>';

        $('#resultado_log').html(html_rl);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
