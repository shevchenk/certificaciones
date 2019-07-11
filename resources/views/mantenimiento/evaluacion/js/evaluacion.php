<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProgramacionG={id:0,dia:"",persona_id:0,persona:"",docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",fecha_campaña:"",
               meta_max:"",meta_min:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd hh:ii:00",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:0,
        autoclose: true,
        todayBtn: false
    });
    
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#TableProgramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $('#ModalProgramacion').css('z-index', 1050);
    
    AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    AjaxProgramacion.CargarTipoEvaluacion(SlctCargarTipoEvaluacion);
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalProgramacion').on('shown.bs.modal', function (event) {

    });

    $('#ModalProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
    });
    
});

ValidarTipoEvaluacion=function(){
    if( $('#slct_tipo_evaluacion').val()!='' ){
        if( $.trim( $(".trclass_"+$('#slct_tipo_evaluacion').val()).html() )=='' ){
            var html='';
            html='<tr class="trclass_'+$('#slct_tipo_evaluacion').val()+'">'+
                '<td>'+$('#slct_tipo_evaluacion').find('option:selected').text()+'</td>'+
                '<td><input type="text" class="form-control fecha" value="2019-07-02" placeholder="Fecha Programada"></td>'+
                '<td><a onClick="QuitarTipoEvaluacion('+$('#slct_tipo_evaluacion').val()+');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td>'+
            '</tr>';
            $('#tb_te').append(html);
        }
        else{
            msjG.mensaje('warning','Tipo de evaluación ya existe!',3000);
        }
    }
    else{
        msjG.mensaje('warning','Seleccione un tipo de evaluación',3000);
    }
}

QuitarTipoEvaluacion=function(te){
    $(".trclass_"+te).remove();
}

SlctCargarTipoEvaluacion=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_evaluacion+"</option>";
    });
    $("#ModalProgramacion #slct_tipo_evaluacion").html(html); 
    $("#ModalProgramacion #slct_tipo_evaluacion").selectpicker('refresh');
};

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalProgramacionForm #txt_docente_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Docente',4000);
    }
    return r;
}

ProgramarEvaluacion=function(){
    $('#ModalProgramacion').modal('show');
}

HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableProgramacion').DataTable().destroy();
    
    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='aula'>"+r.aula+"</td>"+
            "<td class='dias'>"+r.dia+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='fecha_final'>"+r.fecha_final+"</td>"+
            "<td class='fecha_campaña'>"+r.fecha_campaña+"</td>";
        html+='<td><a class="btn btn-primary btn-sm" onClick="ProgramarEvaluacion('+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableProgramacion tbody").html(html); 
    $("#TableProgramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthMenu": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableProgramacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxProgramacion',result.data,'#TableProgramacion_paginate');
        }
    });

};
</script>
