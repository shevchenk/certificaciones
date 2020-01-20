<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var TareaG={id:0,tarea:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableTarea").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxTarea.CargarRol(SlctCargarRol);
    AjaxTarea.Cargar(HTMLCargarTarea);
    $("#TareaForm #TableTarea select").change(function(){ AjaxTarea.Cargar(HTMLCargarTarea); });
    $("#TareaForm #TableTarea input").blur(function(){ AjaxTarea.Cargar(HTMLCargarTarea); });

    $('#ModalTarea').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalTareaForm").append("<input type='hidden' value='"+TareaG.id+"' name='id'>");
        }

        $('#ModalTareaForm #txt_tarea').val( TareaG.tarea );
        $('#ModalTareaForm #slct_rol_id').selectpicker( 'val',TareaG.rol_id );
        
        $('#ModalTareaForm #slct_estado').selectpicker('val', TareaG.estado );
        $('#ModalTareaForm #txt_tarea').focus();
    });

    $('#ModalTarea').on('hidden.bs.modal', function (event) {
        $("#ModalTareaForm input[type='hidden']").not('.mant').remove();
       // $("ModalTareaForm input").val('');
    });
});



ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalTareaForm #txt_tarea").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tarea',4000);
    }
    else if( $.trim( $("#ModalTareaForm #slct_rol_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Rol',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    TareaG.id='';
    TareaG.tarea='';
    TareaG.rol_id='0';
    TareaG.estado='1';
    if( val==0 ){
        TareaG.id=id;
        TareaG.tarea=$("#TableTarea #trid_"+id+" .tarea").text();
        TareaG.estado=$("#TableTarea #trid_"+id+" .estado").val();
        TareaG.rol_id=$("#TableTarea #trid_"+id+" .rol_id").val();
    }
    $('#ModalTarea').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxTarea.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTarea.Cargar(HTMLCargarTarea);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxTarea.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTarea').modal('hide');
        AjaxTarea.Cargar(HTMLCargarTarea);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

Validacion=function(){
    msjG.mensaje('info','No se puede inhabilitar por ser parte de la función principal del software',6000)
}

HTMLCargarTarea=function(result){
    var html="";
    $('#TableTarea').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }
        if( r.id==1 || r.id==2 ){
            estadohtml='<span class="btn btn-info" onClick="Validacion()">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tarea'>"+r.tarea+"</td>"+
            "<td class='rol'>"+r.rol+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+
            "<input type='hidden' class='rol_id' value='"+r.rol_id+"'>"+
            estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableTarea tbody").html(html); 
    $("#TableTarea").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTarea": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTarea_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTarea','AjaxTarea',result.data,'#TableTarea_paginate');
        }
    });
};

</script>
