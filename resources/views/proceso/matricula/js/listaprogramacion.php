<script type="text/javascript">
var LDfiltrosG='';
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
      bfiltros= button.data('filtros');
      if( typeof (bfiltros)!='undefined'){
          LDfiltrosG=bfiltros;
      }
      AjaxListaprogramacion.Cargar(HTMLCargarProgramacion);

    });

    $('#ModalListaprogramacion').on('hidden.bs.modal', function (event) {
//        $("ModalDocenteForm input[type='hidden']").remove();
//        $("ModalDocenteForm input").val('');
    });
});

SeleccionarProgramacion = function(val,id){
    var existe=$("#t_matricula #trid_"+id+"").val();
    if( val==0 && typeof(existe)=='undefined'){
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
        var fecha_f=fecha_final.split(" ");
        if(sucursal_id==1){
            var mod='VIRTUAL';
        }
        var html='';
          html+="<tr id='trid_"+id+"'>"+
            "<td>"+
            "<input type='text' class='form-control' value='"+mod+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+curso+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+fecha_i[0]+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+fecha_i[1]+"-"+fecha_f[1]+" | "+dia+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+sucursal+"' disabled></td>"+
            '<td><a onClick="Eliminar('+id+')" class="btn btn-danger" ><i class="fa fa-trash fa-lg"></i></a></td>';
          html+="</tr>";
        
        $("#t_matricula").append(html);
        
          var html1='';
          html1+="<tr id='trid_"+id+"'>"+
            "<td><input type='hidden' id='txt_programacion_id' name='txt_programacion_id[]' class='form-control' value='"+id+"' readOnly>"+
            "<input type='text' class='form-control'  value='"+curso+"'  disabled></td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago' name='txt_nro_pago[]'></td>"+
            "<td><input type='text' class='form-control'  id='txt_monto_pago' name='txt_monto_pago[]' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>"+
            "<td>"+
            '<input type="text" readonly class="form-control" id="pago_nombre'+id+'"" name="pago_nombre[]" value="">'+
                    '<input type="text" style="display: none;" id="pago_archivo'+id+'" name="pago_archivo[]">'+
                    '<label class="btn bg-olive btn-flat margin">'+
                        '<i class="fa fa-file-pdf-o fa-lg"></i>'+
                        '<i class="fa fa-file-word-o fa-lg"></i>'+
                        '<i class="fa fa-file-image-o fa-lg"></i>'+
                    '<input type="file" style="display: none;" onchange="onPagos('+id+',2);" >'+
             '</label>'+ 
            "</td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago_certificado' name='txt_nro_pago_certificado[]'></td>"+
            "<td><input type='text' class='form-control'  id='txt_monto_pago_certificado' name='txt_monto_pago_certificado[]' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>"+
            "<td>"+
            '<input type="text" readonly class="form-control" id="pago_nombre_certificado'+id+'"  name="pago_nombre_certificado[]" value="">'+
                    '<input type="text" style="display: none;" id="pago_archivo_certificado'+id+'" name="pago_archivo_certificado[]">'+
                    '<label class="btn bg-olive btn-flat margin">'+
                        '<i class="fa fa-file-pdf-o fa-lg"></i>'+
                        '<i class="fa fa-file-word-o fa-lg"></i>'+
                        '<i class="fa fa-file-image-o fa-lg"></i>'+
                    '<input type="file" style="display: none;" onchange="onPagos('+id+',1);" >'+
             '</label>'+ 
            "</td>";
          html1+="</tr>";
        
        $("#t_pago").append(html1);
        
        $('#ModalListaprogramacion').modal('hide');
    }else {
        alert("Ya se agregó el  Curso");
    }
};
    
onPagos=function(item,val){
    if(val==1){ etiqueta="_certificado";}else {etiqueta="";}
    var files = event.target.files || event.dataTransfer.files;
    if (!files.length)
      return;
    var image = new Image();
    var reader = new FileReader();
    reader.onload = (e) => {
        $("#t_pago #trid_"+item+" #pago_archivo"+etiqueta+item).val(event.target.result);
        //console.log(event.target.result);
    };
    reader.readAsDataURL(files[0]);
    $("#t_pago #trid_"+item+" #pago_nombre"+etiqueta+item).val(files[0].name);
//    console.log(files[0].name);
};    
    

Eliminar = function (tr) {
        var c = confirm("¿Está seguro de Eliminar el Curso?");
        if (c) {
            $("#t_matricula #trid_"+tr+"").remove();
            $("#t_pago #trid_"+tr+"").remove();
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
            '<span class="btn btn-primary btn-sm" onClick="SeleccionarProgramacion(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>'+
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
            masterG.CargarPaginacion('HTMLCargarDocente','AjaxDocente',result.data,'#TableListaprogramacion_paginate');
        } 
    });
};
</script>
