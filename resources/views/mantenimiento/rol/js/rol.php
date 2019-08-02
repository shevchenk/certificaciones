<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var RolG={id:0,rol:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableRol").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxRol.Cargar(HTMLCargarRol);
    $("#RolForm #TableRol select").change(function(){ AjaxRol.Cargar(HTMLCargarRol); });
    $("#RolForm #TableRol input").blur(function(){ AjaxRol.Cargar(HTMLCargarRol); });

    $('#ModalRol').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalRolForm").append("<input type='hidden' value='"+RolG.id+"' name='id'>");
        }

        $('#ModalRolForm #txt_rol').val( RolG.rol );
        
        $('#ModalRolForm #slct_estado').selectpicker('val', RolG.estado );
        $('#ModalRolForm #txt_rol').focus();
    });

    $('#ModalRol').on('hidden.bs.modal', function (event) {
        $("#ModalRolForm input[type='hidden']").not('.mant').remove();
       // $("ModalRolForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalRolForm #txt_rol").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Rol',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    RolG.id='';
    RolG.rol='';
    RolG.estado='1';
    if( val==0 ){
        RolG.id=id;
        RolG.rol=$("#TableRol #trid_"+id+" .rol").text();
        RolG.estado=$("#TableRol #trid_"+id+" .estado").val();
    }
    $('#ModalRol').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxRol.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxRol.Cargar(HTMLCargarRol);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxRol.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalRol').modal('hide');
        AjaxRol.Cargar(HTMLCargarRol);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

Validacion=function(){
    msjG.mensaje('info','No se puede inhabilitar por ser parte de la función principal del software',6000)
}

HTMLCargarRol=function(result){
    var html="";
    $('#TableRol').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }
        if( r.id==1 || r.id==2 ){
            estadohtml='<span class="btn btn-info" onClick="Validacion()">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='rol'>"+r.rol+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableRol tbody").html(html); 
    $("#TableRol").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthRol": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableRol_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarRol','AjaxRol',result.data,'#TableRol_paginate');
        }
    });
};

</script>
