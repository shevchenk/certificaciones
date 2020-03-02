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
        $('#txt_fecha_matricula').focus();
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
        var fecha_matricula = $('#txt_fecha_matricula').val();
        var empresa = $.trim($("#slct_empresa").val());
        var tipo = $.trim($("#slct_tipo").val());
        data=true;
        
        if( empresa=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione empresa!")
        }
        else if( fecha_matricula=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione fecha de matrícula!")
        }
        else if( tipo=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione tipo!")
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
            $(this).attr('href','ReportDinamic/Reporte.ReporteAvanzadoEM@ExportVendedorComision'+'?fecha_matricula='+$("#txt_fecha_matricula").val()+'&tipo='+$("#slct_tipo").val()+'&vendedor2='+$.trim($("#slct_vendedor").val())+'&empresa2='+$.trim($("#slct_empresa").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarMedioCaptacion(HTMLCargarMedioCaptacion);
    Reporte.CargarEmpresa(HTMLCargarEmpresa);

});

HTMLCargarMedioCaptacion=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.medio_captacion+'</option>';
    })
    $("#slct_medio_captacion").html(html);
    $("#slct_medio_captacion").selectpicker('refresh');
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
        html="<tr>"+
            "<td>"+(index+1)+"</td>"+
            "<td>"+r.codigo+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.cargo+"</td>";

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
