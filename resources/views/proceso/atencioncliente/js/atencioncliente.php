<script type="text/javascript">
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EspecialidadG={id:0,
    especialidad:"",
    certificado_especialidad:"",
    curso_id:"",
    estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableDatos").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(3);
    AjaxEspecialidad.Cargar(HTMLCargar);
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalEspecialidad').on('shown.bs.modal', function (event) {

        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEspecialidadForm").append("<input type='hidden' value='"+EspecialidadG.id+"' name='id'>");
        }
        
        $('#ModalEspecialidadForm #txt_especialidad').val( EspecialidadG.especialidad );
        $('#ModalEspecialidadForm #txt_certificado_especialidad').val( EspecialidadG.certificado_especialidad );
        var curso = EspecialidadG.curso_id.split(',');
        $('#ModalEspecialidadForm #slct_curso_id').selectpicker( 'val',curso );
        $('#ModalEspecialidadForm #slct_estado').selectpicker( 'val',EspecialidadG.estado );
        $('#ModalEspecialidadForm #txt_especialidad').focus();
    });

    $('#ModalEspecialidad').on('hidden.bs.modal', function (event) {
        $("ModalEspecialidadForm input[type='hidden']").not('.mant').remove();
       // $("ModalEspecialidadForm input").val('');
    });
});

/*
ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEspecialidadForm #txt_especialidad").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Especialidad',4000);
    }
    else if( $.trim( $("#ModalEspecialidadForm #txt_certificado_especialidad").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Nombre del Certificado',4000);
    }
    else if( $.trim( $("#ModalEspecialidadForm #slct_curso_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione almenos 1 Curso',4000);
    }

    return r;
}
*/

/*
AgregarEditar=function(val,id){
    AddEdit=val;
    EspecialidadG.id='';
    EspecialidadG.especialidad='';
    EspecialidadG.certificado_especialidad='';
    EspecialidadG.curso_id='';
    EspecialidadG.estado='1';
    if( val==0 ){
        EspecialidadG.id=id;
        EspecialidadG.especialidad=$("#TableDatos #trid_"+id+" .especialidad").text();
        EspecialidadG.certificado_especialidad=$("#TableDatos #trid_"+id+" .certificado_especialidad").text();
        EspecialidadG.curso_id=$("#TableDatos #trid_"+id+" .curso_id").val();

        EspecialidadG.estado=$("#TableDatos #trid_"+id+" .estado").val();
    }
    $('#ModalEspecialidad').modal('show');
}
*/

CambiarEstado=function(estado,id){
    AjaxEspecialidad.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarNotaCurso=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.Cargar(HTMLCargar);
    }
}

/*
AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEspecialidad.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        cursos_selec=[];
        $('#ModalEspecialidad').modal('hide');
        AjaxEspecialidad.Cargar(HTMLCargar);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
*/

CargarSlct=function(slct){

    if(slct==3){
    AjaxEspecialidad.CargarCurso(SlctCargarCurso);
    }
}

SlctCargarCurso=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.curso+"</option>";
    });
    $("#ModalEspecialidad #slct_curso_id").html(html); 
    $("#ModalEspecialidad #slct_curso_id").selectpicker('refresh');

};

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.persona_id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='email'>"+r.email+"</td>"+
                "<td class='telefono'>"+r.telefono+"</td>"+
                "<td class='celular'>"+r.celular+"</td>";
                //"<td>";
                //"<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+=""+
                //'<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
                '<td><a id="btn_'+r.persona_id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verCursos(HTMLCargaCurso, '+r.persona_id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
        html+="</tr>";
    });//FIN FUNCTION

    $("#TableDatos tbody").html(html); 
    $("#TableDatos").DataTable({ //INICIO DATATABLE
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
            $('#TableDatos_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargar','AjaxEspecialidad',result.data,'#TableDatos_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML


// PROCESOS NUEVOS
HTMLCargaCurso=function(result){ //INICIO HTML
    var html="";
    //$('#TableDatos').DataTable().destroy();

    $.each(result.data,function(index,r){
        html+="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.modalidad+"</td>";
        html+="<td class='curso'>"+r.curso+"</td>";
        html+="<td>"+r.profesor+"</td>";
        html+="<td class='fecha'>"+r.fecha+"</td>";
        html+="<td>"+r.horario+"</td>";
        html+="<td>"+r.sucursal+"</td>";

        html+="<td><a class='btn btn-lg btn-dropbox' href='ReportDinamic/Proceso.AlumnoPR@DescargarCertificado?id="+r.id+"' target='__blank'><i class='fa fa-download'></i></a></td>";
        html+="</tr>";
    });

    $("#tb_matricula").html(html); 
};

btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
}

btnguardar_curso = function(){
    AjaxEspecialidad.guardarNotasProg(HTMLCambiarNotaCurso);
}

DescargarCertificado=function(id){
    AjaxEspecialidad.DescargarCertificado();
}
// --



</script>