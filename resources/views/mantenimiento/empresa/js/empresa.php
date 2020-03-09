<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var EmpresaG={id:0,empresa:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    AjaxEmpresa.CargarTipoEvaluacion(SlctCargarTipoEvaluacion);
    $("#EmpresaForm #TableEmpresa select").change(function(){ AjaxEmpresa.Cargar(HTMLCargarEmpresa); });
    $("#EmpresaForm #TableEmpresa input").blur(function(){ AjaxEmpresa.Cargar(HTMLCargarEmpresa); });

    $('#ModalEmpresa').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEmpresaForm").append("<input type='hidden' value='"+EmpresaG.id+"' name='id'>");
        }

        $('#ModalEmpresaForm #txt_empresa').val( EmpresaG.empresa );
        $('#ModalEmpresaForm #txt_nota_minima').val( EmpresaG.nota_minima );
        $('#ModalEmpresaForm #txt_logo_nombre').val( EmpresaG.logo_archivo );
        $('#ModalEmpresaForm #txt_contenido_ficha').val( EmpresaG.contenido_ficha );
        masterG.SelectImagen(EmpresaG.logo_archivo, '#logo_img');
        
        $('#ModalEmpresaForm #slct_estado').selectpicker('val', EmpresaG.estado );
        $("#ModalEmpresaForm #slct_trabajo_final").selectpicker( 'val', EmpresaG.trabajo_final );
        $("#ModalEmpresaForm #slct_peso_trabajo_final").selectpicker( 'val', EmpresaG.peso_trabajo_final );

        $("#ModalEmpresaForm #slct_peso_trabajo_final").removeAttr("disabled");
        if( $("#ModalEmpresaForm #slct_trabajo_final").val()==0 ){
            $("#ModalEmpresaForm #slct_peso_trabajo_final").attr("disabled","true");
        }
        AjaxEmpresa.CargarEvaluaciones(HTMLCargarEvaluaciones);
        $('#ModalEmpresaForm #txt_empresa').focus();
    });

    $('#ModalEmpresa').on('hidden.bs.modal', function (event) {
        $("#ModalEmpresaForm input[type='hidden']").not('.mant').remove();
       // $("ModalEmpresaForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEmpresaForm #txt_empresa").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Empresa',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EmpresaG.id='';
    EmpresaG.empresa='';
    EmpresaG.estado='1';
    EmpresaG.nota_minima='0';
    EmpresaG.trabajo_final='0';
    EmpresaG.peso_trabajo_final='0';
    EmpresaG.logo_archivo='';
    EmpresaG.contenido_ficha='';
    if( val==0 ){
        EmpresaG.id=id;
        EmpresaG.empresa=$("#TableEmpresa #trid_"+id+" .empresa").text();
        EmpresaG.estado=$("#TableEmpresa #trid_"+id+" .estado").val();
        EmpresaG.nota_minima=$("#TableEmpresa #trid_"+id+" .nota_minima").val();
        EmpresaG.trabajo_final=$("#TableEmpresa #trid_"+id+" .trabajo_final").val();
        EmpresaG.peso_trabajo_final=$("#TableEmpresa #trid_"+id+" .peso_trabajo_final").val();
        EmpresaG.logo_archivo=$("#TableEmpresa #trid_"+id+" .logo_archivo").val();
        EmpresaG.contenido_ficha=$("#TableEmpresa #trid_"+id+" .contenido_ficha").val();
    }
    $('#ModalEmpresa').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxEmpresa.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEmpresa.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEmpresa').modal('hide');
        AjaxEmpresa.Cargar(HTMLCargarEmpresa);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarEmpresa=function(result){
    var html="";
    $('#TableEmpresa').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='empresa'>"+r.empresa+"</td>"+
            "<td>";
        html+="<input type='hidden' class='trabajo_final' value='"+r.trabajo_final+"'>";
        html+="<input type='hidden' class='peso_trabajo_final' value='"+r.peso_trabajo_final+"'>";
        html+="<input type='hidden' class='logo_archivo' value='"+$.trim(r.logo_archivo)+"'>";
        html+="<input type='hidden' class='contenido_ficha' value='"+$.trim(r.contenido_ficha)+"'>";
        html+="<input type='hidden' class='nota_minima' value='"+r.nota_minima+"'>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableEmpresa tbody").html(html); 
    $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthEmpresa": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableEmpresa_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEmpresa','AjaxEmpresa',result.data,'#TableEmpresa_paginate');
        }
    });
};

validaProyectoFinal=function(t){
    $("#ModalEmpresaForm #slct_peso_trabajo_final").removeAttr("disabled");
    $("#ModalEmpresaForm #slct_peso_trabajo_final").selectpicker("val",1);
    if( t.value==0 ){
        $("#ModalEmpresaForm #slct_peso_trabajo_final").selectpicker("val",0);
        $("#ModalEmpresaForm #slct_peso_trabajo_final").attr("disabled","true");
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
    $("#ModalEmpresa #slct_tipo_evaluacion").html(html); 
    $("#ModalEmpresa #slct_tipo_evaluacion").selectpicker('refresh');
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
