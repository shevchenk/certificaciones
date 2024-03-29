<script type="text/javascript">
let LlamadaG = {
    persona_id: '',
    persona: '',
    id: '',
}
$(document).ready(function() {
    $("#LlamadaForm #TableLlamada select").change(function(){ AjaxLlamada.HistorialLlamada(HTMLCargarListaLlamada); });
    $("#LlamadaForm #TableLlamada input").blur(function(){ AjaxLlamada.HistorialLlamada(HTMLCargarListaLlamada); });

    $('#ModalLlamada').on('shown.bs.modal', function (event) {
        
      var button = $(event.relatedTarget); // captura al boton
      $("#LlamadaForm #txt_dni").val( $("#ModalMatriculaForm #txt_dni").val() );
      
      LlamadaG.persona_id = $.trim( $( button.data().persona_id ).val() );
      LlamadaG.persona = $.trim( $( button.data().persona ).val() );
      LlamadaG.id = button.data().id;
      $(LlamadaG.id).val('');
      $("#ModalLlamada #trabajador").text(LlamadaG.persona);
        /*if( LlamadaG.persona == '' ){
          $('#ModalLlamada .modal-footer button').click();
          msjG.mensaje('warning','Busque y seleccione Persona Marketing',4000);
        }
        else{
        */
            AjaxLlamada.HistorialLlamada(HTMLCargarListaLlamada);
        //}

    });

    $('#ModalLlamada').on('hidden.bs.modal', function (event) {
        LlamadaG.persona_id = '';
        LlamadaG.persona = '';
        LlamadaG.id = '';
    });
});

SeleccionarLlamada = function(id){
    $(LlamadaG.id).val(id);
    $('#ModalLlamada').modal('hide');
};
 
    
    
HTMLCargarListaLlamada=function(result){
    var html="";
    $('#TableLlamada tbody').html('');
    $('#TableLlamada').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='ID'>"+r.id+"</td>"+
            "<td class='fecha_llamada'>"+r.fecha_llamada+"</td>"+
            "<td class='estado_llamada'>"+r.estado_llamada+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='fecha_programada'>"+$.trim(r.fecha_programada)+"</td>"+
            "<td class='comentario'>"+$.trim(r.comentario)+"</td>"+
            '<td><span class="btn btn-primary btn-sm" onClick="SeleccionarLlamada('+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "</td>";
        html+="</tr>";
    });
    $("#TableLlamada tbody").html(html); 
    $("#TableLlamada").DataTable({
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
            $('#TableLlamada_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarListaLlamada','AjaxLlamada.HistorialLlamada',result.data,'#TableLlamada_paginate');
        } 
    });
};
</script>
