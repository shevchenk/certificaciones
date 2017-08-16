<script type="text/javascript">
$(document).ready(function() {
    $('#btn_cargar').on('click', function(){
    	$(this).prop('disabled', true).html('<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Procesando..');
    	Cargar.Alumnos()
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
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
