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
        var formacion="<ul><li>"+$.trim(r.formacion).split("\n").join('</li><li>')+"</li></ul>";
        var fecha_inicio="<ul><li>"+$.trim(r.fecha_inicio).split("\n").join('</li><li>')+"</li></ul>";
        var nro="<ul><li>"+$.trim(r.nro_pago).split("\n").join('</li><li>')+"</li></ul>";
        var monto="<ul><li>"+$.trim(r.monto_pago).split("\n").join('</li><li>')+"</li></ul>";
        var tipo_pago="<ul><li>"+$.trim(r.tipo_pago).split("\n").join('</li><li>')+"</li></ul>";
        var nota="<ul><li>"+$.trim(r.nota).split("\n").join('</li><li>')+"</li></ul>";
        var deuda="<ul><li>"+$.trim(r.deuda).split("\n").join('</li><li>')+"</li></ul>";
        var validada='Validó';
            if(r.validada==0){
                validada='Falta Validar';
            }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.celular+"</td>"+
            "<td>"+r.email+"</td>"+

            "<td>"+r.fecha_matricula+"</td>"+
            "<td>"+r.empresa_inscripcion+"</td>"+
            "<td>"+r.tipo_formacion+"</td>"+
            "<td>"+formacion+"</td>"+
            
            "<td>"+nro+"</td>"+
            "<td>"+monto+"</td>"+
            "<td>"+tipo_pago+"</td>"+
            "<td>"+$.trim(r.total)+"</td>"+
            
            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_promocion)+"</td>"+

            "<td>"+$.trim(r.nro_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.monto_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_inscripcion)+"</td>"+

            "<td>"+deuda+"</td>"+
            "<td>"+nota+"</td>";
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
