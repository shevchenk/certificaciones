<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ProgramacionG={id:0,dia:"",persona_id:0,persona:"",docente_id:0,sucursal_id:"",
               curso_id:"",aula:"",fecha_inicio:"",fecha_final:"",fecha_campaña:"",
               meta_max:"",meta_min:"",estado:1}; // Datos Globales
$(document).ready(function() {
    $(".fechas").datetimepicker({
        format: "yyyy-mm-dd hh:ii:00",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:0,
        autoclose: true,
        todayBtn: false
    });

    $(".horas").datetimepicker({
        format: "hh:ii:00",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:0,
        startView:1,
        autoclose: true,
        todayBtn: false
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

    $("#TableProgramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $('#ModalProgramacion').css('z-index', 1050);
    $('#ModalListadocente').css('z-index', 1050);
    $('#ModalDocente').css('z-index', 1060);
    $('#ModalListapersona').css('z-index', 1070);
    $('#ModalPersona').css('z-index', 1080);
    
    CargarSlct(1);CargarSlct(3);
    AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    
    $("#ProgramacionForm #TableProgramacion select").change(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    $("#ProgramacionForm #TableProgramacion input").blur(function(){ AjaxProgramacion.Cargar(HTMLCargarProgramacion); });
    
    $('#ModalProgramacion').on('shown.bs.modal', function (event) {
        $('#ModalProgramacionForm .txt_link').css( "display","none" );
        $('#ModalProgramacionForm #slct_curso_id, #ModalProgramacionForm #slct_sucursal_id').removeAttr( 'disabled' );
        if( AddEdit==1 ){        
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalProgramacionForm").append("<input type='hidden' value='"+ProgramacionG.id+"' name='id'>");
            $('#ModalProgramacionForm #slct_curso_id, #ModalProgramacionForm #slct_sucursal_id').attr( 'disabled','true' );
        }
        var dia = ProgramacionG.dia.split(",");
        $('#ModalProgramacionForm #txt_docente').val( ProgramacionG.persona );
        $('#ModalProgramacionForm #txt_docente_id').val( ProgramacionG.docente_id );
        $('#ModalProgramacionForm #txt_persona_id').val( ProgramacionG.persona_id );
        $('#ModalProgramacionForm #slct_sucursal_id').selectpicker('val', ProgramacionG.sucursal_id );
        $('#ModalProgramacionForm #slct_curso_id').selectpicker( 'val',ProgramacionG.curso_id );
        ValidaCurso(ProgramacionG.curso_id);
        $('#ModalProgramacionForm #txt_aula').val( ProgramacionG.aula );
        $('#ModalProgramacionForm #slct_dia').selectpicker('val',dia);
        $('#ModalProgramacionForm #slct_turno').selectpicker('val',ProgramacionG.turno);
        $('#ModalProgramacionForm #txt_fecha_inicio').val( ProgramacionG.fecha_inicio.split(" ")[0] );
        $('#ModalProgramacionForm #txt_hora_inicio').val( ProgramacionG.fecha_inicio.split(" ")[1] );
        $('#ModalProgramacionForm #txt_fecha_final').val( ProgramacionG.fecha_final.split(" ")[0] );
        $('#ModalProgramacionForm #txt_hora_final').val( ProgramacionG.fecha_final.split(" ")[1] );
        $('#ModalProgramacionForm #txt_fecha_campaña').val( ProgramacionG.fecha_campaña );
        $('#ModalProgramacionForm #txt_meta_max').val( ProgramacionG.meta_max );
        $('#ModalProgramacionForm #txt_meta_min').val( ProgramacionG.meta_min );
        $('#ModalProgramacionForm #txt_costo').val( ProgramacionG.costo );
        $('#ModalProgramacionForm #txt_costo_ins').val( ProgramacionG.costo_ins );
        $('#ModalProgramacionForm #txt_costo_mat').val( ProgramacionG.costo_mat );
        $('#ModalProgramacionForm #slct_estado').selectpicker( 'val',ProgramacionG.estado );
        console.log(ProgramacionG.semestre);
        if( ProgramacionG.semestre!='' ){
            $('#ModalProgramacionForm #slct_anio').selectpicker( 'val',ProgramacionG.semestre.substr(0, 4) );
            $('#ModalProgramacionForm #slct_anio1').selectpicker( 'val',ProgramacionG.semestre.substr(5, 1) );
            $('#ModalProgramacionForm #slct_anio2').selectpicker( 'val',ProgramacionG.semestre.substr(7, 1) );
        }
        ValidaOde(ProgramacionG.sucursal_id);
        $('#ModalProgramacionForm #slct_docente_id').focus();
    });

    $('#ModalProgramacion').on('hidden.bs.modal', function (event) {
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
    });
    
});

ValidaOde=function(v){
    $("#ModalProgramacionForm .validaode").css("display",'block');
    if( v==1 ){
        $('#ModalProgramacionForm #slct_dia').selectpicker('val',['LU','MA','MI','JU','VI','SA','DO']);
        //$('#ModalProgramacionForm #txt_fecha_inicio').val( '2019-01-01 00:00:00' );
        $('#ModalProgramacionForm #txt_fecha_final').val( '2200-12-31' );
        $('#ModalProgramacionForm #txt_hora_inicio').val( '00:00:00' );
        $('#ModalProgramacionForm #txt_hora_final').val( '23:59:59' );
        $('#ModalProgramacionForm #txt_aula').val( 'Libre' );
        $("#ModalProgramacionForm .validaode").css("display",'none');
    }
}

ValidaCurso=function(v){
    //$("#ModalProgramacion #txt_costo").val("0");
    $("#ModalProgramacion #slct_anio,#ModalProgramacion #slct_anio1,#ModalProgramacion #slct_anio2").val("");
    $("#ModalProgramacion #slct_anio,#ModalProgramacion #slct_anio1,#ModalProgramacion #slct_anio2").selectpicker("refresh");

    $("#ModalProgramacionForm .validacosto").css("display",'none');
    $("#ModalProgramacionForm .validasemestre").css("display",'none');
    if( v!=0 ){
        $("#ModalProgramacionForm .validacosto").css("display",'block');
        $("#ModalProgramacionForm .validasemestre").css("display",'none');
        datatipo = $("#ModalProgramacion #slct_curso_id option:selected").attr("data-tipo");
        if( datatipo==1 ){
            $("#ModalProgramacionForm .validasemestre").css("display",'block');
            $("#ModalProgramacionForm .validacosto").css("display",'none');
        }
    }
}

ValidaForm=function(){
    var r=true;
    var tipo = $("#ModalProgramacion #slct_curso_id option:selected").attr("data-tipo");
    if( $.trim( $("#ModalProgramacionForm #slct_curso_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Curso',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #slct_sucursal_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione ODE',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_docente_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Docente',4000);
    }
    /*
    else if( $.trim( $("#ModalProgramacionForm #txt_aula").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Aula',4000);
    }
    */
    else if( $.trim( $("#ModalProgramacionForm #slct_dia").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Frencuencia',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_hora_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Hora de Inicio',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_hora_final").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Hora Final',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_fecha_inicio").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha de Inicio',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_fecha_final").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha Final',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_fecha_campaña").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Fecha de Campaña',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_costo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Costo',4000);
    }
    else if( tipo==1 && ($.trim( $("#ModalProgramacionForm #slct_anio").val() )=='' || $.trim( $("#ModalProgramacionForm #slct_anio1").val() )=='' || $.trim( $("#ModalProgramacionForm #slct_anio2").val() )=='') ){
        r=false;
        msjG.mensaje('warning','Seleccione todos los datos del semestre',4000);
    }
    /*else if( $.trim( $("#ModalProgramacionForm #txt_costo_ins").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Costo Inscripción',4000);
    }
    else if( $.trim( $("#ModalProgramacionForm #txt_costo_mat").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Costo Matrícula',4000);
    }*/
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    ProgramacionG.id='';
    ProgramacionG.persona='';
    ProgramacionG.persona_id='';
    ProgramacionG.docente_id='';
    ProgramacionG.sucursal_id='0';
    ProgramacionG.curso_id='0';
    ProgramacionG.aula='';
    ProgramacionG.dia='';
    ProgramacionG.turno='';
    ProgramacionG.fecha_inicio='';
    ProgramacionG.fecha_final='';
    ProgramacionG.fecha_campaña='';
    ProgramacionG.meta_max='';
    ProgramacionG.meta_min='';
    ProgramacionG.semestre='';
    ProgramacionG.costo='0';
    ProgramacionG.costo_ins='0';
    ProgramacionG.costo_mat='0';
    ProgramacionG.estado='1';
    if( val==0 ){
        ProgramacionG.id=id;
        ProgramacionG.docente_id=$("#TableProgramacion #trid_"+id+" .docente_id").val();
        ProgramacionG.persona_id=$("#TableProgramacion #trid_"+id+" .persona_id").val();
        ProgramacionG.persona=$("#TableProgramacion #trid_"+id+" .persona").text();
        ProgramacionG.sucursal_id=$("#TableProgramacion #trid_"+id+" .sucursal_id").val();
        ProgramacionG.curso_id=$("#TableProgramacion #trid_"+id+" .curso_id").val();
        ProgramacionG.aula=$("#TableProgramacion #trid_"+id+" .aula").text();
        ProgramacionG.dia=$("#TableProgramacion #trid_"+id+" .dia").val();
        ProgramacionG.turno=$("#TableProgramacion #trid_"+id+" .turno").val();
        ProgramacionG.fecha_inicio=$("#TableProgramacion #trid_"+id+" .fecha_inicio").val();
        ProgramacionG.fecha_final=$("#TableProgramacion #trid_"+id+" .fecha_final").val();
        ProgramacionG.fecha_campaña=$("#TableProgramacion #trid_"+id+" .fecha_campaña").text();
        ProgramacionG.meta_max=$("#TableProgramacion #trid_"+id+" .meta_max").val();
        ProgramacionG.meta_min=$("#TableProgramacion #trid_"+id+" .meta_min").val();
        ProgramacionG.costo=$("#TableProgramacion #trid_"+id+" .costo").val();
        ProgramacionG.semestre=$("#TableProgramacion #trid_"+id+" .semestre").val();
        /*ProgramacionG.costo_ins=$("#TableProgramacion #trid_"+id+" .costo_ins").val();
        ProgramacionG.costo_mat=$("#TableProgramacion #trid_"+id+" .costo_mat").val();*/
        ProgramacionG.estado=$("#TableProgramacion #trid_"+id+" .estado").val();
    }
    $('#ModalProgramacion').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxProgramacion.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxProgramacion.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalProgramacion').modal('hide');
        AjaxProgramacion.Cargar(HTMLCargarProgramacion);
    }else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableProgramacion').DataTable().destroy();
    
    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }
        tipo_inicio_curso = " - Curso";
        if( r.tipo_inicio_curso==1 ){
            tipo_inicio_curso = " - Inicio";
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='curso'>"+r.curso+tipo_inicio_curso+"</td>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='aula'>"+r.aula+"</td>"+
            "<td class='turnos'>"+r.turno+"</td>"+
            "<td class='dias'>"+r.dia+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[0]+"</td>"+
            "<td>"+r.fecha_inicio.split(" ")[1]+"</td>"+
            "<td>"+r.fecha_final.split(" ")[1]+"</td>"+
            "<td>"+r.semestre+"</td>"+
            "<td class='fecha_campaña'>"+r.fecha_campaña+"</td>"+
            "<td>"+
            "<input type='hidden' class='meta_max' value='"+r.meta_max+"'>"+
            "<input type='hidden' class='meta_min' value='"+r.meta_min+"'>"+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='turno' value='"+r.turno+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='costo' value='"+r.costo+"'>"+
            "<input type='hidden' class='costo_ins' value='"+r.costo_ins+"'>"+
            "<input type='hidden' class='costo_mat' value='"+r.costo_mat+"'>"+
            "<input type='hidden' class='fecha_inicio' value='"+r.fecha_inicio+"'>"+
            "<input type='hidden' class='fecha_final' value='"+r.fecha_final+"'>"+
            "<input type='hidden' class='semestre' value='"+r.semestre+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
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

CargarSlct=function(slct){
    if(slct==1){
    AjaxProgramacion.CargarSucursal(SlctCargarSucursal1);
    }
    if(slct==3){
    AjaxProgramacion.CargarCurso(SlctCargarCurso);
    }
    if(slct==2){
    AjaxAEPersona.CargarPrivilegio(SlctCargarPrivilegio);
    }
}

SlctCargarSucursal1=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#ModalProgramacion #slct_sucursal_id").html(html); 
    $("#ModalProgramacion #slct_sucursal_id").selectpicker('refresh');

};

SlctCargarCurso=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        tipo_inicio_curso = " - Curso";
        if( r.tipo_inicio_curso==1 ){
            tipo_inicio_curso = " - Inicio";
        }
        html+="<option data-tipo='"+r.tipo_inicio_curso+"' value="+r.id+">"+r.curso+tipo_inicio_curso+"</option>";
    });
    $("#ModalProgramacion #slct_curso_id").html(html); 
    $("#ModalProgramacion #slct_curso_id").selectpicker('refresh');

};
</script>
