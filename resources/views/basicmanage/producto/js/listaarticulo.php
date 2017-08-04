<script type="text/javascript">
var LPtextoIdArticulo='';
var LPtextoArticulo='';
var LPfiltrosG='';
$(document).ready(function() {
    $("#TableListaArticulo").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $("#ListaarticuloForm #TableListaArticulo select").change(function(){ AjaxListaarticulo.Cargar(HTMLCargarListaArticulo); });
    $("#ListaarticuloForm #TableListaArticulo input").blur(function(){ AjaxListaarticulo.Cargar(HTMLCargarListaArticulo); });

    $('#ModalListaarticulo').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListaarticulo.Cargar(HTMLCargarListaArticulo);
      LPtextoIdArticulo= button.data('articuloid');

      LPtextoArticulo= button.data('articulo');

      
    });

    $('#ModalListaarticulo').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();
    });
});

SeleccionarArticulo = function(val,id){
    $("#"+LPtextoArticulo).val('');
    $("#"+LPtextoIdArticulo).val('');
    if( val==0 ){
        var articulo=$("#TableListaArticulo #trid_"+id+" .articulo").text();

        $("#"+LPtextoArticulo).val(articulo);
        $("#"+LPtextoIdArticulo).val(id);
   

        $("#ListaarticuloForm #txt_categoria_id").val(id);

         
        $('#ModalListaarticulo').modal('hide');
    }
}
    
    
HTMLCargarListaArticulo=function(result){
    var html="";
    $('#TableListaArticulo').DataTable().destroy();

    $.each(result.data,function(index,r){
        
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='articulo'>"+r.articulo+"</td>";
            //"<input type='hidden' class='categoria_id' value='"+r.categoria_id+"'>";
         
         html+='<td><span class="btn btn-primary btn-sm" onClick="SeleccionarArticulo(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span></td>';
    });
    $("#TableListaArticulo tbody").html(html); 
    $("#TableListaArticulo").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
