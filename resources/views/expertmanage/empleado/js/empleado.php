<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var EmpleadoG={id:0,persona_id:0,persona:"",cargo_id:0,sucursal_id:0,fecha_inicio:"",fecha_final:"",estado:1}; // Datos Globales
$(document).ready(function() {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
      $('.selectpicker').selectpicker('mobile');
    }
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        autoclose: true,
        todayBtn: false
    });
    
    $("#TableEmpleado").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(1);
    CargarSlct(2);
    AjaxEmpleado.Cargar(HTMLCargarEmpleado);
    $("#EmpleadoForm #TableEmpleado select").change(function(){ AjaxEmpleado.Cargar(HTMLCargarEmpleado); });
    $("#EmpleadoForm #TableEmpleado input").blur(function(){ AjaxEmpleado.Cargar(HTMLCargarEmpleado); });
    
    $('#ModalEmpleado').on('shown.bs.modal', function (event) {
        
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEmpleadoForm").append("<input type='hidden' value='"+EmpleadoG.id+"' name='id'>");
        }

        $('#ModalEmpleadoForm #txt_persona_id').val( EmpleadoG.persona_id );
        $('#ModalEmpleadoForm #txt_persona').val( EmpleadoG.persona );
        $('#ModalEmpleadoForm #slct_sucursal').val( EmpleadoG.sucursal_id );
        $('#ModalEmpleadoForm #slct_cargo').val( EmpleadoG.cargo_id );
        $('#ModalEmpleadoForm #txt_fecha_inicio').val( EmpleadoG.fecha_inicio );
        $('#ModalEmpleadoForm #txt_fecha_final').val( EmpleadoG.fecha_final );
        $('#ModalEmpleadoForm #slct_estado').val( EmpleadoG.estado );
        $("#ModalEmpleadoForm select").selectpicker('refresh');
        $('#ModalEmpleadoForm #txt_persona').focus();
    });

    $('#ModalEmpleado').on('hidden.bs.modal', function (event) {
        $("#ModalEmpleadoForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEmpleadoForm #txt_persona_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
    else if( $.trim( $("#ModalEmpleadoForm #slct_cargo").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Cargo',4000);
    }
    else if( $.trim( $("#ModalEmpleadoForm #slct_sucursal").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Sucursal',4000);
    }
    return r;
}

AgregarEditar=function(val,id){

    AddEdit=val;
    EmpleadoG.id='';
    EmpleadoG.persona_id='';
    EmpleadoG.persona='';
    EmpleadoG.cargo_id='0';
    EmpleadoG.sucursal_id='0';
    EmpleadoG.fecha_inicio='';
    EmpleadoG.fecha_final='';
    EmpleadoG.estado='1';
    if( val==0 ){
        EmpleadoG.id=id;
        EmpleadoG.persona_id=$("#TableEmpleado #trid_"+id+" .persona_id").val();
        EmpleadoG.persona=$("#TableEmpleado #trid_"+id+" .persona").text();
        EmpleadoG.cargo_id=$("#TableEmpleado #trid_"+id+" .cargo_id").val();
        EmpleadoG.sucursal_id=$("#TableEmpleado #trid_"+id+" .sucursal_id").val();
        EmpleadoG.fecha_inicio=$("#TableEmpleado #trid_"+id+" .fecha_inicio").val();
        EmpleadoG.fecha_final=$("#TableEmpleado #trid_"+id+" .fecha_final").val();
        EmpleadoG.estado=$("#TableEmpleado #trid_"+id+" .estado").val();
    }
    $('#ModalEmpleado').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxEmpleado.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEmpleado.Cargar(HTMLCargarEmpleado);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEmpleado.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEmpleado').modal('hide');
        AjaxEmpleado.Cargar(HTMLCargarEmpleado);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

CargarSlct=function(slct){
    if(slct==1){
    AjaxEmpleado.CargarSucursal(SlctCargarSucursal);
    }
    if(slct==2){
    AjaxEmpleado.CargarCargo(SlctCargarCargo);
    }
}

SlctCargarSucursal=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalEmpleado #slct_sucursal").html(html); 
    $("#ModalEmpleado #slct_sucursal").selectpicker('refresh'); 

}

SlctCargarCargo=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.cargo+"</option>";
    });
    $("#ModalEmpleado #slct_cargo").html(html); 
    $("#ModalEmpleado #slct_cargo").selectpicker('refresh'); 

}

HTMLCargarEmpleado=function(result){
    var html="";
    $('#TableEmpleado').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.paterno+" "+r.materno+" "+r.nombre+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='cargo'>"+r.cargo+"</td>"+
            "<td>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='cargo_id' value='"+r.cargo_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='fecha_inicio' value='"+r.fecha_inicio+"'>"+
            "<input type='hidden' class='fecha_final' value='"+r.fecha_final+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableEmpleado tbody").html(html); 
    $("#TableEmpleado").DataTable({
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
            $('#TableEmpleado_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEmpleado','AjaxEmpleado',result.data,'#TableEmpleado_paginate');
        }
    });
};
</script>
