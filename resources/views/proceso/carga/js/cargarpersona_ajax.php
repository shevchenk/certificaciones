<script type="text/javascript">
var Cargar={
    Alumnos:function(evento){
        var data = new FormData($("#form_file")[0]);
        $.ajax({
            url     : 'AjaxDinamic/Proceso.CargarPR@CargarInteresados',
            type    : 'POST',
            data    : data,
            async   : true,
            success : function (obj) { HTMLMsg(obj); $('#btn_cargar').prop('disabled', false).html('<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar'); },
            error   : function(jqXHR, textStatus, error){ alert(jqXHR.responseText); },
            cache   : false,
            contentType: false,
            processData: false
        });
    }
};
</script>
