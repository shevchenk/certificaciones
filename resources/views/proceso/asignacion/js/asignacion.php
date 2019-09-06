<script type="text/javascript">
    var TI=0;
    var ISA=0;
    var IA=0;
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

    AjaxVisita.Trabajadores(SlctCargarTrabajador);
    
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
            AjaxVisita.Cargar(HTMLCargarVisita);
        }
    });

});

Guardar=function(id, matricula_id){
    
    if( TI==0 ){
        msjG.mensaje('warning','No se puede asignar el Nro de Interesados es 0',3000);
    }
    else if( $.trim($("#slct_trabajador").val())=='' ){
        msjG.mensaje('warning','Seleccione los trabajadores',3000);
    }
    else{
        AjaxVisita.Guardar(HTMLGuardar);
    }
}

HTMLGuardar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxVisita.Cargar(HTMLCargarVisita);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

SlctCargarTrabajador=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.trabajador+"</option>";
    });
    $("#AsignacionForm #slct_trabajador").html(html); 
    $("#AsignacionForm #slct_trabajador").selectpicker('refresh');

};

HTMLCargarVisita=function(result){
    var html="";
    $.each(result.data,function(index,r){
        faltante= r.total*1 - r.sin_asignar*1;
        TI= r.total;
        ISA= r.sin_asignar;
        IA= faltante;
        html+="<tr>"+
            "<td>"+$.trim(r.total)+"</td>"+
            "<td>"+$.trim(r.sin_asignar)+"</td>"+
            "<td>"+faltante+"</td>";
        html+="</tr>";
    });
    $("#TableVisita tbody").html(html); 
};

</script>
