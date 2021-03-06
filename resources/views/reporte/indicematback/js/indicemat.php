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
        var r=true;
        if ( fecha_inicial=="" || fecha_final=="") {
            r=false;
            swal("Mensaje", "Por favor seleccione todos los campos!")
        }
        return r;
    }
    
    $("#btn_generar").click(function (){
        var r = DataToFilter();
        if( r ){
            AjaxIndice.Cargar(HTMLCargarIndiceMat);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        var r = DataToFilter();
        if( r ){
            $(this).attr('href','ReportDinamic/Reporte.ReporteEM@ExportIndiceMat'+'?fecha_ini='+$('#txt_fecha_ini').val()+'&fecha_fin='+$('#txt_fecha_fin').val()+'&tipo_curso='+$('#slct_tipo_curso').val());
        }else{
            event.preventDefault();
        }
    });

});


HTMLCargarIndiceMat=function(result){
    var html="";
    $('#TableIndiceMat').DataTable().destroy();
    //swal('tittle','hola');
    $.each(result.data,function(index,r){
        indice_x_dia=0;
        mat_prog_x_dia=0;
        proy_fin_cam=0;
        if(r.ndias>0){
            indice_x_dia = Math.round( (r.mat/r.ndias)*100 )/100;
            mat_prog_x_dia=Math.round( (indice_x_dia*r.dias_falta)*100 )/100;
        }
        proy_fin_cam=Math.round( (r.mat+mat_prog_x_dia)*100)/100;
        color="FF4848";
        if( proy_fin_cam>=r.meta_max ){
            color="35FF35";
        }
        else if( proy_fin_cam>=r.meta_min ){
            color="FFFF48";
        }
        mat_falt_meta = Math.round( (r.meta_max - proy_fin_cam)*100)/100;
        html+="<tr id='trid_"+r.ode+"'>"+
            "<td>"+(index+1)+"</td>"+
            "<td>"+r.empresa+"</td>"+
            "<td>"+r.tipo_formacion+"</td>"+
            "<td>"+r.formacion+"</td>"+
            "<td>"+r.dia+"</td>"+
            "<td>"+r.fecha_inicio.substr(0,10)+"</td>"+
            "<td>"+r.penult_dia+"</td>"+
            "<td>"+r.ult_dia+"</td>"+
            "<td>"+r.mat+"</td>"+
            "<td>"+r.meta_max+"</td>"+
            "<td>"+r.meta_min+"</td>"+
            "<td>"+r.fecha_campaña+"</td>"+
            "<td>"+r.ndias+"</td>"+
            "<td>"+indice_x_dia+"</td>"+
            "<td>"+r.dias_falta+"</td>"+
            "<td>"+mat_prog_x_dia+"</td>"+
            "<td bgcolor='"+color+"'>"+proy_fin_cam+"</td>"+
            "<td>"+mat_falt_meta+"</td>";
        html+="</tr>";
    });
    $("#TableIndiceMat tbody").html(html); 
    $("#TableIndiceMat").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
};

</script>
