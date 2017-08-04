<script type="text/javascript">
var LPtextoIdProducto='';
var LPtextoProducto='';
var LPfiltrosG='';
$(document).ready(function() {
    $("#TableListaproducto").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    $("#ListaproductoForm #TableListaproducto select").change(function(){ AjaxListaproducto.Cargar(HTMLCargarListaProducto); });
    $("#ListaproductoForm #TableListaproducto input").blur(function(){ AjaxListaproducto.Cargar(HTMLCargarListaProducto); });
    
    $('#ModalListaproducto').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListaproducto.Cargar(HTMLCargarListaProducto);
      
      LPtextoIdProducto= button.data('productoid');
      LPtextoProducto= button.data('producto');
      
    });

    $('#ModalListaproducto').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();
    });
});

SeleccionarPersona = function(val,id){
    $("#"+LPtextoProducto).val('');
    $("#"+LPtextoIdProducto).val('');
    if( val==0 ){
        var producto=$("#TableListaproducto #trid_"+id+" .producto").text();
        $("#"+LPtextoProducto).val(producto);
        $("#"+LPtextoIdProducto).val(id);
        $('.persona').css("display","");
        $('.empresa').css("display","none");
        $('#ModalListaproducto').modal('hide');
    }
    }
    
    
HTMLCargarListaProducto=function(result){
    var html="";
    $('#TableListaproducto').DataTable().destroy();

    $.each(result.data,function(index,r){
        
        html+="<tr id='trid_"+r.id+"'>"+
            "<td>";
            if(r.foto!=null){    
            html+="<a  target='_blank' href='img/product/"+r.foto+"'><img src='img/product/"+r.foto+"' style='height: 40px;width: 40px;'></a>";}
            html+="</td>"+
            "<td class='producto'>"+r.producto+"</td>"+
            "<td class='articulo'>"+r.articulo+"</td>"+
            '<td><span class="btn btn-primary btn-sm" onClick="SeleccionarPersona(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
            "</td>"+
            "<input type='hidden' class='articulo_id' value='"+r.articulo_id+"'>"+
            "<input type='hidden' class='unidad_medida' value='"+r.unidad_medida+"'>";
        if(r.foto!=null){
        html+="<input type='hidden' class='foto' value='"+r.foto+"'>";}

        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>";
        html+="</tr>";
    });
    $("#TableListaproducto tbody").html(html); 
    $("#TableListaproducto").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
