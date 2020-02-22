<script type="text/javascript">
var fechaCronogramaG=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EPG={id:0,
especialidad:"",
estado:1}; // Datos Globales
var hoyG='<?php echo date("Y-m-d"); ?>'
$(document).ready(function() {
    $("#subtitulo,#subtitulo2").text( $("#EmpresaUsuarioGlobal option:selected").text() );
    $("#TableEspecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    $("#txt_fecha_cronograma").val(hoyG);

    CargarSlct(1);
    CargarSlct(2);
    AjaxEspecialidadProgramacion.Cargar(HTMLCargar);
    $("#EspecialidadProgramacionForm #TableEspecialidad select").change(function(){ AjaxEspecialidadProgramacion.Cargar(HTMLCargar); });
    $("#EspecialidadProgramacionForm #TableEspecialidad input").blur(function(){ AjaxEspecialidadProgramacion.Cargar(HTMLCargar); });

    $('#ModalEspecialidadProgramacion').on('shown.bs.modal', function (event) {
        $('#sortable').html('');
        fechaCronogramaG=[];
        $("#ModalEspecialidadProgramacionForm #txt_fecha_cronograma").val(hoyG);
        $('#ModalEspecialidadProgramacionForm #slct_tipo').selectpicker( 'val',EPG.tipo );
        $('#ModalEspecialidadProgramacionForm #txt_fecha_inicio').val( EPG.fecha_inicio );
        $('#ModalEspecialidadProgramacionForm #slct_especialidad_id').selectpicker( 'val',EPG.especialidad_id );
        $('#ModalEspecialidadProgramacionForm #slct_sucursal_id').selectpicker( 'val',EPG.sucursal_id.split(',') );
        $('#ModalEspecialidadProgramacionForm #slct_nro_cuota').selectpicker( 'val',EPG.nro_cuota.split('-')[0] );
        $('#ModalEspecialidadProgramacionForm #txt_monto_cuota').val( EPG.nro_cuota.split('-')[1] );
        $('#ModalEspecialidadProgramacionForm #slct_estado').selectpicker( 'val',EPG.estado );
        $('#ModalEspecialidadProgramacionForm #txt_costo').val( EPG.costo );
        $('#ModalEspecialidadProgramacionForm #txt_costo_mat').val( EPG.costo_mat );
        $('#ModalEspecialidadProgramacionForm #txt_fecha_inicio').removeAttr( 'disabled' );
        $('#ModalEspecialidadProgramacionForm #slct_especialidad_id').removeAttr( 'disabled' );
        ValidaTipo(EPG.tipo);
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
            $('#ModalEspecialidadProgramacionForm #slct_especialidad_id').focus();
        }
        else{
            //$('#ModalEspecialidadProgramacionForm #txt_fecha_inicio').attr( 'disabled','true' );
            $('#ModalEspecialidadProgramacionForm #slct_especialidad_id').attr( 'disabled','true' );
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEspecialidadProgramacionForm").append("<input type='hidden' value='"+EPG.id+"' name='id'>");
            AjaxEspecialidadProgramacion.CargarCronograma(HTMLCargarCronograma,EPG.id);
            $('#ModalEspecialidadProgramacionForm #txt_costo').focus();
        }
    });

    $('#ModalEspecialidadProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalEspecialidadProgramacionForm input[type='hidden']").not('.mant').remove();
       // $("ModalEspecialidadProgramacionForm input").val('');
    });
});

