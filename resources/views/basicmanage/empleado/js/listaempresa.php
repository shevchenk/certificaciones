<script type="text/javascript">
var LEtextoIdEmpresa='';
var LEtextoEmpresa='';
var LEtextoIdPersona='';
var LEtextoPersona='';
var LEfiltrosG='';
$(document).ready(function() {
    $("#TableListaempresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $('#ModalListaempresa').on('shown.bs.modal', function (event) {
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LEfiltrosG=bfiltros;
      }
      AjaxListaempresa.Cargar(HTMLCargarEmpresa);

      LEtextoIdEmpresa= button.data('empresaid');
      LEtextoEmpresa= button.data('empresa');
      LEtextoIdPersona= button.data('personaid');
      LEtextoPersona= button.data('persona');

    });

    $('#ModalListaempresa').on('hidden.bs.modal', function (event) {
          LEfiltrosG='';
//        $("ModalEmpresaForm input[type='hidden']").remove();
    });
});

SeleccionarEmpresa = function(val,id){
        $("#"+LEtextoEmpresa).val('');
        $("#"+LEtextoIdEmpresa).val('');
        $("#"+LEtextoIdPersona).val('');
        $("#"+LEtextoPersona).val('');
    if( val==0 ){
        var nombrecompleto=$("#TableListaempresa #trid_"+id+" .nombrecompleto").text();
        var razon_social=$("#TableListaempresa #trid_"+id+" .razon_social").text();
         var persona_id=$("#TableListaempresa #trid_"+id+" .persona_id").val();
        $("#"+LEtextoEmpresa).val(razon_social);
        $("#"+LEtextoIdEmpresa).val(id);
        $("#"+LEtextoIdPersona).val(persona_id);
        $("#"+LEtextoPersona).val(nombrecompleto);
        $('.persona').css("display","");
        $('.empresa').css("display","");
        $('#ModalListaempresa').modal('hide');
    } 
    }
    
    
HTMLCargarEmpresa=function(result){
    var html="";
    $('#TableListaempresa').DataTable().destroy();

    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='nombrecompleto'>"+r.paterno+" "+r.materno+" "+r.nombre+"</td>"+
            "<td class='razon_social'>"+r.razon_social+"</td>"+
            "<td class='ruc'>"+r.ruc+"</td>"+
            "<td class='ruc'>"+r.nombre_comercial+"</td>"+
            '<td><input type="hidden" class="persona_id" value='+r.persona_id+'><span class="btn btn-primary btn-sm" onClick="SeleccionarEmpresa(0,'+r.id+')"><i class="glyphicon glyphicon-ok"></i></span></td>';

        html+="</tr>";
    });
    $("#TableListaempresa tbody").html(html); 
    $("#TableListaempresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
