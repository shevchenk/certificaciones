<script type="text/javascript">
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EPG={id:0,
especialidad:"",
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableEspecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    CargarSlct(3);
    AjaxEspecialidadProgramacion.Cargar(HTMLCargar);
    $("#EspecialidadProgramacionForm #TableEspecialidad select").change(function(){ AjaxEspecialidadProgramacion.Cargar(HTMLCargarEspecialidad); });
    $("#EspecialidadProgramacionForm #TableEspecialidad input").blur(function(){ AjaxEspecialidadProgramacion.Cargar(HTMLCargarEspecialidad); });

    $('#ModalEspecialidadProgramacion').on('shown.bs.modal', function (event) {
        $('#sortable').html('');
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEspecialidadProgramacionForm").append("<input type='hidden' value='"+EPG.id+"' name='id'>");
        }
        
        $('#ModalEspecialidadProgramacionForm #txt_especialidad').val( EPG.especialidad );
        $('#ModalEspecialidadProgramacionForm #txt_codigo_inicio').val( EPG.codigo_inicio );
        $('#ModalEspecialidadProgramacionForm #txt_fecha_inicio').val( EPG.fecha_inicio );
        $('#ModalEspecialidadProgramacionForm #slct_especialidad_id').selectpicker( 'val',EPG.especialidad_id );
        var curso = EPG.curso_id.split(',');
        for (var i = 0; i < curso.length; i++) {
            CargarCronograma(curso[i]);
        }
        $('#ModalEspecialidadProgramacionForm #slct_estado').selectpicker( 'val',EPG.estado );
        $('#ModalEspecialidadProgramacionForm #txt_especialidad').focus();
    });

    $('#ModalEspecialidadProgramacion').on('hidden.bs.modal', function (event) {
        $("ModalEspecialidadProgramacionForm input[type='hidden']").not('.mant').remove();
       // $("ModalEspecialidadProgramacionForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEspecialidadProgramacionForm #slct_especialidad_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Especialidad',4000);
    }
    else if( $.trim( $("#ModalEspecialidadProgramacionForm #txt_fecha_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha de Inicio',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EPG.id='';
    EPG.especialidad_id='';
    EPG.fecha_inicio='';
    EPG.codigo_inicio='A';
    EPG.horario='';
    EPG.cronograma='';
    EPG.estado='1';
    if( val==0 ){
        EPG.id=id;
        EPG.especialidad_id=$("#TableEspecialidad #trid_"+id+" .especialidad_id").text();
        EPG.fecha_inicio=$("#TableEspecialidad #trid_"+id+" .fecha_inicio").text();
        EPG.codigo_inicio=$("#TableEspecialidad #trid_"+id+" .codigo_inicio").text();
        //EPG.curso_id=$("#TableEspecialidad #trid_"+id+" .curso_id").val();

        EPG.estado=$("#TableEspecialidad #trid_"+id+" .estado").val();
    }
    $('#ModalEspecialidadProgramacion').modal('show');
}

CargarCursosHTML=function(result){
    $.each(result.data,function(index,r){
        $('#slct_curso_id').val(r.curso_id);
        AgregarCurso();
    });
    $('#slct_curso_id').val('');
}

AgregarCurso=function(){
    
    cantidad = $('#sortable tr').length*1 + 1;
    cursoid = $('#slct_curso_id').val();
    curso = $('#slct_curso_id option:selected').text();
    if( $.trim(curso) =='' ){
        msjG.mensaje('warning','Seleccione un curso',4000);
    }
    else if( $.trim( $('#sortable #trid_'+cursoid).html() ) == '' ){
        $('#sortable').append('<tr id="trid_'+cursoid+'"><td>N° '+cantidad+'</td><td><input type="hidden" name="curso_id[]" value="'+cursoid+'">'+curso+'</td><td><a onClick="EliminarTr(\'#trid_'+cursoid+'\');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td></tr>');
    }
    else{
        msjG.mensaje('warning','El curso seleccionado ya fue agregado',4000);
    }
}

EliminarTr=function(t){
    $(t).remove();
}

CambiarEstado=function(estado,id){
    AjaxEspecialidadProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidadProgramacion.Cargar(HTMLCargarEspecialidad);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEspecialidadProgramacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        cursos_selec=[];
        $('#ModalEspecialidad').modal('hide');
        AjaxEspecialidadProgramacion.Cargar(HTMLCargarEspecialidad);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

CargarSlct=function(slct){

    if(slct==3){
    AjaxEspecialidadProgramacion.CargarCurso(SlctCargarCurso);
    }
}

SlctCargarCurso=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.curso+"</option>";
    });
    $("#ModalEspecialidad #slct_curso_id").html(html); 
    $("#ModalEspecialidad #slct_curso_id").selectpicker('refresh');

};

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableEspecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>";
   
            html+="</td>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
            "<td class='codigo_inicio'>"+r.codigo_inicio+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='horario'>"+r.horario+"</td>"+
            "<td class='cronograma'><ol><li>"+$.trim(r.cronograma).split(",").join("</li><li>")+"</li></ol></td>"+
            "<td>"+
            "<input type='hidden' class='especialidad_id' value='"+r.especialidad_id+"'>";

        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });//FIN FUNCTION

    $("#TableEspecialidad tbody").html(html); 
    $("#TableEspecialidad").DataTable({ //INICIO DATATABLE
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
            $('#TableEspecialidad_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEspecialidad','AjaxEspecialidadProgramacion',result.data,'#TableEspecialidad_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
