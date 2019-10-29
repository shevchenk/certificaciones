<script type="text/javascript">
    var TI=0;
    var TA=0;
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

    AjaxAprobados.CargarEmpresas(SlctCargarEmpresas);
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var empresa = $('#slct_empresas').val();
        var r=true;
        if ( fecha_inicial=="" || fecha_final=="" || empresa=='') {
            r=false;
            swal("Mensaje", "Por favor seleccione todos los campos!")
        }
        return r;
    }
    
    $("#btn_generar").click(function (){
        var r = DataToFilter();
        if( r ){
            AjaxAprobados.Cargar(HTMLCargarVisita);
        }
    });
});

SlctCargarEmpresas=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.empresa+"</option>";
    });
    $("#AsignacionForm #slct_empresas").html(html); 
    $("#AsignacionForm #slct_empresas").selectpicker('refresh');
}

HTMLCargarVisita=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<tr>"+
            "<td>"+$.trim(r.paterno)+"</td>"+
            "<td>"+$.trim(r.materno)+"</td>"+
            "<td>"+$.trim(r.nombre)+"</td>"+
            "<td>"+$.trim(r.curso)+"</td>"+
            "<td>"+$.trim(r.nota)+"</td>"+
            "<td>"+$.trim(r.credito)+"</td>"+
            "<td>"+$.trim(r.fecha_arobado)+"</td>"+
            "";
        html+="</tr>";
    });
    $("#TableVisita tbody").html(html); 


};

</script>
