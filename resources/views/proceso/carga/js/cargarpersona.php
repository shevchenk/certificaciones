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
        $('#resultado').html('<tr><td style="text-align: center; font-weight: bold; color: red;">'+result.data.length+' filas</td></tr>');
        if( result.data.length>0 ){
            $.each(result.data,function(index,r){
                html='<tr>';
                html+='<td>'+r.pos+'</td>';
                html+='<td>'+r.DNI+'</td>';
                html+='<td>'+r.EMAIL+'</td>';
                html+='<td>'+r.COD_VENDEDOR+'</td>';
                html+='<td>'+r.FECHA_REGISTRO+'</td>';
                html+='<td>'+r.FECHA_ENTREGA+'</td>';
                html+='</tr>';
                $('#resultado2').append(html);
            });
        }
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
