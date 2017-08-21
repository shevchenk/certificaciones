<script type="text/javascript">
var LTtextoIdPersona='';
var LTtextoPersona='';
var LTtextoDNI='';
var LPfiltrosG='';
var LTvalorIdPersona='';
var LTEpersona=1;
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
      $(this).find('.modal-footer .btn-primary').text('Nuevo').css("display","");
      var button = $(event.relatedTarget); // captura al boton
      bfiltros= button.data('filtros');
      if( typeof (button.data('epersona'))!='undefined'){
           LTEpersona=0;
      }
      if( typeof (button.data('apersona'))!='undefined'){
           $(this).find('.modal-footer .btn-primary').text('Nuevo').css("display","none");
      }
      if( typeof (bfiltros)!='undefined'){
          LPfiltrosG=bfiltros;
      }
      AjaxListapersona.Cargar(HTMLCargarPersona);

      LTtextoIdPersona= button.data('personaid');
      LTtextoPersona= button.data('persona');
      LTtextoDNI= button.data('dni');

    });

    $('#ModalListapersona').on('hidden.bs.modal', function (event) {
        LPfiltrosG='';
//        $("ModalPersonaForm input[type='hidden']").remove();

    });
});

SeleccionarPersona = function(val,id){
    LTvalorIdPersona=$("#"+LTtextoIdPersona).val();
    $("#"+LTtextoPersona).val('');
    $("#"+LTtextoIdPersona).val('');
    $("#"+LTtextoDNI).val('');
    
    if( val==0 ){
        var paterno=$("#TableListapersona #trid_"+id+" .paterno").text();
        var materno=$("#TableListapersona #trid_"+id+" .materno").text();
        var nombre=$("#TableListapersona #trid_"+id+" .nombre").text();
        var dni=$("#TableListapersona #trid_"+id+" .dni").text();
        $("#"+LTtextoPersona).val(paterno+" "+materno+" "+nombre);
        $("#"+LTtextoIdPersona).val(id);
        $("#"+LTtextoDNI).val(dni);
        
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
            '<td>';
            if(LTEpersona==1){
                 html+='<a class="btn btn-primary btn-sm" onClick="AgregarEditar2(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a>';
            }
        html+='</td>';

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
