<script type="text/javascript">
$(document).ready(function() {
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
    
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalProgramacion').on('shown.bs.modal', function (event) {

    });

    $('#ModalProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
    });
    
});

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
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='fecha_final'>"+r.fecha_final+"</td>"+
            "<td>";
        html+='<a class="btn btn-primary btn-sm" onClick="SubirArchivos('+r.id+')"><i class="fa fa-upload fa-lg"></i></a></td>';
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

SubirArchivos=function(id){
    docente=$("#trid_"+id+" .persona").text();
    seminario=$("#trid_"+id+" .curso").text();
    fecha_seminario=$("#trid_"+id+" .fecha_inicio").text().split(' ')[0];


    $("#ModalArchivoForm #txt_seminario").val( seminario );
    $("#ModalArchivoForm #txt_fecha_seminario").val( fecha_seminario );
    $("#ModalArchivoForm #txt_id").val( id );
    $("#ModalArchivoForm #txt_docente").val( docente );
    $('#ModalArchivo').modal('show');
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalArchivoForm #txt_temario_nombre").val() )=='' && $.trim( $("#ModalArchivoForm #txt_cv_nombre").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione almenos 1 archivo para guardar',4000);
    }
    return r;
}

RegistrarArchivo=function(){
    if( ValidaForm() ){
        AjaxProgramacion.RegistrarArchivo(HTMLRegistrarArchivo);
    }
}

HTMLRegistrarArchivo=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalArchivo').modal('hide');
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
</script>
