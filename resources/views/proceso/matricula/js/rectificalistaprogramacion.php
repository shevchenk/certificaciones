<?php 
use App\Models\Proceso\Promocion;
$promocionG=Promocion::where('estado',1)->first(); 
?>
<script type="text/javascript">
var LDfiltrosG='';
var LDTipoTabla=0;
var PromocionG="<?php echo $promocionG->pae ?>";
var CheckG=0; // Indica los checks q tiene actualmente
var CheckedG=0; // Indica cuantos tiene
$(document).ready(function() {
    $("#TableListaprogramacion").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
   
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxListaprogramacion.Cargar(HTMLCargarProgramacion); });

    $('#ModalListaprogramacion').on('shown.bs.modal', function (event) {
          var button = $(event.relatedTarget); // captura al boton
          $( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).change(function() {
             
                  bfiltros= button.data('filtros');
                    if( typeof (bfiltros)!='undefined'){
                        LDfiltrosG=bfiltros+'|tipo_modalidad:'+$( "#ModalListaprogramacion #slct_tipo_modalidad_id" ).val();
                    }
                    if( typeof (button.data('tipotabla'))!='undefined'){
                        LDTipoTabla=button.data('tipotabla');
                    }
                  AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);
          });
    });

    $('#ModalListaprogramacion').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
//        $("ModalDocenteForm input").val('');
    });
});

SeleccionarProgramacion = function(val,id){
    //var existe=$("#t_matricula #trid_"+id+"").val();

    //alert(id);
    //return false;

    if( val==0 && typeof(existe)=='undefined'){
        //data=$("#ListaprogramacionForm").serialize().split("txt_").join("").split("slct_").join("");

        var id_matri = ($("#ListaprogramacionForm #id_matri").val());
        var id_matri_deta = $("#ListaprogramacionForm #id_matri_deta").val();
        
        AjaxEspecialidad.actualizarMatriDeta(HTMLCargaMatriDeta, id_matri_deta, id);
        AjaxEspecialidad.verMatriDeta(HTMLCargaMatriDeta, id_matri);

        /*
        var mod='PRESENCIAL';
        var docente=$("#TableListaprogramacion #trid_"+id+" .docente").text();
        var persona_id=$("#TableListaprogramacion #trid_"+id+" .persona_id").val();
        var sucursal_id=$("#TableListaprogramacion #trid_"+id+" .sucursal_id").val();
        var sucursal=$("#TableListaprogramacion #trid_"+id+" .sucursal").text();
        var curso_id=$("#TableListaprogramacion #trid_"+id+" .curso_id").val();
        var dia=$("#TableListaprogramacion #trid_"+id+" .dia").val();
        var curso=$("#TableListaprogramacion #trid_"+id+" .curso").text();
        var fecha_inicio=$("#TableListaprogramacion #trid_"+id+" .fecha_inicio").text();
        var fecha_final=$("#TableListaprogramacion #trid_"+id+" .fecha_final").text();
        var fecha_i=fecha_inicio.split(" ");
        */


        
        $('#ModalListaprogramacion').modal('hide');
    }
    /*else {
        msjG.mensaje('warning',"Ya se agregó el  Curso",3000);
    }*/
};

/*
onPagos=function(item,val){
    if(val==1){ etiqueta="_certificado";}
    if(val==3){ etiqueta="_matricula";}
    if(val==4){ etiqueta="_inscripcion";}
    if(val==2){etiqueta="";}
    
    var files = event.target.files || event.dataTransfer.files;
    if (!files.length)
      return;
    var image = new Image();
    var reader = new FileReader();
    reader.onload = (e) => {
        if(val==3){
            $("#t_pago_matricula  #pago_archivo"+etiqueta).val(event.target.result);
        }else if(val==4){
            $("#t_pago_inscripcion  #pago_archivo"+etiqueta).val(event.target.result);
        }else {
            $("#t_pago #trid_"+item+" #pago_archivo"+etiqueta+item).val(event.target.result);
        }
        //console.log(event.target.result);
    };
    reader.readAsDataURL(files[0]);
    if(val==3){
         $("#t_pago_matricula  #pago_nombre"+etiqueta).val(files[0].name);
    }else if(val==4){
         $("#t_pago_inscripcion  #pago_nombre"+etiqueta).val(files[0].name);
    }else {
         $("#t_pago #trid_"+item+" #pago_nombre"+etiqueta+item).val(files[0].name);
    }
    
//    console.log(files[0].name);
};
*/
    

Eliminar = function (tr) {
        var c = confirm("¿Está seguro de Eliminar el Curso?");
        if (c) {

            if( $("#t_matricula #trid_"+tr+" input[type='checkbox'].flat").is(':checked') ){
                CheckedG--;
            }

            $("#t_matricula #trid_"+tr+"").remove();
            $("#t_pago #trid_"+tr+"").remove();
            CheckG--;
        }
};
    
    
HTMLCargarProgramacion=function(result){
    var html="";
    $('#TableListaprogramacion').DataTable().destroy();

    $.each(result.data.data,function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='persona'>"+r.persona+"</td>"+
            "<td class='sucursal'>"+r.sucursal+"</td>"+
            "<td class='curso'>"+r.curso+"</td>"+
            "<td class='aula'>"+r.aula+"</td>"+
            "<td class='fecha_inicio'>"+r.fecha_inicio+"</td>"+
            "<td class='fecha_final'>"+r.fecha_final+"</td>"+
            "<td>"+
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion(0,'+r.id+')"><i class="glyphicon glyphicon-ok"></i></span>'+
            "<input type='hidden' class='dia' value='"+r.dia+"'>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='docente_id' value='"+r.docente_id+"'>"+
            "<input type='hidden' class='sucursal_id' value='"+r.sucursal_id+"'>"+
            "<input type='hidden' class='curso_id' value='"+r.curso_id+"'>";
        html+="</td>";
        html+="</tr>";
    });
    $("#TableListaprogramacion tbody").html(html); 
    $("#TableListaprogramacion").DataTable({
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
            $('#TableListaprogramacion_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarProgramacion','AjaxListaprogramacion',result.data,'#TableListaprogramacion_paginate');
        } 
    });
};
</script>
