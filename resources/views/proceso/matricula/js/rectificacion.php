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
CambiarEstado=function(estado,id){
    AjaxEspecialidad.CambiarEstado(HTMLCambiarEstado,estado,id);
}
*/
/*
HTMLCambiarNotaCurso=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.Cargar(HTMLCargar);
    }
}
*/
AnularMatri=function(alumno_id, id_matri){
    window.alumno_id = alumno_id;
    window.id_matri = id_matri;
    AjaxEspecialidad.anularMatricula(HTMLAnularMatri, id_matri);
    $("#div_tabla2_deta").slideUp();
    $("#tb_tabla2_deta tbody").html(''); 
}
HTMLAnularMatri=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.verMatriculas(HTMLCargaMatri, alumno_id);
    }
}


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

        html+="<tr id='trid_"+r.id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='materno'>"+r.email+"</td>"+
                "<td class='materno'>"+r.telefono+"</td>"+
                "<td class='materno'>"+r.celular+"</td>"+
                "<td class='materno'>"+r.direccion+"</td>";

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+=""+
                //'<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
                '<td><a id="btn_'+r.id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verMatriculas(HTMLCargaMatri, '+r.id+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
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
HTMLCargaMatri=function(result){ //INICIO HTML
    var html="";

    $.each(result.data.data,function(index, r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"'>";
        html+=""+
                "<td class=''>"+r.id+"</td>"+
                "<td class=''>"+r.tipo_participante+"</td>"+
                "<td class=''>"+r.ode+"</td>"+
                "<td class=''>"+r.paterno+"</td>"+
                "<td class=''>"+r.materno+"</td>"+
                "<td class=''>"+r.nombre+"</td>"+
                "<td class=''>"+r.fecha_matricula+"</td>"+

                '<td><a id="btnvermatri_'+r.id+'" class="btn btn-primary btn-sm" onClick="CargaMatriDeta('+r.id+')"><i class="glyphicon glyphicon-sort-by-attributes-alt"></i> </a>&nbsp;&nbsp;'+
                '<a id="btnanular_'+r.id+'" class="btn btn-danger btn-sm" onClick="AnularMatri('+r.alumno_id+', '+r.id+')"><i class="glyphicon glyphicon-ban-circle"></i> </a></td>';

        html+="</tr>";
    });//FIN FUNCTION

    $("#tb_tabla2 tbody").html(html); 
};

CargaMatriDeta=function(id_matri){
    AjaxEspecialidad.verMatriDeta(HTMLCargaMatriDeta, id_matri);
};

HTMLCargaMatriDeta=function(result){ //INICIO HTML
    var html="";

    $.each(result.data.data,function(index, r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"'>";
        html+=""+
                "<td class=''>"+r.id+"</td>"+
                "<td class=''>"+r.curso+"</td>"+
                "<td class=''>"+r.docente+"</td>"+
                "<td class=''>"+r.dia+"</td>"+
                "<td class=''>"+r.fecha_inicio+"</td>"+
                "<td class=''>"+r.fecha_final+"</td>"+
                //'<td><a id="btnv_'+r.id+'" class="btn btn-primary btn-sm" onClick="AjaxEspecialidad.verMatriDeta(HTMLCargaMatriDeta, '+r.id+')"><i class="glyphicon glyphicon-sort-by-attributes-alt"></i> </a></td>';
                '<td><button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1|tipo_curso:'+r.tipo_curso+'|id_matri:'+r.id_matri+'|id_matri_deta:'+r.id+'"><i class="glyphicon glyphicon-refresh"></i> Programación</button>';

        html+="</tr>";
    });//FIN FUNCTION

    $("#tb_tabla2_deta tbody").html(html);
};


btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
    $("#div_tabla2_deta").slideUp();
}

/*
btnguardar_curso = function(){
    AjaxEspecialidad.guardarNotasProg(HTMLCambiarNotaCurso);
}
*/
// --



</script>