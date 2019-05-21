<script type="text/javascript">
var PersonaIdG=0;
var cursos_selec=[];
var AddEdit=0; //0: Editar | 1: Agregar
var EspecialidadG={id:0,
    especialidad:"",
    certificado_especialidad:"",
    curso_id:"",
    estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableDatos").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxEspecialidad.Cargar(HTMLCargar);
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });

    $('#ModalLlamada').on('shown.bs.modal', function (event) {

    });

    $('#ModalLlamada').on('hidden.bs.modal', function (event) {
        $("ModalLlamadaForm input[type='hidden']").not('.mant').remove();
       // $("ModalEspecialidadForm input").val('');
    });
});

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.persona_id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='email'>"+r.email+"</td>"+
                "<td class='telefono'>"+r.telefono+"</td>"+
                "<td class='celular'>"+r.celular+"</td>";
                //"<td>";
                //"<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+=""+
                '<td><a class="btn btn-primary btn-sm" onClick="AbrirLlamada('+r.persona_id+')"><i class="fa fa-phone fa-lg"></i> </a></td>';
        html+="</tr>";
    });//FIN FUNCTION

    $("#TableDatos tbody").html(html); 
    $("#TableDatos").DataTable({ //INICIO DATATABLE
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
            $('#TableDatos_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargar','AjaxEspecialidad',result.data,'#TableDatos_paginate');
        }
    }); //FIN DATA TABLE
}; //FIN HTML

AbrirLlamada=function(id){
    PersonaIdG=id;
    paterno=$("#trid_"+id+" .paterno").text();
    materno=$("#trid_"+id+" .materno").text();
    nombre=$("#trid_"+id+" .nombre").text();
    $("#ModalLlamadaForm #txt_alumno").val( paterno +' '+materno+', '+nombre );
    $('#ModalLlamada').modal('show');
}
</script>


