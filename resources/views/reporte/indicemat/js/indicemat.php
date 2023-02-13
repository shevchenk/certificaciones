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
        data=true;
        
        if( fecha_final=='' || fecha_inicial=='' ){
            data=false;
            swal("Mensaje", "Por favor seleccione ambas fechas!")
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
            $(this).attr('href','ReportDinamic/Reporte.ReporteAvanzadoEM@ExportIndiceMatriculacion'+'?fecha_inicial='+$("#txt_fecha_inicial").val()+'&fecha_final='+$("#txt_fecha_final").val()+'&sucursal2='+$.trim($("#slct_sucursal").val())+'&empresa2='+$.trim($("#slct_empresa").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarSucursal(HTMLCargarSucursal);
    Reporte.CargarEmpresa(HTMLCargarEmpresa);
    Reporte.CargarEspecialidad(HTMLCargarEspecialidad);
    Reporte.CargarCurso(HTMLCargarCurso);

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

HTMLCargarEspecialidad=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.especialidad+'</option>';
    })
    $("#slct_especialidad").html(html);
    $("#slct_especialidad").selectpicker('refresh');
}

HTMLCargarCurso=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.curso+'</option>';
    })
    $("#slct_curso").html(html);
    $("#slct_curso").selectpicker('refresh');
}


HTMLCargarReporte=function(result){
    var html="";
    $('#TableReporte').DataTable().destroy();
    $("#TableReporte tbody").html(html); 
    $.each(result.data,function(index,r){
        r.ind_sa = (r.sa / 7).toFixed(2);
        r.ind_ud = (r.ud / 7).toFixed(2);
        r.pro_df = (r.dias_falta * r.ind_ud).toFixed(0);
        r.pro_fin = r.total*1 + r.pro_df*1;
        r.falta_meta = r.meta_max - r.pro_fin;
        if( r.falta_meta < 0 ){
            r.falta_meta = 0;
        }
        //color="FFFF4848";
        color = 'danger';
        if( r.pro_fin >= r.meta_max ){
            //color="FF35FF35";
            color = 'success';
        }
        else if( r.pro_fin >= r.meta_min ){
            //color="FFFFFF48";
            color = 'warning';
        }

        html="<tr>"+
            "<td>"+r.ode+"</td>"+
            "<td>"+r.empresa+"</td>"+
            "<td>"+r.formacion+"</td>"+
            "<td>"+r.curso+"</td>"+
            "<td>"+r.frecuencia+"</td>"+
            "<td>"+r.horario+"</td>"+
            "<td>"+r.inicio+"</td>"+

            "<td>"+r.f14+"</td>"+
            "<td>"+r.f13+"</td>"+
            "<td>"+r.f12+"</td>"+
            "<td>"+r.f11+"</td>"+
            "<td>"+r.f10+"</td>"+
            "<td>"+r.f9+"</td>"+
            "<td>"+r.f8+"</td>"+
            "<td>"+r.f7+"</td>"+
            "<td>"+r.f6+"</td>"+
            "<td>"+r.f5+"</td>"+
            "<td>"+r.f4+"</td>"+
            "<td>"+r.f3+"</td>"+
            "<td>"+r.f2+"</td>"+
            "<td>"+r.f1+"</td>"+
            "<td>"+r.sa+"</td>"+
            "<td>"+r.ud+"</td>"+
            "<td>"+r.total+"</td>"+

            "<td>"+r.meta_max+"</td>"+
            "<td>"+r.meta_min+"</td>"+
            "<td>"+r.fecha_campaña+"</td>"+
            "<td>"+r.dias_campaña+"</td>"+
            "<td>"+r.dias_falta+"</td>"+
            "<td>"+r.ind_sa+"</td>"+
            "<td>"+r.ind_ud+"</td>"+
            "<td>"+r.pro_df+"</td>"+
            "<td class='"+color+"'>"+r.pro_fin+"</td>"+
            "<td>"+r.falta_meta+"</td>"+
            "<td>"+r.obs+"</td>"+
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
