<script type="text/javascript">
var LDfiltrosG='';
var LDTipoTabla=0;
var EspecialidadIDG=0;
$(document).ready(function() {
    $("#TableListaespecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListaespecialidadForm #TableListaespecialidad input").blur(function(){ AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad); });

    $('#ModalListaespecialidad').on('shown.bs.modal', function (event) {
          var button = $(event.relatedTarget); // captura al boton
          if( $.trim($("#div_cursos_progra #txt_persona_id").val())=='' ){
            msjG.mensaje('warning','Seleccione Persona',4000);
            $("#ModalListaespecialidad").modal('hide');
          }
          else{
            AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad);
          }
    });

    $('#ModalListaespecialidad').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
    });

    /*$("#TableListaprogramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $("#ModalListaprogramacion #slct_tipo_modalidad_id").attr("onChange","ListaProgramacionModalidad()");
   
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });

    $('#ModalListaprogramacion').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget); // captura al boton
        var bfiltros= button.data('filtros');
        if( typeof (bfiltros)!='undefined'){
            LDfiltrosG=bfiltros+'|tipo_modalidad:'+$("#ModalListaprogramacion #slct_tipo_modalidad_id" ).val();
        }
        $("#ModalListaprogramacion #slct_tipo_modalidad_id").selectpicker('val','0');
        ListaProgramacionModalidad();
    });*/
});
/*
ListaProgramacionModalidad=function(){
    AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);
}
*/

HTMLCargarEspecialidad=function(result){
    var html="";
    $('#TableListaespecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        html+="<tr id='trides_"+r.id+"'>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+"<hr><ol><li>C - "+$.trim(r.cronograma).split("|").join("</li><li>C - ")+"</li></ol></td>"+
            "<td class='curso'>"+
                "<table class='table table-bordered table-hover'>"+
                    "<thead><tr>"+
                        "<th>N° Orden</th>"+
                        "<th>Curso</th>"+
                        "<th>N Veces</th>"+
                        "<th>Nota</th>"+
                    "</tr></thead>"+
                    "<tbdoy id='table_"+r.id+"'><tr>"+
                        "<td>"+$.trim(r.cursos).split('|').join('</td><td>').split("^^").join("</td></tr><tr><td>")+"</td>"+
                    "</tr></tbody>"+
                "</table>"+
            "</td>"+
            "<td>"+
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarEspecialidad(\''+r.id+'\')"+><i class="glyphicon glyphicon-ok"></i></span>';
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaespecialidad tbody").html(html); 
    $("#TableListaespecialidad").DataTable({
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
            $('#TableListaespecialidad_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEspecialidad','AjaxListaespecialidad',result.data,'#TableListaespecialidad_paginate');
        } 
    });
};

SeleccionarEspecialidad=function(id){
    EspecialidadIDG=id;
    sweetalertG.confirm("Confirmación!", "Desea cambiar de especialidad a:'"+$("#trides_"+id+" .especialidad").text()+"' ?", function(){
        AjaxListaespecialidad.CambiarEspecialidad(HTMLCambiarEspecialidad,id);
    });
}

HTMLCambiarEspecialidad=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $("#ModalListaespecialidad").modal('hide');
        AjaxEspecialidad.verMatriculas(HTMLCargaMatri, result.alumno_id, null);
        CargaMatriDeta(result.matricula_id);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
/*
HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableListaprogramacion').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        validasem="style='display:none;'";
        if(r.tipo_curso==1){
            validasem='';
        }
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='aula' "+validasem+">"+r.aula+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='fecha_final'>"+r.fecha_final+"</td>"+
            "<td>"+
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion('+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaprogramacion tbody").html(html); 
    $("#TableListaprogramacion").DataTable({
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
            $('#TableListaprogramacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxListaprogramacion',result.data,'#TableListaprogramacion_paginate');
        } 
    });
};

SeleccionarProgramacion=function(id){
    var mod='PRESENCIAL';
    var docente=$("#TableListaprogramacion #trid_"+id+" .docente").text();
    var persona_id=$("#TableListaprogramacion #trid_"+id+" .persona_id").val();
    var sucursal_id=$("#TableListaprogramacion #trid_"+id+" .sucursal_id").val();
    var sucursal=$("#TableListaprogramacion #trid_"+id+" .sucursal").text();
    var curso_id=$("#TableListaprogramacion #trid_"+id+" .curso_id").val();
    var dia=$("#TableListaprogramacion #trid_"+id+" .dia").val();
    var curso=$("#TableListaprogramacion #trid_"+id+" .curso").text();
    var fecha_inicio=$("#TableListaprogramacion #trid_"+id+" .fecha_inicio").text();
    var fecha_final=$("#TableListaprogramacion #trid_"+id+" .fecha_final").text();
    var fecha_i=fecha_inicio.split(" ");
    var fecha_f=fecha_final.split(" ");
    if(sucursal_id==1){
        var mod='ONLINE';
    }

    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(1)").val(id);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(2)").val(mod);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(3)").val(fecha_i[0]);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(4)").val(fecha_i[1]+"-"+fecha_f[1]+" | "+dia);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(5)").val(sucursal);

    $("#ModalListaprogramacion").modal('hide');
}*/
</script>
