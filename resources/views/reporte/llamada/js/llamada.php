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
        container : 'body',
        todayBtn: false
    });

    $('#spn_fecha_ini').on('click', function(){
        $('#txt_fecha_ini').focus();
    });
    $('#spn_fecha_fin').on('click', function(){
        $('#txt_fecha_fin').focus();
    });

    $('#spn_fecha_ini_dis').on('click', function(){
        $('#txt_fecha_ini_dis').focus();
    });
    $('#spn_fecha_fin_dis').on('click', function(){
        $('#txt_fecha_fin_dis').focus();
    });
    
    function DataToFilter( reporte ){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var r=true;
        
        var fechaInicio = new Date( fecha_inicial ).getTime();
        var fechaFin    = new Date( fecha_final ).getTime();
        var diff = (fechaFin - fechaInicio) / (1000*60*60*24);

        if ( fecha_inicial=="" || fecha_final=="") {
            r=false;
            swal("Mensaje", "Por favor seleccione la fecha inicial y la fecha final!")
        }
        else if ( reporte != 'ExportLlamadasDetalle' && diff>30 ){
            r=false;
            swal("Mensaje", "El rango de fecha no puede ser superior a 30 días");
        }
        return r;
    }

    $(document).on('click', '.btnexport', function(event) {
        var r = DataToFilter( $(this).attr('data-reporte') );
        if( r ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@'+$(this).attr('data-reporte')+'?fecha_ini='+$('#txt_fecha_ini').val()+'&fecha_fin='+$('#txt_fecha_fin').val()+'&vendedor='+$.trim($('#slct_vendedor').val())+'&tipo='+$.trim($('#slct_tipo').val())+'&empresa='+$.trim($('#slct_empresa').val())+'&fuente='+$.trim($('#slct_fuente').val())+'&ultimo_registro='+$.trim($('#slct_ultimo_registro').val()));
        }else{
            event.preventDefault();
        }
    });
    AjaxLlamada.ListarVendedor(SlctListarVendedor);
    AjaxLlamada.ListarFuente(SlctListarFuente);

});

SlctListarVendedor=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+="<option data-subtext='"+$.trim(r.codigo)+"' value="+r.id+">"+r.trabajador+"</option>";
    });
    $("#slct_vendedor").html(html); 
    $("#slct_vendedor").selectpicker('refresh');
};

SlctListarFuente=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value='"+r.fuente+"'>"+r.fuente+"</option>";
    });
    $("#slct_fuente").html(html); 
    $("#slct_fuente").selectpicker('refresh');
};

</script>
