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
    
    $("#btn_generar").click(function (){
            MPAjax.Cargar(MP.HTMLCargarReporte);
    });

    MPAjax.CargarEspecialidad(MP.HTMLCargarEspecialidad);
    MPAjax.CargarCurso(MP.HTMLCargarCurso);
});

var MP = {
    HTMLCargarEspecialidad : (result) => {
        var html='';
        $.each(result.data,function(index,r){
            html+='<option value="'+r.id+'">'+r.especialidad+'</option>';
        })
        $("#slct_especialidad").html(html);
        $("#slct_especialidad").selectpicker('refresh');
    },

    HTMLCargarCurso : (result) => {
        var html='';
        $.each(result.data,function(index,r){
            html+='<option value="'+r.id+'">'+r.curso+'</option>';
        })
        $("#slct_curso").html(html);
        $("#slct_curso").selectpicker('refresh');
    },


    HTMLCargarReporte : (result) => {
        var html="";
        $('#TableReporte').DataTable().destroy();
        $("#TableReporte tbody").html('');

        $.each(result.data,function(index,r){
            html =  "<tr id='trid_"+r.id+"'>"+
                        "<td>"+r.tipo_formacion+"</td>"+
                        "<td>"+r.formacion+"</td>"+
                        "<td>"+r.curso+"</td>"+
                        "<td>"+$.trim(r.local)+"</td>"+
                        "<td>"+$.trim(r.frecuencia)+"</td>"+
                        "<td>"+$.trim(r.horario)+"</td>"+
                        "<td>"+$.trim(r.turno)+"</td>"+
                        "<td>"+$.trim(r.inicio)+"</td>"+
                        
                        "<td>"+$.trim(r.deuda_total)+"</td>"+    
                        "<td>"+$.trim(r.deuda_total)+"</td>"+    
                    "</tr>";

            $("#TableReporte tbody").append(html);
        });

        $("#TableReporte").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    },

    GuardarArchivo : (index) => {
        nombre = $('#txt_pago_nombre'+index).val();
        archivo = $('#txt_pago_archivo'+index).val();
        matricula_detalle_id = $("#txt_matricula_detalle_id"+index).val();
        data = {matricula_detalle_id: matricula_detalle_id, archivo: archivo, nombre: nombre};
        MPAjax.GuardarArchivo(MP.HTMLGuardarArchivo, data);
    },

    HTMLGuardarArchivo : (result) => {
        if( result.rst==1 ){
            msjG.mensaje('success',result.msj,4000);
            $("#btn_generar").click();
        }
        else if( result.rst==2 ){
            msjG.mensaje('warning',result.msj,4000);
        }
    }
}


</script>
