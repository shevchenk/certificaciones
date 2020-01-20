<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var TrabajadorG={id:0,persona_id:0,trabajador:'',rol_id:0,codigo:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableTrabajador").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(1);
    AjaxTrabajador.Cargar(HTMLCargarTrabajador);
    $("#TrabajadorForm #TableTrabajador select").change(function(){ AjaxTrabajador.Cargar(HTMLCargarTrabajador); });
    $("#TrabajadorForm #TableTrabajador input").blur(function(){ AjaxTrabajador.Cargar(HTMLCargarTrabajador); });

    $('#ModalTrabajador').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalTrabajadorForm").append("<input type='hidden' value='"+TrabajadorG.id+"' name='id'>");
        }

            CargaTarea(TrabajadorG.rol_id);

        $('#ModalTrabajadorForm #txt_trabajador').val( TrabajadorG.trabajador );
        $('#ModalTrabajadorForm #txt_persona_id').val( TrabajadorG.persona_id );
        $('#ModalTrabajadorForm #slct_rol_id').selectpicker('val', TrabajadorG.rol_id );
        $('#ModalTrabajadorForm #slct_tarea_id').selectpicker('val', TrabajadorG.tarea_id );
        $('#ModalTrabajadorForm #txt_codigo').val( TrabajadorG.codigo );
        $('#ModalTrabajadorForm #slct_estado').selectpicker('val', TrabajadorG.estado );
        $('#ModalTrabajadorForm #txt_trabajador').focus();
    });

    $('#ModalTrabajador').on('hidden.bs.modal', function (event) {
        $("#ModalTrabajadorForm input[type='hidden']").not('.mant').remove();
       // $("ModalTrabajadorForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalTrabajadorForm #txt_persona_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Trabajador',4000);
    }
    else if( $.trim( $("#ModalTrabajadorForm #slct_rol_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Rol',4000);
    }
    else if( $.trim( $("#ModalTrabajadorForm #slct_tarea_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Tarea',4000);
    }
    /*
    else if( $.trim( $("#ModalTrabajadorForm #txt_codigo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Código',4000);
    }
    */

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    TrabajadorG.id='';
    TrabajadorG.persona_id='';
    TrabajadorG.trabajador='';
    TrabajadorG.rol_id='0';
    TrabajadorG.tarea_id='0';
    TrabajadorG.codigo='';
    TrabajadorG.estado='1';
    if( val==0 ){
        TrabajadorG.id=id;
        TrabajadorG.persona_id=$("#TableTrabajador #trid_"+id+" .persona_id").val();
        TrabajadorG.rol_id=$("#TableTrabajador #trid_"+id+" .rol_id").val();
        TrabajadorG.tarea_id=$("#TableTrabajador #trid_"+id+" .tarea_id").val();
        TrabajadorG.trabajador=$("#TableTrabajador #trid_"+id+" .trabajador").text();
        TrabajadorG.codigo=$("#TableTrabajador #trid_"+id+" .codigo").text();
        TrabajadorG.estado=$("#TableTrabajador #trid_"+id+" .estado").val();
    }
    $('#ModalTrabajador').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxTrabajador.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTrabajador.Cargar(HTMLCargarTrabajador);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxTrabajador.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTrabajador').modal('hide');
        AjaxTrabajador.Cargar(HTMLCargarTrabajador);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarTrabajador=function(result){
    var html="";
    $('#TableTrabajador').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='trabajador'>"+r.trabajador+"</td>"+
            "<td class='rol'>"+r.rol+"</td>"+
            "<td class='tarea'>"+r.tarea+"</td>"+
            "<td class='codigo'>"+r.codigo+"</td>"+
            "<td>"+
            "<input type='hidden' class='rol_id' value='"+r.rol_id+"'>"+
            "<input type='hidden' class='tarea_id' value='"+r.tarea_id+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableTrabajador tbody").html(html); 
    $("#TableTrabajador").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTrabajador": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTrabajador_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTrabajador','AjaxTrabajador',result.data,'#TableTrabajador_paginate');
        }
    });
};
CargarSlct=function(slct){
    if(slct==1){
    AjaxTrabajador.CargarRol(SlctCargarRol);
    }
}

CargaTarea=function(val){
    AjaxTrabajador.CargarTarea(SlctCargarTarea,val);
}

SlctCargarRol=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.rol+"</option>";
    });
    $("#ModalTrabajador #slct_rol_id").html(html); 
    $("#ModalTrabajador #slct_rol_id").selectpicker('refresh');

};
SlctCargarTarea=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        selected='';
        if( TrabajadorG.tarea_id*1>0 && TrabajadorG.tarea_id==r.id ){
            selected='selected';
        }
        html+="<option value='"+r.id+"' "+selected+">"+r.tarea+"</option>";
    });
    $("#ModalTrabajador #slct_tarea_id").html(html); 
    $("#ModalTrabajador #slct_tarea_id").selectpicker('refresh');

};
</script>
