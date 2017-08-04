<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CargoG={id:0,cargo:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCargo").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
    AjaxCargo.Cargar(HTMLCargarCargo);

    $('#ModalCargo').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalCargoForm").append("<input type='hidden' value='"+CargoG.id+"' name='id'>");
        }

        $('#ModalCargoForm #txt_cargo').val( CargoG.cargo );
        $('#ModalCargoForm #slct_estado').val( CargoG.estado );
        $("#ModalCargoForm .selectpicker").selectpicker('refresh');
        $('#ModalCargoForm #txt_cargo').focus();
    });

    $('#ModalCargo').on('hidden.bs.modal', function (event) {
        $("#ModalCargoForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalCargoForm #txt_cargo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Cargo',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    CargoG.id='';
    CargoG.cargo='';
    CargoG.estado='1';
    if( val==0 ){
        CargoG.id=id;
        CargoG.cargo=$("#TableCargo #trid_"+id+" .cargo").text();
        CargoG.estado=$("#TableCargo #trid_"+id+" .estado").val();
    }
    $('#ModalCargo').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxCargo.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCargo.Cargar(HTMLCargarCargo);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxCargo.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCargo').modal('hide');
        AjaxCargo.Cargar(HTMLCargarCargo);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarCargo=function(result){
    var html="";
    $('#TableCargo').DataTable().destroy();

    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='cargo'>"+r.cargo+"</td>"+
            "<td><input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableCargo tbody").html(html); 
    $("#TableCargo").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>

