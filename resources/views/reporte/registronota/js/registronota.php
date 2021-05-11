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
        
        return data;
    }
    
    $("#btn_generar").click(function (){
        
        if( DataToFilter() ){
            Reporte.Cargar(HTMLCargarReporte);
        }
    });

    $(document).on('click', '#btnexport', function(event) {
        if( DataToFilter() ){
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportRegistroNota'+'?paterno='+$("#txt_paterno").val()+'&materno='+$("#txt_materno").val()+'&nombre='+$("#txt_nombre").val()+'&especialidad2='+$.trim($("#slct_especialidad").val())+'&curso2='+$.trim($("#slct_curso").val())+'&dni='+$.trim($("#txt_dni").val()));
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
    var html='<option value="">.::Seleccione::.</option>';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.curso+'</option>';
    })
    $("#slct_curso").html(html);
    $("#slct_curso").selectpicker('refresh');
}


HTMLCargarReporte=function(result){
    var html="";
    $('#TableReporte').DataTable().destroy();
    $('#TableReporte #cabecera,#TableReporte #cabecera2').find(".cabecera").remove();

    for (var i = 0; i < result.data[1].length; i++) {
        $('#TableReporte #cabecera,#TableReporte #cabecera2').append('<th class="cabecera" style="background-color: #FFF2CC;">N'+(i+1)+'</th>');
    }
    $('#TableReporte #cabecera,#TableReporte #cabecera2').append('<th class="cabecera" style="background-color: #FFF2CC;">Promedio Final</th>');

    cantidad=result.data[1].length+1;

    $("#notacurso").attr("colspan",cantidad);
    $.each(result.data[0],function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>"+r.dni+"</td>"+
            "<td>"+r.nombre+"</td>"+
            "<td>"+r.paterno+"</td>"+
            "<td>"+r.materno+"</td>"+
            "<td>"+r.celular+"</td>"+
            "<td>"+r.email+"</td>"+

            "<td>"+r.empresa_inscripcion+"</td>"+
            "<td>"+r.fecha_matricula+"</td>"+
            "<td>"+r.tipo_formacion+"</td>"+
            "<td>"+r.formacion+"</td>"+
            "<td>"+r.curso+"</td>"+
            "<td>"+$.trim(r.local)+"</td>"+
            "<td>"+$.trim(r.frecuencia)+"</td>"+
            "<td>"+$.trim(r.horario)+"</td>"+
            "<td>"+$.trim(r.turno)+"</td>"+
            "<td>"+$.trim(r.inicio)+"</td>";

            for (var i = 0; i < result.data[1].length; i++) {
                html+="<td>"+r['n'+(i+1)]+"</td>";
            }
            
        html+="<td>"+r.promedio+"</td>";
        html+="</tr>";
    });

    html1="<tr>";
    html2="<tr>";
    $.each(result.data[1],function(index,r){
        html1 += "<td>N"+(index+1)+"</td>";
        html2 += "<td>"+r.tipo_evaluacion+"</td>";
    });
    html1+='</tr>';
    html2+='</tr>';

    $("#TableNotas thead").html(html1);
    $("#TableNotas tbody").html(html2);

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
