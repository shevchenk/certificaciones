<script type="text/javascript">
$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:3,
        startView:3,
        autoclose: true,
        todayBtn: false
    });
    
    $("#TableNotas").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    function DataToFilter(){
        var fecha_ini_mat = $('#txt_fecha_ini_mat').val();
        var fecha_fin_mat = $('#txt_fecha_fin_mat').val();
        var fecha_inicio = $('#txt_fecha_inicio').val();
        var fecha_final = $('#txt_fecha_final').val();
        var data = [];
        if ( fecha_inicio!=="" && fecha_final!=="") {
            data.push({fecha_ini_mat:fecha_ini_mat,fecha_fin_mat:fecha_fin_mat});
        }else if ( fecha_inicio!=="" && fecha_final!=="") {
            data.push({fecha_inicio:fecha_inicio,fecha_final:fecha_final});
        } else {
            alert("Seleccione Fechas");
        }
        return data;
    }
    
    $("#btn_generar").click(function (){
        var data = DataToFilter();            
        if(data.length > 0){
            AjaxNotas.Cargar(data[0],HTMLCargarNotas);         
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            //$(this).attr('href','AjaxDinamic/Reporte.NotasEM@getLoadNOTASExport'+'?fecha_inicio='+data[0]['fecha_inicio']+'&fecha_final='+data[0]['fecha_inicial']);            
            $(this).attr('href','ReportDinamic/Reporte.NotasEM@ExportNotas'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarNotas=function(result){
    var html="";
    $('#TableNotas').DataTable().destroy();

    $.each(result.data,function(index,r){
        //var curso=r.cursos.split(",");
        //var nro=r.nro_pago_certificado.split(",");
        //var monto=r.monto_pago_certificado.split(",");

        html+="<tr id='trid_"+r.paterno+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.telefono+"</td>"+
            "<td>"+r.email+"</td>"+

            "<td>"+r.sucursal+"</td>"+
            "<td>"+r.fecha_inicio+"</td>"+
            "<td>"+r.fecha_final+"</td>"+
            "<td>"+r.docente+"</td>"+
            "<td>"+r.modalidad+"</td>"+
            "<td>"+r.curso+"</td>"+

            "<td>"+$.trim(r.nota_curso_alum)+"</td>"+

            "<td>"+r.nro_pago_certificado+"</td>"+
            "<td>"+r.monto_pago_certificado+"</td>"+

            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>";
        html+="</tr>";
    });
    $("#TableNotas tbody").html(html); 
    $("#TableNotas").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

</script>
