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

    $("#slct_trabajador").on('hidden.bs.select', function(e, clickedIndex, isSelected, previousValue){
        var html='';
        var tra=$(this).val();
        var val=$("#r_nro_asignacion").val();
        var val2=$("#nro_asignacion").val();
        $("#tb_asignar").html('');
        for (var i = 0; i < $(this).val().length ; i++) {
            html='<tr>';
            html+=   '<td>'+$("#slct_trabajador option[value='"+tra[i]+"']").text()+'</td>';
            html+=   '<td><input name="txt_rasig_trabajador[]" class="form-control rasig_trabajador" type="text" value="'+val+'"></td>';
            html+=   '<td><input name="txt_asig_trabajador[]" class="form-control asig_trabajador" type="text" value="'+val2+'"></td>';
            html+='</tr>';
            $("#tb_asignar").append(html);
        }
    });
});

AsignaNro=function(val){
    if(val==2){
        var val=$("#nro_asignacion").val()*1;
        $("#tb_asignar input.asig_trabajador").val(val);
    }
    else{
        var val=$("#r_nro_asignacion").val()*1;
        $("#tb_asignar input.rasig_trabajador").val(val);
    }
}

Guardar=function(){
    var trasig= $("#TableVisita tbody").find("td:eq(2)").text()*1;
    var tasig= $("#TableVisita tbody").find("td:eq(1)").text()*1;
    var asig=0;
    var rasig=0;
    for (var i = 0; i < $("#slct_trabajador").val().length ; i++) {
        rasig= rasig+$("#tb_asignar tr").find("input:eq(0)").val()*1;
        asig= asig+$("#tb_asignar tr").find("input:eq(1)").val()*1;
    }

    if( TI==0 ){
        msjG.mensaje('warning','No se puede asignar el Nro de Interesados es 0',3000);
    }
    else if( $.trim($("#slct_trabajador").val())=='' ){
        msjG.mensaje('warning','Seleccione los trabajadores',3000);
    }
    else if( trasig<rasig ){
        msjG.mensaje('warning','# Reasignar ('+rasig+') no puede ser mayor a # Interesados Asignados ('+trasig+')',6000);
    }
    else if( tasig<asig ){
        msjG.mensaje('warning','# Asignar ('+asig+') no puede ser mayor a # Interesados Sin Asignar ('+tasig+')',6000);
    }
    /*else if( 1==1 ){
        msjG.mensaje('warning',trasig+' = '+rasig+' | '+tasig+' = '+asig,10000);
    }*/
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
