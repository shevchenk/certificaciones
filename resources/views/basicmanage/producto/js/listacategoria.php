<script type="text/javascript">
var LPtextoIdCategoria='';
var LPtextoCategoria='';
var LPfiltrosG='';
$(document).ready(function() {
    $("#TableListacategoria").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $("#ListacategoriaForm #TableListacategoria select").change(function(){ AjaxListacategoria.Cargar(HTMLCargarListaCategoria); });
    $("#ListacategoriaForm #TableListacategoria input").blur(function(){ AjaxListacategoria.Cargar(HTMLCargarListaCategoria); });

    $('#ModalListacategoria').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListacategoria.Cargar(HTMLCargarListaCategoria);
      
      LPtextoIdCategoria= button.data('categoriaid');
      LPtextoCategoria= button.data('categoria');
      
    });

    $('#ModalListacategoria').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();
    });
});

SeleccionarCategoria = function(val,id){
    $("#"+LPtextoCategoria).val('');
    $("#"+LPtextoIdCategoria).val('');
    if( val==0 ){
        var categoria=$("#TableListacategoria #trid_"+id+" .categoria").text();
        $("#"+LPtextoCategoria).val(categoria);
        $("#"+LPtextoIdCategoria).val(id);

        $('#ModalListacategoria').modal('hide');
        $( '#ModalProveedorDetalle #btn_listararticulo' ).data( 'filtros', 'estado:1|categoria_id:'+id );
        console.log( $( '#ModalProveedorDetalle #btn_listararticulo' ).data( 'filtros' ) );
    }
    }
    
    
HTMLCargarListaCategoria=function(result){
    var html="";
    $('#TableListacategoria').DataTable().destroy();

    $.each(result.data,function(index,r){
        
        html+="<tr id='trid_"+r.id+"'>"+
            
            "<td class='categoria'>"+r.categoria+"</td>";
         
            
         html+='<td><span class="btn btn-primary btn-sm" onClick="SeleccionarCategoria(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span></td>';
    });
    $("#TableListacategoria tbody").html(html); 
    $("#TableListacategoria").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
