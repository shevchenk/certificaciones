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
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var r=true;
        if ( fecha_inicial=="" || fecha_final=="") {
            r=false;
            swal("Mensaje", "Por favor seleccione la fecha inicial y la fecha final!")
        }
        return r;
    }

    $(document).on('click', '.btnexport', function(event) {
        var r = DataToFilter();
        if( r ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@'+$(this).attr('data-reporte')+'?fecha_ini='+$('#txt_fecha_ini').val()+'&fecha_fin='+$('#txt_fecha_fin').val()+'&vendedor='+$.trim($('#slct_vendedor').val())+'&tipo='+$.trim($('#slct_tipo').val())+'&empresa='+$.trim($('#slct_empresa').val())+'&fuente='+$.trim($('#slct_fuente').val()));
        }else{
            event.preventDefault();
        }
    });
    AjaxLlamada.ListarVendedor(SlctListarVendedor);
    AjaxLlamada.ListarFuente(SlctListarFuente);
    AjaxLlamada.ListarTipo(SlctListarTipo);
    AjaxLlamada.ListarEmpresa(SlctListarEmpresa);

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
        html+="<option value="+r.fuente+">"+r.fuente+"</option>";
    });
    $("#slct_fuente").html(html); 
    $("#slct_fuente").selectpicker('refresh');
};

SlctListarTipo=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.tipo+">"+r.tipo+"</option>";
    });
    $("#slct_tipo").html(html); 
    $("#slct_tipo").selectpicker('refresh');
};

SlctListarEmpresa=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.empresa+">"+r.empresa+"</option>";
    });
    $("#slct_empresa").html(html); 
    $("#slct_empresa").selectpicker('refresh');
};

</script>
