<script type="text/javascript">
var LTtextoTrabajador='';
var LTtextoIdPersona='';
var LDfiltrosG='';
$(document).ready(function() {
    $("#TableListatrabajador").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListatrabajadorForm #TableListatrabajador select").change(function(){ AjaxListatrabajador.Cargar(HTMLCargarListaTrabajador); });
    $("#ListatrabajadorForm #TableListatrabajador input").blur(function(){ AjaxListatrabajador.Cargar(HTMLCargarListaTrabajador); });

    $('#ModalListatrabajador').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LDfiltrosG=bfiltros;
      }
      AjaxListatrabajador.Cargar(HTMLCargarListaTrabajador);

      LTtextoTrabajador= button.data('persona');
      LTtextoIdPersona= button.data('personaid');

    });

    $('#ModalListatrabajador').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
//        $("ModalDocenteForm input").val('');
    });
});

SeleccionarTrabajador = function(val,id){
    $("#"+LTtextoTrabajador).val('');
    $("#"+LTtextoIdPersona).val('');
    if( val==0 ){
        var trabajador=$("#TableListatrabajador #trid_"+id+" .trabajador").text();
        var codigo=$("#TableListatrabajador #trid_"+id+" .codigo").text();
        var tarea=$("#TableListatrabajador #trid_"+id+" .tarea").text();
        var persona_id=$("#TableListatrabajador #trid_"+id+" .persona_id").val();
        $("#"+LTtextoTrabajador).val(trabajador+' | '+tarea+' | '+codigo);
        $("#"+LTtextoIdPersona).val(persona_id);
        $('#ModalListatrabajador').modal('hide');
    }
    }
    
    
HTMLCargarListaTrabajador=function(result){
    var html="";
    $('#TableListatrabajador').DataTable().destroy();

    $.each(result.data.data,function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='trabajador'>"+r.trabajador+"</td>"+
            "<td class='rol'>"+r.rol+"</td>"+
            "<td class='tarea'>"+r.tarea+"</td>"+
            "<td class='codigo'>"+r.codigo+"</td>"+
           '<td><span class="btn btn-primary btn-sm" onClick="SeleccionarTrabajador(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "</td>";

        html+="</tr>";
    });
    $("#TableListatrabajador tbody").html(html); 
    $("#TableListatrabajador").DataTable({
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
            $('#TableListatrabajador_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarListaTrabajador','AjaxListatrabajador',result.data,'#TableListatrabajador_paginate');
        } 
    });
};
</script>
