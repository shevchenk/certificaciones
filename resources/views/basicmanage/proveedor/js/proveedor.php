<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProveedorG={id:0,
persona_id:0,
persona:"",
empresa:"",
empresa_id:0,
estado:1}; // Datos Globales
$(document).ready(function() {

      
    $("#TableProveedor").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    $('#ModalProveedor').css('z-index', 1050);
    $('#ModalListaempresa').css('z-index', 1050);
    $('#ModalEmpresa').css('z-index', 1060);
    $('#ModalListapersona').css('z-index', 1070);
    $('#ModalPersona').css('z-index', 1080);
    $('#ModalCategoria').css('z-index', 1090);
   // $('#ModalCategoria').css('z-index', 1090);

    AjaxProveedor.Cargar(HTMLCargarProveedor);
    
    $('#ModalProveedor').on('shown.bs.modal', function (event) {
        
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalProveedorForm").append("<input type='hidden' value='"+ProveedorG.id+"' name='id'>");
        }

        $('#ModalProveedorForm #txt_persona_id').val( ProveedorG.persona_id );
        $('#ModalProveedorForm #txt_persona').val( ProveedorG.persona );
        $('#ModalProveedorForm #txt_empresa_id').val( ProveedorG.empresa_id );
        $('#ModalProveedorForm #txt_empresa').val( ProveedorG.empresa );
        $('#ModalProveedorForm #slct_estado').val( ProveedorG.estado );
        $("#ModalProveedorForm select").selectpicker('refresh');
       // $('#ModalProveedorForm #txt_persona').focus();
    });

    $('#ModalProveedor').on('hidden.bs.modal', function (event) {
        $("#ModalProveedorForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalProveedorForm #txt_persona_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona o Empresa',4000);
    }
    return r;
}

AgregarEditar=function(val,id){

    AddEdit=val;
    ProveedorG.id='';
    ProveedorG.persona_id='';
    ProveedorG.persona='';
    ProveedorG.empresa='';
    ProveedorG.empresa_id='';
    ProveedorG.estado='1';
    $('.persona').css("display","none");
    $('.empresa').css("display","none");
    
    if( val==0 ){
        ProveedorG.id=id;
        ProveedorG.persona_id=$("#TableProveedor #trid_"+id+" .persona_id").val();
        ProveedorG.persona=$("#TableProveedor #trid_"+id+" .nombrecompleto").text();
        ProveedorG.empresa=$("#TableProveedor #trid_"+id+" .razon_social").text();
        ProveedorG.empresa_id=$("#TableProveedor #trid_"+id+" .empresa_id").val();
        ProveedorG.estado=$("#TableProveedor #trid_"+id+" .estado").val();
        if(ProveedorG.empresa_id!==''){
                $('.empresa').css("display","");
        }
        if(ProveedorG.persona_id!==''){
                $('.persona').css("display","");
        }
    }
    $('#ModalProveedor').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxProveedor.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProveedor.Cargar(HTMLCargarProveedor);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProveedor.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProveedor').modal('hide');
        AjaxProveedor.Cargar(HTMLCargarProveedor);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarProveedor=function(result){
    var html="";
    $('#TableProveedor').DataTable().destroy();

    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='nombrecompleto'>"+r.paterno+" "+r.materno+" "+r.nombre+"</td>"+
            "<td class='razon_social'>"+r.razon_social+"</td>"+
            "<td>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='empresa_id' value='"+r.empresa_id+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>'+

            '<td><a class="btn btn-primary btn-sm" onClick="Cargar('+r.id+')"><i class="fa fa-plus fa-lg"></i> </a></td>';



        html+="</tr>";
    });
    $("#TableProveedor tbody").html(html); 
    $("#TableProveedor").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};


Cargar=function(id){
     $("#ModalProveedorDetalleForm #txt_proveedor_id").val(id);
     $("#ProveedorDetalleForm #txt_proveedor_id").val(id);
    AjaxProveedorDetalle.Cargar(HTMLCargarProveedorDetalle);
    $("#ProveedorDetalleForm").css("display","");
     
};

</script>
