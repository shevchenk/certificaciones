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
            $(this).attr('href','ReportDinamic/Reporte.TramiteHistoEM@Export'+'?fecha_inicial='+data[0]['fecha_inicial']+'&fecha_final='+data[0]['fecha_final']);
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarReporte=function(result){
    var html="";
    $('#TableReporte').DataTable().destroy();

    $.each(result.data,function(index,r){
        html+="<tr id='trid_"+r.id_envio+"'>"+
            "<td>"+r.id_envio+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.certificado+"</td>"+
            "<td>"+r.nota_certificado+"</td>"+
            "<td>"+r.direccion+"</td>"+
            "<td>"+r.referencia+"</td>"+
            "<td>"+r.region+"</td>"+
            "<td>"+r.provincia+"</td>"+
            "<td>"+r.distrito+"</td>"+
            "<td>"+r.fecha_estado_certificado+"</td>"+
            "<td>"+r.sucursal+"</td>"+
            "<td>"+r.nro_pago+"</td>"+
            "<td>"+r.fecha_pago+"</td>"+
            "<td>"+r.fecha_inicio_bandeja+"</td>"+
            "<td>"+r.estado_certificado+"</td>";
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
