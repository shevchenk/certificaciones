<script type="text/javascript">
var LDtextoIdDocente='';
var LDtextoDocente='';
var LDtextoIdPersona='';
var LDfiltrosG='';
$(document).ready(function() {
    $("#TableListadocente").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListadocenteForm #TableListadocente select").change(function(){ AjaxListadocente.Cargar(HTMLCargarDocente); });
    $("#ListadocenteForm #TableListadocente input").blur(function(){ AjaxListadocente.Cargar(HTMLCargarDocente); });

    $('#ModalListadocente').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LDfiltrosG=bfiltros;
      }
      AjaxListadocente.Cargar(HTMLCargarDocente);

      LDtextoIdDocente= button.data('docenteid');
      LDtextoDocente= button.data('docente');
      LDtextoIdPersona= button.data('personaid');

    });

    $('#ModalListadocente').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
//        $("ModalDocenteForm input").val('');
    });
});

SeleccionarDocente = function(val,id){
    $("#"+LDtextoDocente).val('');
    $("#"+LDtextoIdDocente).val('');
    $("#"+LDtextoIdPersona).val('');
    if( val==0 ){
        var docente=$("#TableListadocente #trid_"+id+" .docente").text();
        var persona_id=$("#TableListadocente #trid_"+id+" .persona_id").val();
        $("#"+LDtextoDocente).val(docente);
        $("#"+LDtextoIdPersona).val(persona_id);
        $("#"+LDtextoIdDocente).val(id);
        $('#ModalListadocente').modal('hide');
    }
    }
    
    
HTMLCargarDocente=function(result){
    var html="";
    $('#TableListadocente').DataTable().destroy();

    $.each(result.data.data,function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='docente'>"+r.docente+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
           '<td><span class="btn btn-primary btn-sm" onClick="SeleccionarDocente(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar1(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';

        html+="</tr>";
    });
    $("#TableListadocente tbody").html(html); 
    $("#TableListadocente").DataTable({
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
            $('#TableListadocente_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarDocente','AjaxDocente',result.data,'#TableListadocente_paginate');
        } 
    });
};
</script>
