<script type="text/javascript">
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EspecialidadG={id:0,
especialidad:"",
certificado_especialidad:"",
curso_id:"",
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableEspecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    CargarSlct(3);
    AjaxEspecialidad.Cargar(HTMLCargarEspecialidad);
    $("#EspecialidadForm #TableEspecialidad select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargarEspecialidad); });
    $("#EspecialidadForm #TableEspecialidad input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargarEspecialidad); });

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

AgregarEditar=function(val,id){
    AddEdit=val;
    EspecialidadG.id='';
    EspecialidadG.especialidad='';
    EspecialidadG.certificado_especialidad='';
    EspecialidadG.curso_id='';
    EspecialidadG.estado='1';
    if( val==0 ){
        EspecialidadG.id=id;
        EspecialidadG.especialidad=$("#TableEspecialidad #trid_"+id+" .especialidad").text();
        EspecialidadG.certificado_especialidad=$("#TableEspecialidad #trid_"+id+" .certificado_especialidad").text();
        EspecialidadG.curso_id=$("#TableEspecialidad #trid_"+id+" .curso_id").val();

        EspecialidadG.estado=$("#TableEspecialidad #trid_"+id+" .estado").val();
    }
    $('#ModalEspecialidad').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxEspecialidad.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.Cargar(HTMLCargarEspecialidad);
    }
}

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
        AjaxEspecialidad.Cargar(HTMLCargarEspecialidad);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
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

HTMLCargarEspecialidad=function(result){ //INICIO HTML
    var html="";
    $('#TableEspecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>";
   
            html+="</td>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
            "<td class='certificado_especialidad'>"+r.certificado_especialidad+"</td>"+
            "<td class='cursos'>"+$.trim(r.cursos).split(",").join("<br>")+"</td>"+
            "<td>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";

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
            masterG.CargarPaginacion('HTMLCargarEspecialidad','AjaxEspecialidad',result.data,'#TableEspecialidad_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
