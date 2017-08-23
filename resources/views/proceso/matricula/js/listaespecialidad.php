<script type="text/javascript">
var LDfiltrosG='';
$(document).ready(function() {
    $("#TableListaespecialidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $("#ListaespecialidadForm #TableListaespecialidad select").change(function(){ AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad); });
    $("#ListaespecialidadForm #TableListaespecialidad input").blur(function(){ AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad); });

    $('#ModalListaespecialidad').on('shown.bs.modal', function (event) { 
        
        var persona_id=$("#txt_persona_id").val();
        bfiltros='persona_id:'+persona_id;
      if( typeof (bfiltros)!='undefined'){
          LDfiltrosG=bfiltros;
      }
      AjaxListaespecialidad.Cargar(HTMLCargarEspecialidad);

    });

    $('#ModalListaespecialidad').on('hidden.bs.modal', function (event) {
        LDfiltrosG='';
    });

});

SeleccionarEspecialidad = function(val,id){
    var existe=$("#t_matricula #trid_"+id+"").val();
    if( val==0 && typeof(existe)=='undefined'){
        var especialidad=$("#TableListaespecialidad #trid_"+id+" .especialidad").text();
        var certificado_especialidad=$("#TableListaespecialidad #trid_"+id+" .certificado_especialidad").text();

        var html='';
          html+="<tr id='trid_"+id+"'>"+
            "<td>"+
            "<input type='text' class='form-control' value='"+especialidad+"' disabled></td>"+
            "<td><input type='text' class='form-control' value='"+certificado_especialidad+"' disabled></td>"+
            '<td><a onClick="Eliminar('+id+')" class="btn btn-danger" ><i class="fa fa-trash fa-lg"></i></a></td>';
          html+="</tr>";
        
        $("#t_matricula").append(html);
        
          var html1='';
          html1+="<tr id='trid_"+id+"'>"+
            "<td><input type='hidden' id='txt_especialidad_id' name='txt_especialidad_id[]' class='form-control' value='"+id+"' readOnly>"+
            "<input type='text' class='form-control'  value='"+especialidad+"'  disabled></td>"+
            "<td><input type='text' class='form-control'  id='txt_nro_pago_certificado' name='txt_nro_pago_certificado[]'></td>"+
            "<td><input type='text' class='form-control'  id='txt_monto_pago_certificado' name='txt_monto_pago_certificado[]' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>"+
            "<td>"+
            '<input type="text" readonly class="form-control" id="pago_nombre_certificado'+id+'"  name="pago_nombre_certificado[]" value="">'+
                    '<input type="text" style="display: none;" id="pago_archivo_certificado'+id+'" name="pago_archivo_certificado[]">'+
                    '<label class="btn btn-warning  btn-flat margin">'+
                        '<i class="fa fa-file-pdf-o fa-lg"></i>'+
                        '<i class="fa fa-file-word-o fa-lg"></i>'+
                        '<i class="fa fa-file-image-o fa-lg"></i>'+
                    '<input type="file" style="display: none;" onchange="onPagos('+id+',1);" >'+
             '</label>'+ 
            "</td>";
          html1+="</tr>";
        
        $("#t_pago").append(html1);
        
        $('#ModalListaespecialidad').modal('hide');
    }else {
        msjG.mensaje('warning',"Ya se agregó la Especialidad",3000);
    }
};
    
onPagos=function(item,val){
    if(val==1){ etiqueta="_certificado";}
    if(val==3){ etiqueta="_matricula";}
    if(val==2){etiqueta="";}
    
    var files = event.target.files || event.dataTransfer.files;
    if (!files.length)
      return;
    var image = new Image();
    var reader = new FileReader();
    reader.onload = (e) => {
        if(val!=3){
            $("#t_pago #trid_"+item+" #pago_archivo"+etiqueta+item).val(event.target.result);
        }else {
            $("#t_pago_matricula  #pago_archivo"+etiqueta).val(event.target.result);
        }
        //console.log(event.target.result);
    };
    reader.readAsDataURL(files[0]);
    if(val!=3){
        $("#t_pago #trid_"+item+" #pago_nombre"+etiqueta+item).val(files[0].name);
    }else {
        $("#t_pago_matricula  #pago_nombre"+etiqueta).val(files[0].name);
    }
    
//    console.log(files[0].name);
};    
    

Eliminar = function (tr) {
        var c = confirm("¿Está seguro de Eliminar la Especialiación?");
        if (c) {
            $("#t_matricula #trid_"+tr+"").remove();
            $("#t_pago #trid_"+tr+"").remove();
        }
};
    
ValidarPersona=function(thiis){
    
    if($("#"+$(thiis).data('personaid')).val()!=''){
        $('#ModalListaespecialidad').modal('show');
    }else{
        msjG.mensaje('warning',"No hay una persona seleccionada",3000);
    }
}

HTMLCargarEspecialidad=function(result){
    var html="";
    $('#TableListaespecialidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='especialidad'>"+r.especialidad+"</td>"+
            "<td class='certificado_especialidad'>"+r.certificado_especialidad+"</td>"+
            "<td class='nveces'>"+r.nveces+"</td>"+
            "<td>";
                if(r.validar>0 || r.nveces>0){
                    html+='<span class="btn btn-primary btn-sm" onClick="SeleccionarEspecialidad(0,'+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>';
                }
        html+="<input type='hidden' class='id' value='"+r.id+"'>"+
              "</td>"+
              "</tr>";
    });
    $("#TableListaespecialidad tbody").html(html); 
    $("#TableListaespecialidad").DataTable({
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
            $('#TableListaespecialidad_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEspecialidad','AjaxListaespecialidad',result.data,'#TableListaespecialidad_paginate');
        } 
    });
};

</script>
