<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProgramacionG={id:0,persona_id:0,docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd hh:ii:ss",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:0,
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
    CargarSlct(1);CargarSlct(2);CargarSlct(3);
    AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalProgramacion').on('shown.bs.modal', function (event) {

        if( AddEdit==1 ){        
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalProgramacionForm").append("<input type='hidden' value='"+ProgramacionG.id+"' name='id'>");
        }

        $('#ModalProgramacionForm #slct_docente_id').val( ProgramacionG.docente_id );
        $('#ModalProgramacionForm #txt_persona_id').val( ProgramacionG.persona_id );
        $('#ModalProgramacionForm #slct_sucursal_id').val( ProgramacionG.sucursal_id );
        $('#ModalProgramacionForm #slct_curso_id').val( ProgramacionG.curso_id );
        $('#ModalProgramacionForm #txt_aula').val( ProgramacionG.aula );
        $('#ModalProgramacionForm #txt_fecha_inicio').val( ProgramacionG.fecha_inicio );
        $('#ModalProgramacionForm #txt_fecha_final').val( ProgramacionG.fecha_final );
        $('#ModalProgramacionForm #slct_estado').val( ProgramacionG.estado );
        $("#ModalProgramacion select").selectpicker('refresh');
        $('#ModalProgramacionForm #slct_docente_id').focus();
    });

    $('#ModalProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
    });
    
    $( "#ModalProgramacionForm #slct_docente_id" ).change(function() {
            var persona_id= $(this).children("option:selected").data('personaid');
            $('#ModalProgramacionForm #txt_persona_id').val(persona_id);
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalProgramacionForm #slct_docente_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Docente',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #slct_sucursal_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sucursal',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #slct_curso_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Curso',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_fecha_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha de Inicio',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_fecha_final").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha Final',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    ProgramacionG.id='';
     ProgramacionG.persona_id='';
    ProgramacionG.docente_id='';
    ProgramacionG.sucursal_id='';
    ProgramacionG.curso_id='';
    ProgramacionG.aula='';
    ProgramacionG.fecha_inicio='';
    ProgramacionG.fecha_final='';
    ProgramacionG.estado='1';
    if( val==0 ){
        ProgramacionG.id=id;
        ProgramacionG.docente_id=$("#TableProgramacion #trid_"+id+" .docente_id").val();
        ProgramacionG.persona_id=$("#TableProgramacion #trid_"+id+" .persona_id").val();
        ProgramacionG.sucursal_id=$("#TableProgramacion #trid_"+id+" .sucursal_id").val();
        ProgramacionG.curso_id=$("#TableProgramacion #trid_"+id+" .curso_id").val();
        ProgramacionG.aula=$("#TableProgramacion #trid_"+id+" .aula").text();
        ProgramacionG.fecha_inicio=$("#TableProgramacion #trid_"+id+" .fecha_inicio").text();
        ProgramacionG.fecha_final=$("#TableProgramacion #trid_"+id+" .fecha_final").text();  
        ProgramacionG.estado=$("#TableProgramacion #trid_"+id+" .estado").val();
    }
    $('#ModalProgramacion').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProgramacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProgramacion').modal('hide');
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
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
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='fecha_final'>"+r.fecha_final+"</td>"+
            "<td>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
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
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxProgramacion',result.data,'#HTMLCargarProgramacion_paginate');
        }
    });

};

CargarSlct=function(slct){
    if(slct==1){
    AjaxProgramacion.CargarSucursal(SlctCargarSucursal);
    }
    if(slct==2){
    AjaxProgramacion.CargarDocente(SlctCargarDocente);
    }
    if(slct==3){
    AjaxProgramacion.CargarCurso(SlctCargarCurso);
    }
}

SlctCargarSucursal=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalProgramacion #slct_sucursal_id").html(html); 
    $("#ModalProgramacion #slct_sucursal_id").selectpicker('refresh');

};

SlctCargarDocente=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+" data-personaid="+r.persona_id+">"+r.persona+"</option>";
    });
    $("#ModalProgramacion #slct_docente_id").html(html); 
    $("#ModalProgramacion #slct_docente_id").selectpicker('refresh');

};

SlctCargarCurso=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.curso+"</option>";
    });
    $("#ModalProgramacion #slct_curso_id").html(html); 
    $("#ModalProgramacion #slct_curso_id").selectpicker('refresh');

};
</script>
