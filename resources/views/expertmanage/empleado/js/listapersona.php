<script type="text/javascript">
var LTtextoIdPersona='';
var LTtextoPersona='';
var LTtextoIdEmpresa='';
var LTtextoEmpresa='';
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
   
    $("#ListapersonaForm #TableListapersona select").change(function(){ AjaxListapersona.Cargar(HTMLCargarPersona); });
    $("#ListapersonaForm #TableListapersona input").blur(function(){ AjaxListapersona.Cargar(HTMLCargarPersona); });

    $('#ModalListapersona').on('shown.bs.modal', function (event) {
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListapersona.Cargar(HTMLCargarPersona);

      LTtextoIdPersona= button.data('personaid');
      LTtextoPersona= button.data('persona');
      LTtextoIdEmpresa= button.data('empresaid');
      LTtextoEmpresa= button.data('empresa');

    });

    $('#ModalListapersona').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();

    });
});

SeleccionarPersona = function(val,id){
    $("#"+LTtextoEmpresa).val('');
    $("#"+LTtextoIdEmpresa).val('');
    $("#"+LTtextoPersona).val('');
    $("#"+LTtextoIdPersona).val('');
    if( val==0 ){
        var paterno=$("#TableListapersona #trid_"+id+" .paterno").text();
        var materno=$("#TableListapersona #trid_"+id+" .materno").text();
        var nombre=$("#TableListapersona #trid_"+id+" .nombre").text();
        $("#"+LTtextoPersona).val(paterno+" "+materno+" "+nombre);
        $("#"+LTtextoIdPersona).val(id);
        $('.persona').css("display","");
        $('.empresa').css("display","none");
        $('#ModalListapersona').modal('hide');
    }
    }
    
    
HTMLCargarPersona=function(result){
    var html="";
    $('#TableListapersona').DataTable().destroy();

    $.each(result.data.data,function(index,r){

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
            $('#TableListapersona_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarPersona','AjaxPersona',result.data,'#TableListapersona_paginate');
        } 
    });
};
</script>