ValidaTipo=function(v){
    $("#ModalEspecialidadProgramacionForm .validatipo").css('display','block');
    if( v==2 ){
        $("#ModalEspecialidadProgramacionForm .validatipo").css('display','none');
    }
}

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEspecialidadProgramacionForm #slct_especialidad_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Especialidad',4000);
    }
    else if( $.trim( $("#ModalEspecialidadProgramacionForm #slct_tipo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Tipo de Programación',4000);
    }
    /*else if( $.trim( $("#ModalEspecialidadProgramacionForm #txt_fecha_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha de Inicio',4000);
    }*/
    else if( $.trim( $("#ModalEspecialidadProgramacionForm #txt_costo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Costo Inscripción',4000);
    }
    else if( $.trim( $("#ModalEspecialidadProgramacionForm #txt_costo_mat").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Costo Matrícula',4000);
    }
    else if( $("#ModalEspecialidadProgramacionForm #slct_tipo").val()==1 && $.trim( $("#ModalEspecialidadProgramacionForm #slct_nro_cuota").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Nro Cuota de la Escala',4000);
    }
    else if( $("#ModalEspecialidadProgramacionForm #slct_tipo").val()==1 && $.trim( $("#ModalEspecialidadProgramacionForm #txt_monto_cuota").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Monto Cuota de la Escala',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EPG.id='';
    EPG.especialidad_id='';
    EPG.fecha_inicio=hoyG;
    EPG.tipo='';
    EPG.sucursal_id='';
    EPG.nro_cuota='-';
    EPG.cronograma='';
    EPG.costo='0';
    EPG.costo_mat='0';
    EPG.estado='1';
    if( val==0 ){
        EPG.id=id;
        EPG.especialidad_id=$("#TableEspecialidad #trid_"+id+" .especialidad_id").val();
        EPG.sucursal_id=$("#TableEspecialidad #trid_"+id+" .sucursal_id").val();
        //EPG.fecha_inicio=$("#TableEspecialidad #trid_"+id+" .fecha_inicio").text();
        EPG.tipo=$("#TableEspecialidad #trid_"+id+" .tipo").val();
        EPG.nro_cuota=$("#TableEspecialidad #trid_"+id+" .nro_cuota").val();
        EPG.cronograma=$("#TableEspecialidad #trid_"+id+" .cronograma").text();
        EPG.costo=$("#TableEspecialidad #trid_"+id+" .costo").val();
        EPG.costo_mat=$("#TableEspecialidad #trid_"+id+" .costo_mat").val();
        EPG.estado=$("#TableEspecialidad #trid_"+id+" .estado").val();
    }
    $('#ModalEspecialidadProgramacion').modal('show');
}

HTMLCargarCronograma=function(result){
    $.each(result.data,function(index,r){
        $('#txt_fecha_cronograma').val(r.fecha_cronograma);
        $('#txt_monto_cronograma').val(r.monto_cronograma);
        CargarCronograma();
    });
    $('#slct_curso_id').val('');
}

CargarCronograma=function(){
    fecha_cronograma = $('#txt_fecha_cronograma').val();
    monto_cronograma = $('#txt_monto_cronograma').val();
    if( $.trim( $('#sortable #trid_'+fecha_cronograma).html() ) == '' ){
        fechaCronogramaG.push(fecha_cronograma+"|"+monto_cronograma);
        fechaCronogramaG.sort();
        $('#sortable').html('');
        for (i=0; i<fechaCronogramaG.length; i++) {
            $('#sortable').append('<tr id="trid_'+fechaCronogramaG[i].split("|")[0]+'"><td>N° '+(i+1)+'</td><td><input type="hidden" name="txt_fecha_cronograma[]" value="'+fechaCronogramaG[i].split("|")[0]+'">'+fechaCronogramaG[i].split("|")[0]+'</td><td><input type="text" name="txt_monto_cronograma[]" value="'+fechaCronogramaG[i].split("|")[1]+'"></td><td><a onClick="EliminarTr(\''+fechaCronogramaG[i]+'\');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td></tr>');
        }
    }
    else{
        msjG.mensaje('warning','La fecha del cronograma seleccionado ya fue agregado',4000);
    }
}

EliminarTr=function(t){
    fechaCronogramaG.splice(fechaCronogramaG.indexOf(t),1);
    $('#sortable').html('');
    for (i=0; i<fechaCronogramaG.length; i++) {
        $('#sortable').append('<tr id="trid_'+fechaCronogramaG[i].split("|")[0]+'"><td>N° '+(i+1)+'</td><td><input type="hidden" name="txt_fecha_cronograma[]" value="'+fechaCronogramaG[i].split("|")[0]+'">'+fechaCronogramaG[i].split("|")[0]+'</td><td><input type="text" name="txt_monto_cronograma[]" value="'+fechaCronogramaG[i].split("|")[1]+'"></td><td><a onClick="EliminarTr(\''+fechaCronogramaG[i]+'\');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td></tr>');
    }
}

CambiarEstado=function(estado,id){
    AjaxEspecialidadProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidadProgramacion.Cargar(HTMLCargar);
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
        fechaCronogramaG=[];
        $('#ModalEspecialidadProgramacion').modal('hide');
        AjaxEspecialidadProgramacion.Cargar(HTMLCargar);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

CargarSlct=function(t){
    if(t==1){
        AjaxEspecialidadProgramacion.CargarEspecialidad(HTMLCargarEspecialidad);
    }
    else if(t==2){
        AjaxEspecialidadProgramacion.CargarOde(HTMLCargarOde);
    }
}

HTMLCargarEspecialidad=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.especialidad+"</option>";
    });
    $("#ModalEspecialidadProgramacion #slct_especialidad_id").html(html); 
    $("#ModalEspecialidadProgramacion #slct_especialidad_id").selectpicker('refresh');
};

HTMLCargarOde=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalEspecialidadProgramacion #slct_sucursal_id").html(html); 
    $("#ModalEspecialidadProgramacion #slct_sucursal_id").selectpicker('refresh');

};

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableEspecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        tipo='Pago en Cuota(s)';
        cronograma="<ol><li>C - "+$.trim(r.fecha_cronograma).split(",").join("</li><li>C - ")+"</li></ol>";
        nro_cuota=r.nro_cuota;
        if( r.tipo==2 ){
            tipo='Pago por Curso';
            cronograma='';
            nro_cuota='';
        }

        html+="<tr id='trid_"+r.id+"'>";
   
            html+="</td>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
            "<td>"+ tipo +"</td>"+
            "<td class='sucursal'><ul><li>"+$.trim(r.sucursal).split("|").join("</li><li>")+"</li></ul></td>"+
            //"<td class='codigo_inicio'>"+r.codigo_inicio+"</td>"+
            "<td>"+nro_cuota+"</td>"+
            "<td>"+cronograma+"</td>"+
            "<td>"+
            "<input type='hidden' class='especialidad_id' value='"+r.especialidad_id+"'>"+
            "<input type='hidden' class='tipo' value='"+r.tipo+"'>"+
            "<input type='hidden' class='nro_cuota' value='"+r.nro_cuota+"'>"+
            "<input type='hidden' class='costo' value='"+r.costo+"'>"+
            "<input type='hidden' class='costo_mat' value='"+r.costo_mat+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>";

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
            masterG.CargarPaginacion('HTMLCargar','AjaxEspecialidadProgramacion',result.data,'#TableEspecialidad_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
