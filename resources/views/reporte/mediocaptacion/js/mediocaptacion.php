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

    $('#spn_fecha_ins_ini').on('click', function(){
        $('#txt_fecha_inscripcion_inicial').focus();
    });
    $('#spn_fecha_ins_fin').on('click', function(){
        $('#txt_fecha_inscripcion_final').focus();
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
        var fecha_inscripcion_inicial = $('#txt_fecha_inscripcion_inicial').val();
        var fecha_inscripcion_final = $('#txt_fecha_inscripcion_final').val();
        data=true;
        
        if( fecha_final=='' || fecha_inicial=='' || fecha_inscripcion_final=='' || fecha_inscripcion_inicial=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione todas las fechas!")
        }
        else if( $.trim($("#slct_empresa").val())=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione almenos 1 empresa!")
        }
        return data;
    }
    
    $("#btn_generar").click(function (){
        
        if( DataToFilter() ){
            Reporte.Cargar(HTMLCargarReporte);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        if( DataToFilter() ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteAvanzadoEM@ExportMedioCaptacion'+'?fecha_inicial='+$("#txt_fecha_inicial").val()+'&fecha_final='+$("#txt_fecha_final").val()+'&fecha_inscripcion_inicial='+$("#txt_fecha_inscripcion_inicial").val()+'&fecha_inscripcion_final='+$("#txt_fecha_inscripcion_final").val()+'&sucursal2='+$.trim($("#slct_sucursal").val())+'&empresa2='+$.trim($("#slct_empresa").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarSucursal(HTMLCargarSucursal);
    Reporte.CargarEmpresa(HTMLCargarEmpresa);

});

HTMLCargarSucursal=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.sucursal+'</option>';
    })
    $("#slct_sucursal").html(html);
    $("#slct_sucursal").selectpicker('refresh');
}

HTMLCargarEmpresa=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.empresa+'</option>';
    })
    $("#slct_empresa").html(html);
    $("#slct_empresa").selectpicker('refresh');
}


HTMLCargarReporte=function(result){
    var html="";
    $('#TableReporte').DataTable().destroy();
    $("#TableReporte tbody").html(html); 

    $('#TableReporte #cabecera,#TableReporte #cabecera2').find(".cabecera").remove();
    cantidad = $("#slct_empresa").val().length;
    for (var i = 0; i < cantidad; i++) {
        $('#TableReporte #cabecera,#TableReporte #cabecera2').append('<th class="cabecera" style="background-color: #FFF2CC;">'+$("#slct_empresa option[value='"+$("#slct_empresa").val()[i]+"']").text()+'</th>');
    }
    $('#TableReporte #cabecera,#TableReporte #cabecera2').append('<th class="cabecera" style="background-color: #FFF2CC;">Total</th>');
    $("#titmedio").attr("colspan",cantidad*1+4);

    var ode_aux='';
    $.each(result.data,function(index,r){
        if( ode_aux!=r.ode ){
            ode_aux=r.ode;
            contador=0;
        }
        contador++;
        r.nro = contador;
        html="<tr>"+
            "<td>"+r.ode+"</td>"+
            "<td>"+r.nro+"</td>"+
            "<td>"+r.medio_captacion+"</td>";

        for (var i = 1; i <= cantidad; i++) {
        html+="<td>"+r['e'+i]+"</td>";
        }
        
        html+="<td>"+r.total+"</td>"+
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
};

</script>
