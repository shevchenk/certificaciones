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
            Reporte.Cargar(data[0],HTMLCargarReporte);         
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportSeminario'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
            //$(this).attr('href','reporte/seminario'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
        }else{
            event.preventDefault();
        }
    });

    $(document).on('click', '#btnexportdetalle', function(event) {
        var data = DataToFilter();
        if(data.length > 0){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportSeminarioDetalle'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
            //$(this).attr('href','reporte/seminario'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarReporte=function(result){
    var html="";
    $('#TableReporte').DataTable().destroy();

    $.each(result.data,function(index,r){
        var seminario=r.formacion.split("\n").join('<br/>');
        var nro=r.nro_pago.split("\n").join('<br/>');
        var monto=r.monto_pago.split("\n").join('<br/>');


        html+="<tr id='trid_"+r.id+"'>"+
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
            
            "<td>"+seminario+"</td>"+
            "<td>"+nro+"</td>"+
            "<td>"+monto+"</td>"+
            
            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>"+

            "<td>"+$.trim(r.subtotal)+"</td>"+
            "<td>"+$.trim(r.total)+"</td>"+

            "<td>"+r.marketing+"</td>"+
            "<td>"+r.cajera+"</td>"+
            "<td>"+r.matricula+"</td>";
        html+="</tr>";
    });
    $("#TableReporte tbody").html(html); 
    $("#TableReporte").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

</script>
