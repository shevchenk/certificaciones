<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ClienteG={id:0,persona_id:0,persona:"",empresa:"",empresa_id:0,estado:1}; // Datos Globales
$(document).ready(function() {
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        autoclose: true,
        todayBtn: false
    });
    
    $("#TableCliente").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    $('#ModalCliente').css('z-index', 1050);
    $('#ModalListaempresa').css('z-index', 1050);
    $('#ModalEmpresa').css('z-index', 1060);
    $('#ModalListapersona').css('z-index', 1070);
    $('#ModalPersona').css('z-index', 1080);


    AjaxCliente.Cargar(HTMLCargarCliente);

    $('#ModalCliente').on('shown.bs.modal', function (event) {
        
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalClienteForm").append("<input type='hidden' value='"+ClienteG.id+"' name='id'>");
        }

        $('#ModalClienteForm #txt_persona_id').val( ClienteG.persona_id );
        $('#ModalClienteForm #txt_persona').val( ClienteG.persona );
        $('#ModalClienteForm #txt_empresa_id').val( ClienteG.empresa_id );
        $('#ModalClienteForm #txt_empresa').val( ClienteG.empresa );
        $('#ModalClienteForm #slct_estado').val( ClienteG.estado );
        $("#ModalClienteForm select").selectpicker('refresh');
        $('#ModalClienteForm #txt_persona').focus();
    });

    $('#ModalCliente').on('hidden.bs.modal', function (event) {
        $("#ModalClienteForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalClienteForm #txt_persona_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona o Empresa',4000);
    }
    return r;
}

AgregarEditar=function(val,id){

    AddEdit=val;
    ClienteG.id='';
    ClienteG.persona_id='0';
    ClienteG.persona='';
    ClienteG.empresa='';
    ClienteG.empresa_id='0';
    ClienteG.estado='1';
    $('.persona').css("display","none");
    $('.empresa').css("display","none");
    
    if( val==0 ){
        ClienteG.id=id;
        ClienteG.persona_id=$("#TableCliente #trid_"+id+" .persona_id").val();
        ClienteG.persona=$("#TableCliente #trid_"+id+" .nombrecompleto").text();
        ClienteG.empresa=$("#TableCliente #trid_"+id+" .razon_social").text();
        ClienteG.empresa_id=$("#TableCliente #trid_"+id+" .empresa_id").val();
        ClienteG.estado=$("#TableCliente #trid_"+id+" .estado").val();
        if(ClienteG.empresa_id!==''){
                $('.empresa').css("display","");
        }
        if(ClienteG.persona_id!==''){
                $('.persona').css("display","");
        }
    }
    $('#ModalCliente').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxCliente.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCliente.Cargar(HTMLCargarCliente);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxCliente.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCliente').modal('hide');
        AjaxCliente.Cargar(HTMLCargarCliente);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}


HTMLCargarCliente=function(result){
    var html="";
    $('#TableCliente').DataTable().destroy();

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
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableCliente tbody").html(html); 
    $("#TableCliente").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
