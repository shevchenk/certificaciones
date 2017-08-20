<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var CursoG={id:0,
curso:"",
certificado_curso:"",
tipo_curso:0,
estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableCurso").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxCurso.Cargar(HTMLCargarCurso);
    $("#CursoForm #TableCurso select").change(function(){ AjaxCurso.Cargar(HTMLCargarCurso); });
    $("#CursoForm #TableCurso input").blur(function(){ AjaxCurso.Cargar(HTMLCargarCurso); });

    $('#ModalCurso').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalCursoForm").append("<input type='hidden' value='"+CursoG.id+"' name='id'>");
        }

        $('#ModalCursoForm #txt_curso').val( CursoG.curso );
        $('#ModalCursoForm #txt_certificado_curso').val( CursoG.certificado_curso );
        $('#ModalCursoForm #slct_tipo_curso').val( CursoG.tipo_curso );
        $('#ModalCursoForm #slct_estado').val( CursoG.estado );
        $("#ModalCurso select").selectpicker('refresh');
        $('#ModalCursoForm #txt_curso').focus();
    });

    $('#ModalCurso').on('hidden.bs.modal', function (event) {
        $("ModalCursoForm input[type='hidden']").not('.mant').remove();

       // $("ModalCursoForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalCursoForm #txt_curso").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Curso',4000);
    }
    else if( $.trim( $("#ModalCursoForm #txt_certificado_curso").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Nombre del Certificado',4000);
    }
    else if( $.trim( $("#ModalCursoForm #slct_tipo_curso").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Tipo',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    CursoG.id='';
    CursoG.curso='';
    CursoG.certificado_curso='';
    CursoG.tipo_curso='0';
    CursoG.estado='1';
    if( val==0 ){
        CursoG.id=id;
        CursoG.curso=$("#TableCurso #trid_"+id+" .curso").text();
        CursoG.certificado_curso=$("#TableCurso #trid_"+id+" .certificado_curso").text();
        CursoG.tipo_curso=$("#TableCurso #trid_"+id+" .tipo_curso").val();
        CursoG.estado=$("#TableCurso #trid_"+id+" .estado").val();
    }
    $('#ModalCurso').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxCurso.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxCurso.Cargar(HTMLCargarCurso);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxCurso.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalCurso').modal('hide');
        AjaxCurso.Cargar(HTMLCargarCurso);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarCurso=function(result){ //INICIO HTML
    var html="";
    $('#TableCurso').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='certificado_curso'>"+r.certificado_curso+"</td>"+
            "<td class='tipo_cursoFORM'>"+r.tipo_curso+"</td>"+

            "<td>"+
            "<input type='hidden' class='tipo_curso' value='"+r.tipo_curso+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";

    });//FIN FUNCTION

    $("#TableCurso tbody").html(html); 
    $("#TableCurso").DataTable({ //INICIO DATATABLE
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
            $('#TableCurso_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarCurso','AjaxCurso',result.data,'#TableCurso_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

</script>
