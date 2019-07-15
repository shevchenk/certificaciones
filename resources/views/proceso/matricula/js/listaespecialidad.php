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
          if( $("#ModalMatriculaForm #txt_persona_id").val()=='' ){
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

    $("#TableListaprogramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });

    $('#ModalListaprogramacion').on('shown.bs.modal', function (event) {
          var button = $(event.relatedTarget); // captura al boton
          $("#ModalListaprogramacion #slct_tipo_modalidad_id").selectpicker('val','0');
          $("#ModalListaprogramacion #slct_tipo_modalidad_id").change();
          $( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).change(function() {
             
                  bfiltros= button.data('filtros');
                    if( typeof (bfiltros)!='undefined'){
                        LDfiltrosG=bfiltros+'|tipo_modalidad:'+$( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).val();
                    }
                    if( typeof (button.data('tipotabla'))!='undefined'){
                        LDTipoTabla=button.data('tipotabla');
                    }
                  AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);
          });
    });
});

HTMLCargarEspecialidad=function(result){
    var html="";
    $('#TableListaespecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        html+="<tr id='trides_"+r.id+"'>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
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
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarEspecialidad('+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>';
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
    var html=''; var cursos='';
        $("#trides_"+id+" table>tbody>tr").each( function(){
          html+="<tr id='tres_"+id+"_"+$(this).find('td:eq(0) .curso_id').val()+"'>"+
            "<td><input type='text' class='form-control' value='"+$('#trides_'+id+' .especialidad').text()+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+$(this).find('td:eq(1)').text()+"' disabled></td>"+
            "<td><input type='hidden' class='form-control' id='txt_programacion_id' name='txt_programacion_id[]' value=''><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled></td>"+
            "<td><input type='text' class='form-control' value='' disabled>"+
                "<input type='hidden' class='form-control' name='txt_curso_id[]' value='"+$(this).find('td:eq(0) .curso_id').val()+"'>"+
                "<input type='hidden' class='form-control' id='txt_especialidad_id' name='txt_especialidad_id[]' value='"+id+"'>"+
            "</td>"+
            '<td><button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1|tipo_curso:1|curso_id:'+$(this).find('td:eq(0) .curso_id').val()+'" data-tipotabla="1">Seleccionar Programación</button></td>';
          html+="</tr>";

          cursos+="<li>"+$(this).find('td:eq(1)').text()+"</li>";
        })

        $("#promocion_seminario").html("<ol>"+cursos+"</ol>");
        $("#t_matricula").html(html);
        $("#ModalListaespecialidad").modal('hide');
}

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

    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(2)").val(id);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(3)").val(mod);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(4)").val(fecha_i[0]);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(5)").val(fecha_i[1]+"-"+fecha_f[1]+" | "+dia);
    $("#tres_"+EspecialidadIDG+"_"+curso_id+" input:eq(6)").val(sucursal);

    $("#ModalListaprogramacion").modal('hide');
}
</script>
