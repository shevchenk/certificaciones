<script type="text/javascript">
let MatriculaG = {
    BtnAuxSi: '<a class="btn btn-flat btn-info" href="#" target="blank"><i class="fa fa-download fa-lg"></i></a>',
    BtnAuxNo: '<a class="btn btn-flat btn-danger"><i class="fa fa-ban fa-lg"></i></a>',
};
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
        
        return data;
    }
    
    $("#btn_generar").click(function (){
        
        if( DataToFilter() ){
            Reporte.Cargar(HTMLCargarReporte);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        if( DataToFilter() ){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportControlPago'+'?paterno='+$("#txt_paterno").val()+'&materno='+$("#txt_materno").val()+'&nombre='+$("#txt_nombre").val()+'&especialidad2='+$.trim($("#slct_especialidad").val())+'&curso2='+$.trim($("#slct_curso").val())+'&dni='+$.trim($("#txt_dni").val())+'&fecha_ini='+$.trim($("#txt_fecha_ini").val())+'&fecha_fin='+$.trim($("#txt_fecha_fin").val())+'&medio_captacion='+$.trim($("#txt_medio_captacion").val())+'&medio_captacion2='+$.trim($("#txt_medio_captacion2").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarEspecialidad(HTMLCargarEspecialidad);
    Reporte.CargarCurso(HTMLCargarCurso);

});

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

    $.each(result.data,function(index,r){
        var pagos= r.pagos.split('\n').join("</li><li>");
        var programacion_cuota= r.programacion_cuota.split('\n').join("</li><li>");
        var pagos_cuota= r.pagos_cuota.split('\n').join("</li><li>");
        var pagos2_cuota= r.pagos2_cuota.split('\n').join("</li><li>");
        var deuda_cuota= r.deuda_cuota.split('\n').join("</li><li>");
        const d = new Date();
        let time = d.getTime();
    
        if( r.archivo_pago_matricula != '' ){
            r.archivo_pago_matricula = MatriculaG.BtnAuxSi.replace("#", r.archivo_pago_matricula+"?time="+time);
        }
        else{
            r.archivo_pago_matricula = MatriculaG.BtnAuxNo;
        }

        if( r.archivo_pago_inscripcion != '' ){
            r.archivo_pago_inscripcion = MatriculaG.BtnAuxSi.replace("#", r.archivo_pago_inscripcion+"?time="+time);
        }
        else{
            r.archivo_pago_inscripcion = MatriculaG.BtnAuxNo;
        }

        if( r.archivo_pago_promocion != '' ){
            r.archivo_pago_promocion = MatriculaG.BtnAuxSi.replace("#", r.archivo_pago_promocion+"?time="+time);
        }
        else{
            r.archivo_pago_promocion = MatriculaG.BtnAuxNo;
        }

        if( r.tenencia = 0 ){
            r.tenencia = 'Alquilado';
        }
        else{
            r.tenencia = 'Propio';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.celular+"</td>"+
            "<td>"+r.email+"</td>"+

            "<td>"+$.trim(r.estado_civil)+"</td>"+
            "<td>"+$.trim(r.sexo)+"</td>"+
            "<td>"+$.trim(r.fecha_nacimiento)+"</td>"+
            "<td>"+$.trim(r.pais)+"</td>"+
            "<td>"+$.trim(r.colegio)+"</td>"+
            "<td>"+$.trim(r.distrito)+"</td>"+
            "<td>"+$.trim(r.provincia)+"</td>"+
            "<td>"+$.trim(r.region)+"</td>"+
            "<td>"+$.trim(r.distrito_dir)+"</td>"+
            "<td>"+$.trim(r.provincia_dir)+"</td>"+
            "<td>"+$.trim(r.region_dir)+"</td>"+
            "<td>"+$.trim(r.tenencia)+"</td>"+
            "<td>"+$.trim(r.direccion)+"</td>"+
            "<td>"+$.trim(r.referencia)+"</td>"+

            "<td>"+r.empresa_inscripcion+"</td>"+
            "<td>"+r.fecha_matricula+"</td>"+
            "<td>"+r.tipo_formacion+"</td>"+
            "<td>"+r.formacion+"</td>"+
            "<td>"+r.curso+"</td>"+
            "<td>"+$.trim(r.local)+"</td>"+
            "<td>"+$.trim(r.frecuencia)+"</td>"+
            "<td>"+$.trim(r.horario)+"</td>"+
            "<td>"+$.trim(r.turno)+"</td>"+
            "<td>"+$.trim(r.inicio)+"</td>"+
            
            "<td>"+$.trim(r.nro_pago)+"</td>"+
            "<td>"+$.trim(r.monto_pago)+"</td>"+
            "<td>"+$.trim(r.tipo_pago)+"</td>"+
            
            "<td>"+$.trim(r.nro_promocion)+"</td>"+
            "<td>"+$.trim(r.monto_promocion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_promocion)+r.archivo_pago_promocion+"</td>"+

            "<td>"+$.trim(r.nro_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.monto_pago_inscripcion)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_inscripcion)+r.archivo_pago_inscripcion+"</td>"+

            "<td>"+$.trim(r.nro_pago_matricula)+"</td>"+
            "<td>"+$.trim(r.monto_pago_matricula)+"</td>"+
            "<td>"+$.trim(r.tipo_pago_matricula)+r.archivo_pago_matricula+"</td>"+

            "<td><ul><li>"+$.trim(pagos)+"</li></ul></td>"+

            "<td>"+$.trim(r.deuda)+"</td>"+
            "<td>"+$.trim(r.nota)+"</td>"+
            
            "<td><ul><li>"+$.trim(programacion_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(pagos2_cuota)+"</li></ul></td>"+
            
            "<td><ul><li>"+$.trim(pagos_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(deuda_cuota)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.deuda_total)+"</li></ul></td>"+
            
            "<td><ul><li>"+$.trim(r.sucursal)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.recogo_certificado)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.cajera)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.marketing)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.centro_operacion)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.medio_captacion)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.comollego)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.matricula)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.estado_mat)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.fecha_estado)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.expediente)+"</li></ul></td>"+
            "<td><ul><li>"+$.trim(r.fecha_expediente)+"</li></ul></td>";

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
