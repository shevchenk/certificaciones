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
    
    $('#ModalArchivo').css('z-index', 1050);
    AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalArchivo').on('shown.bs.modal', function (event) {
        
    });

    $('#ModalArchivo').on('hidden.bs.modal', function (event) {
        $("#ModalArchivoForm input[type='hidden']").not('.mant').remove();
        $("#ModalArchivoForm input[type='text'],#ModalArchivoForm input[type='hidden']").val('');
        $("#ModalArchivoForm #txt_situaciones,#ModalArchivoForm #txt_expositor").val('');
        $("#ModalArchivoForm .slct_grabo,#ModalArchivoForm .slct_publico").removeAttr('checked');
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
            "<td><input type='hidden' class='diapo_archivo' value='"+r.diapo_archivo+"'>"+
                "<input type='hidden' class='cv_archivo' value='"+r.cv_archivo+"'>"+
                "<input type='hidden' class='grabo' value='"+r.grabo+"'>"+
                "<input type='hidden' class='publico' value='"+r.publico+"'>"+
                "<input type='hidden' class='expositor' value='"+$.trim(r.expositor)+"'>"+
                "<input type='hidden' class='situaciones' value='"+$.trim(r.situaciones)+"'>"+
                "<input type='hidden' class='diapoedit_archivo' value='"+r.diapoedit_archivo+"'>"+
                "<input type='hidden' class='temario_archivo' value='"+r.temario_archivo+"'>";
        html+='<a class="btn btn-primary btn-sm" onClick="SubirArchivos('+r.id+')"><i class="fa fa-video-camera fa-lg"></i></a></td>';
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
    cv_archivo=$("#trid_"+id+" .cv_archivo").val();
    temario_archivo=$("#trid_"+id+" .temario_archivo").val();
    diapo_archivo=$("#trid_"+id+" .diapo_archivo").val();
    diapoedit_archivo=$("#trid_"+id+" .diapoedit_archivo").val();
    expositor=$("#trid_"+id+" .expositor").val();
    situaciones=$("#trid_"+id+" .situaciones").val();
    grabo=$("#trid_"+id+" .grabo").val();
    publico=$("#trid_"+id+" .publico").val();

    masterG.SelectImagen(cv_archivo,'#cv_img','#cv_href');
    masterG.SelectImagen(temario_archivo,'#temario_img','#temario_href');
    masterG.SelectImagen(diapo_archivo,'#diapo_img','#diapo_href');
    masterG.SelectImagen(diapoedit_archivo,'#diapoedit_img','#diapoedit_href');

    $("#ModalArchivoForm #txt_seminario").val( seminario );
    $("#ModalArchivoForm #txt_fecha_seminario").val( fecha_seminario );
    $("#ModalArchivoForm #txt_id").val( id );
    $("#ModalArchivoForm #txt_docente").val( docente );
    $("#ModalArchivoForm #txt_cv_nombre").val( cv_archivo );
    $("#ModalArchivoForm #txt_temario_nombre").val( temario_archivo );
    $("#ModalArchivoForm #txt_diapo_nombre").val( diapo_archivo );
    $("#ModalArchivoForm #txt_diapoedit_nombre").val( diapoedit_archivo );
    $("#ModalArchivoForm #txt_expositor").val( expositor );
    $("#ModalArchivoForm #txt_situaciones").val( situaciones );
    if(grabo>=0){
        $("#ModalArchivoForm .slct_grabo:eq("+grabo+")").prop('checked','true');
    }
    if(publico>=0){
        $("#ModalArchivoForm .slct_publico:eq("+publico+")").prop( 'checked','true' );
    }
    $('#ModalArchivo').modal('show');
}

ValidaForm=function(){
    var r=true;
    if( $('.slct_grabo').is(':checked')==false ){
        r=false;
        msjG.mensaje('warning','El Docente Grabó?',5000);
    }
    else if( $('.slct_publico').is(':checked')==false ){
        r=false;
        msjG.mensaje('warning','Se Publicó la Grabación?',5000);
    }
    else if( $.trim($('#txt_expositor').val())=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Características del Expositor',5000);
    }
    else if( $.trim($('#txt_situaciones').val())=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Ocasiones Ocurridas',5000);
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
