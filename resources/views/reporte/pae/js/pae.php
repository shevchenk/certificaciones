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
    
    $("#TablePae").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_inicial').val();
        var fecha_final = $('#txt_fecha_final').val();
        var data = [];
        if ( fecha_inicial!=="" && fecha_final!=="") {
            data.push({fecha_inicial:fecha_inicial,fecha_final:fecha_final});
           
        } else {
            alert("Seleccione Fechas");
        }
        return data;
    }
    
    $("#btn_generar").click(function (){
        var data = DataToFilter();            
        if(data.length > 0){
            AjaxPae.Cargar(data[0],HTMLCargarPAE);         
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','AjaxDinamic/Reporte.ReporteEM@getLoadPAEExport'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_inicial']);            
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarPAE=function(result){
    var html="";
    $('#TablePae').DataTable().destroy();

    $.each(result.data,function(index,r){
        var curso=r.cursos.split(",");
        var nro=r.nro_pago_certificado.split(",");
        var monto=r.monto_pago_certificado.split(",");
        html+="<tr id='trid_"+r.paterno+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.telefono+"</td>"+
            "<td>"+r.email+"</td>"+
            "<td>"+r.direccion+"</td>"+
            "<td>"+r.fecha_matricula+"</td>"+
            "<td>"+r.sucursal+"</td>"+
            "<td>"+r.tipo_participante+"</td>"+
            "<td>"+r.nro_pago_inscripcion+"</td>"+
            "<td>"+r.monto_pago_inscripcion+"</td>"+
            "<td>"+r.nro_pago+"</td>"+
            "<td>"+r.monto_pago+"</td>"+
            "<td>"+curso[0]+"</td>"+
            "<td>"+nro[0]+"</td>"+
            "<td>"+monto[0]+"</td>"+
            "<td>"+r.matricula+"</td>";
        html+="</tr>";
    });
    $("#TablePae tbody").html(html); 
    $("#TablePae").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

</script>
