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
            $(this).attr('href','ReportDinamic/Reporte.SeminarioEM@ExportAsesoria'+'?paterno='+$("#txt_paterno").val()+'&materno='+$("#txt_materno").val()+'&nombre='+$("#txt_nombre").val()+'&especialidad_id='+$.trim($("#slct_especialidad_id").val())+'&dni='+$.trim($("#txt_dni").val()));
        }else{
            event.preventDefault();
        }
    });

    Reporte.CargarEspecialidad(HTMLCargarEspecialidad);

    $('#ModalEntrega').on('shown.bs.modal', function (event) {
        
    });

    $('#ModalEntrega').on('hidden.bs.modal', function (event) {
        $("#ModalEntregaForm input[type='hidden']").not('.mant').remove();
        $("#ModalEntregaForm .seminario").show();
    });

});

ConfirmarPeticion=function(persona_id,curso_id){
    paterno=$("#trid_"+persona_id+" .paterno").text();
    materno=$("#trid_"+persona_id+" .materno").text();
    nombre=$("#trid_"+persona_id+" .nombre").text();
    celular=$("#trid_"+persona_id+" .celular").text();
    curso=$("#trc_"+curso_id+" .curso").text();
    modulo= $("#slct_especialidad_id option:selected").text();
    $("#ModalEntregaForm .seminario").hide();

    $("#ModalEntregaForm #txt_persona_id").val( persona_id );
    $("#ModalEntregaForm #txt_matricula_detalle_id").val( '' );
    $("#ModalEntregaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $("#ModalEntregaForm #txt_celular").val( celular );
    $('#ModalEntregaForm #txt_comentario').val('El alumno desea inscribirse al curso de "'+curso+'" del módulo "'+modulo+'"');
    Reporte.CargarLlamada(HTMLCargarLlamada);
    $('#ModalEntrega').modal('show');
}

HTMLCargarLlamada=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+="<tr>";
        html+="<td>"+r.fecha_registro+"</td>";
        html+="<td>"+r.comentario+"</td>";
        html+="<td>"+$.trim(r.fecha_respuesta)+"</td>";
        html+="<td>"+$.trim(r.respuesta)+"</td>";
        html+="</tr>";
    });

    $("#tb_llamada").html(html); 
}

HTMLCargarEspecialidad=function(result){
    var html='';
    $.each(result.data,function(index,r){
        html+='<option value="'+r.id+'">'+r.especialidad+'</option>';
    })
    $("#slct_especialidad_id").html(html);
    $("#slct_especialidad_id").selectpicker('refresh');
}

HTMLCargarReporte=function(result){
    var html=""; var html2="";
    $('#TableReporte').DataTable().destroy();
    $('#TableReporte #cabecera,#TableReporte #cabecera2').find(".cabecera").remove();

    for (var i = 0; i < result.data[1].length; i++) {
        $('#TableReporte #cabecera,#TableReporte #cabecera2').append('<th class="cabecera" style="background-color: #FFF2CC;">C'+(i+1)+'</th>');
        html2+="<tr id='trc_"+(i+1)+"'>"+
                "<td>C"+(i+1)+"</td>"+
                "<td class='curso'>"+result.data[1][i].curso+"</td>"+
                "<td>"+result.data[1][i].credito+"</td>"+
                "<td>"+result.data[1][i].hora+"</td>"+
                "</tr>";
    }
    $("#TableCurso tbody").html(html2); 

    cantidad=result.data[1].length+2;

    $("#notacurso").attr("colspan",cantidad);
    $.each(result.data[0],function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='dni'>"+r.dni+"</td>"+
            "<td class='nombre'>"+r.nombre+"</td>"+
            "<td class='paterno'>"+r.paterno+"</td>"+
            "<td class='materno'>"+r.materno+"</td>"+
            "<td class='celular'>"+r.celular+"</td>"+
            "<td class='email'>"+r.email+"</td>"+
            "<td>"+r.ncursos+"</td>"+
            "<td>"+r.nrelacion+"</td>";

            for (var i = 0; i < result.data[1].length; i++) {
                pintado= "<td class='danger'><a class='btn btn-danger' onClick='ConfirmarPeticion("+r.id+","+(i+1)+")'>No Inscrito</a></td>";
                if( r['c'+(i+1)]>0 ){
                    pintado= "<td class='warning'> Inscrito </td>";
                    if( r['nf'+(i+1)]>0 ){
                        pintado= "<td class='success'> Aprobado </td>";
                    }
                }
                html+=pintado;
            }

        html+="</tr>";
    });
    $("#TableReporte tbody").html(html); 
    $("#TableReporte").DataTable({
        "paging": true,
        
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEntregaForm #txt_comentario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese comentario de la llamada',4000);
    }
    return r;
}

RegistrarEntrega=function(){
    if( ValidaForm() ){
        Reporte.RegistrarLlamada(HTMLRegistrarEntrega);
    }
}

CerrarLlamada=function(){
    if( ValidaForm() ){
        Reporte.CerrarLlamada(HTMLRegistrarEntrega);
    }
}

HTMLRegistrarEntrega=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,5500);
        $('#ModalEntrega').modal('hide');
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

</script>
