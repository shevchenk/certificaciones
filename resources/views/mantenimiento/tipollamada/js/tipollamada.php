<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var TipoLlamadaG={id:0,tipollamada:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableTipoLlamada").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    $("#TipoLlamadaForm #TableTipoLlamada select").change(function(){ AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada); });
    $("#TipoLlamadaForm #TableTipoLlamada input").blur(function(){ AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada); });

    $('#ModalTipoLlamada').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalTipoLlamadaForm").append("<input type='hidden' value='"+TipoLlamadaG.id+"' name='id'>");
        }

        $('#ModalTipoLlamadaForm #txt_tipo_llamada').val( TipoLlamadaG.tipollamada );
        
        $('#ModalTipoLlamadaForm #slct_estado').selectpicker('val', TipoLlamadaG.estado );
        $("#ModalTipoLlamadaForm #slct_peso").selectpicker( 'val', TipoLlamadaG.peso );
        $("#ModalTipoLlamadaForm #slct_obs").selectpicker( 'val', TipoLlamadaG.obs );

        $('#ModalTipoLlamadaForm #txt_tipollamada').focus();
    });

    $('#ModalTipoLlamada').on('hidden.bs.modal', function (event) {
        $("#ModalTipoLlamadaForm input[type='hidden']").not('.mant').remove();
       // $("ModalTipoLlamadaForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalTipoLlamadaForm #txt_tipo_llamada").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo de Llamada',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    TipoLlamadaG.id='';
    TipoLlamadaG.tipollamada='';
    TipoLlamadaG.estado='1';
    TipoLlamadaG.peso='0';
    TipoLlamadaG.obs='0';
    if( val==0 ){
        TipoLlamadaG.id=id;
        TipoLlamadaG.tipollamada=$("#TableTipoLlamada #trid_"+id+" .tipollamada").text();
        TipoLlamadaG.estado=$("#TableTipoLlamada #trid_"+id+" .estado").val();
        TipoLlamadaG.peso=$("#TableTipoLlamada #trid_"+id+" .peso").val();
        TipoLlamadaG.obs=$("#TableTipoLlamada #trid_"+id+" .obs").val();
    }
    $('#ModalTipoLlamada').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxTipoLlamada.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxTipoLlamada.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalTipoLlamada').modal('hide');
        AjaxTipoLlamada.Cargar(HTMLCargarTipoLlamada);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarTipoLlamada=function(result){
    var html="";
    $('#TableTipoLlamada').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='tipollamada'>"+r.tipo_llamada+"</td>"+
            "<td>";
        html+="<input type='hidden' class='peso' value='"+r.peso+"'>";
        html+="<input type='hidden' class='obs' value='"+r.obs+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableTipoLlamada tbody").html(html); 
    $("#TableTipoLlamada").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthTipoLlamada": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableTipoLlamada_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarTipoLlamada','AjaxTipoLlamada',result.data,'#TableTipoLlamada_paginate');
        }
    });
};

validaProyectoFinal=function(t){
    $("#ModalTipoLlamadaForm #slct_peso_trabajo_final").removeAttr("disabled");
    $("#ModalTipoLlamadaForm #slct_peso_trabajo_final").selectpicker("val",1);
    if( t.value==0 ){
        $("#ModalTipoLlamadaForm #slct_peso_trabajo_final").selectpicker("val",0);
        $("#ModalTipoLlamadaForm #slct_peso_trabajo_final").attr("disabled","true");
    }
}

ValidarTipoEvaluacion=function(){
    if( $('#slct_tipo_evaluacion').val()!='' ){
        if( $.trim( $(".trclass_"+$('#slct_tipo_evaluacion').val()).html() )=='' ){
            var html='';
            html='<tr class="trclass_'+$('#slct_tipo_evaluacion').val()+'">'+
                '<td>'+($('#tb_te tr').length+1)+'</td>'+
                '<td><input type="hidden" name="txt_tipo_evaluacion[]" value="'+$('#slct_tipo_evaluacion').val()+'">'+$('#slct_tipo_evaluacion').find('option:selected').text()+'</td>'+
                '<td><select name="slct_peso_evaluacion[]">'+
                    '<option value="">.::Seleccione::.</option>'+
                    '<option value="1" selected>1</option>'+
                    '<option value="2" >2</option>'+
                    '<option value="3" >3</option>'+
                    '<option value="4" >4</option>'+
                    '<option value="5" >5</option>'+
                '</select></td>'+
                '<td><a onClick="QuitarTipoEvaluacion('+$('#slct_tipo_evaluacion').val()+');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td>'+
            '</tr>';
            $('#tb_te').append(html);
            $(".fecha").datetimepicker({
                format: "yyyy-mm-dd",
                language: 'es',
                showMeridian: true,
                time:true,
                minView:2,
                autoclose: true,
                todayBtn: false
            });
        }
        else{
            msjG.mensaje('warning','Tipo de evaluación ya existe!',3000);
        }
    }
    else{
        msjG.mensaje('warning','Seleccione un tipo de evaluación',3000);
    }
}

QuitarTipoEvaluacion=function(te){
    $(".trclass_"+te).remove();
    $('#tb_te tr').each(function(index,val){
        $(val).find('td:eq(0)').text( (index+1) );
    });
}

SlctCargarTipoEvaluacion=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.tipo_evaluacion+"</option>";
    });
    $("#ModalTipoLlamada #slct_tipo_evaluacion").html(html); 
    $("#ModalTipoLlamada #slct_tipo_evaluacion").selectpicker('refresh');
};

HTMLCargarEvaluaciones=function(result){
    $('#tb_te').html('');
    $.each(result.data,function(index,r){
        var html='';
            html='<tr class="trclass_'+r.id+'">'+
                '<td>'+(index+1)+'</td>'+
                '<td><input type="hidden" name="txt_tipo_evaluacion[]" value="'+r.id+'">'+r.tipo_evaluacion+'</td>'+
                '<td><select name="slct_peso_evaluacion[]">'+
                    '<option value="">.::Seleccione::.</option>';
                for (var i = 1; i < 6; i++) {
                    selected='';
                    if(i==r.peso_evaluacion){
                        selected='selected';
                    }
                    html+='<option value="'+i+'" '+selected+'>'+i+'</option>';
                }
            html+='</select></td>'+
                '<td><a onClick="QuitarTipoEvaluacion('+r.id+');" class="btn btn-flat btn-danger"><i class="fa fa-trash fa-lg"></i></a></td>'+
            '</tr>';
            $('#tb_te').append(html);
    });
}

</script>
