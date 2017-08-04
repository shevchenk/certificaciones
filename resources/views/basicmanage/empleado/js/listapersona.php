<script type="text/javascript">
var LPtextoIdPersona='';
var LPtextoPersona='';
var LPtextoIdEmpresa='';
var LPtextoEmpresa='';
var LPfiltrosG='';
$(document).ready(function() {
    $("#TableListapersona").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $('#ModalListapersona').on('shown.bs.modal', function (event) { 
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListapersona.Cargar(HTMLCargarPersona);
      
      LPtextoIdPersona= button.data('personaid');
      LPtextoPersona= button.data('persona');
      LPtextoIdEmpresa= button.data('empresaid');
      LPtextoEmpresa= button.data('empresa');
      
    });

    $('#ModalListapersona').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();
    });
});

SeleccionarPersona = function(val,id){
    $("#"+LPtextoEmpresa).val('');
    $("#"+LPtextoIdEmpresa).val('');
    $("#"+LPtextoPersona).val('');
    $("#"+LPtextoIdPersona).val('');
    if( val==0 ){
        var paterno=$("#TableListapersona #trid_"+id+" .paterno").text();
        var materno=$("#TableListapersona #trid_"+id+" .materno").text();
        var nombre=$("#TableListapersona #trid_"+id+" .nombre").text();
        $("#"+LPtextoPersona).val(paterno+" "+materno+" "+nombre);
        $("#"+LPtextoIdPersona).val(id);
        $('.persona').css("display","");
        $('.empresa').css("display","none");
        $('#ModalListapersona').modal('hide');
    }
    }
    
    
HTMLCargarPersona=function(result){
    var html="";
    $('#TableListapersona').DataTable().destroy();

    $.each(result.data,function(index,r){
        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='paterno'>"+r.paterno+"</td>"+
            "<td class='materno'>"+r.materno+"</td>"+
            "<td class='nombre'>"+r.nombre+"</td>"+
            "<td class='dni'>"+r.dni+"</td>"+
           '<td><span class="btn btn-primary btn-sm" onClick="SeleccionarPersona(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
                        "<input type='hidden' class='email' value='"+r.email+"'>"+
            "<input type='hidden' class='fecha_nacimiento' value='"+r.fecha_nacimiento+"'>"+
            "<input type='hidden' class='sexo' value='"+r.sexo+"'>"+
            "<input type='hidden' class='telefono' value='"+r.telefono+"'>"+
            "<input type='hidden' class='celular' value='"+r.celular+"'>"+
             "<input type='hidden' class='estado' value='"+r.estado+"'>"+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar1(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';

        html+="</tr>";
    });
    $("#TableListapersona tbody").html(html); 
    $("#TableListapersona").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
};
</script>
