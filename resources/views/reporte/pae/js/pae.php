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
    
    $("#TablePae").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var data = [];
        if ( fecha_inicial!=="" && fecha_final!=="") {
            data.push({fecha_ini:fecha_inicial,fecha_fin:fecha_final});
           
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
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportPAE'+'?fecha_ini='+data[0]['fecha_ini']+'&fecha_fin='+data[0]['fecha_fin']);
        }else{
            event.preventDefault();
        }
    });

    $(document).on('click', '#btnexport2', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportPAEDes'+'?fecha_ini='+data[0]['fecha_ini']+'&fecha_fin='+data[0]['fecha_fin']);
        }else{
            event.preventDefault();
        }
    });

    $(document).on('click', '#btnexport3', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportPAECab'+'?fecha_ini='+data[0]['fecha_ini']+'&fecha_fin='+data[0]['fecha_fin']);
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarPAE=function(result){
    var html="";
    $('#TablePae').DataTable().destroy();

    $.each(result.data,function(index,r){
        //var curso=r.cursos.split(",");
        //var nro=r.nro_pago_certificado.split(",");
        //var monto=r.monto_pago_certificado.split(",");
        var curso=r.cursos.split("\n").join('<br/>');
        var nro=r.nro_pago_certificado.split("\n").join('<br/>');
        var monto=r.monto_pago_certificado.split("\n").join('<br/>');
        var nro_c=r.nro_pago_c.split("\n").join('<br/>');
        var monto_c=r.monto_pago_c.split("\n").join('<br/>');
        var modalidad=r.modalidad.split("\n").join('<br/>');

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

            "<td>"+modalidad+"</td>"+
            "<td>"+curso+"</td>"+
            "<td>"+nro_c+"</td>"+
            "<td>"+monto_c+"</td>"+
            "<td>"+nro+"</td>"+
            "<td>"+monto+"</td>"+
            /*
            "<td>"+curso[1]+"</td>"+
            "<td>"+nro[1]+"</td>"+
            "<td>"+monto[1]+"</td>"+
            "<td>"+curso[2]+"</td>"+
            "<td>"+nro[2]+"</td>"+
            "<td>"+monto[2]+"</td>"+
            */
            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>"+

            "<td>"+$.trim(r.subtotal)+"</td>"+
            "<td>"+$.trim(r.total)+"</td>"+

            "<td>"+r.marketing+"</td>"+
            "<td>"+r.cajera+"</td>"+
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
