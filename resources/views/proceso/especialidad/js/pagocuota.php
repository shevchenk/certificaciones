<script type="text/javascript">
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
    AjaxEspecialidad.ListSucursal(HTMLListSucursal);
    $("#EspecialidadForm #TableDatos select").change(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
    $("#EspecialidadForm #TableDatos input").blur(function(){ AjaxEspecialidad.Cargar(HTMLCargar); });
});

HTMLCargar=function(result){ //INICIO HTML
    var html="";
    $('#TableDatos').DataTable().destroy();

    $.each(result.data.data,function(index,r){ //INICIO FUNCTION
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>";
   
        html+="</td>"+
                "<td class='dni'>"+r.dni+"</td>"+
                "<td class='paterno'>"+r.paterno+"</td>"+
                "<td class='materno'>"+r.materno+"</td>"+
                "<td class='nombre'>"+r.nombre+"</td>"+
                "<td class='materno'>"+r.email+"</td>"+
                "<td class='materno'>"+r.telefono+"</td>"+
                "<td class='materno'>"+r.celular+"</td>"+
                "<td class='materno'>"+r.direccion+"</td>";

        var tipo_curso=null;
        if( $.trim($("#EspecialidadForm #tipo_curso").val())!='' ){
            tipo_curso=1;
        }

        //html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
        html+=""+
                //'<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
                '<td><a id="btn_'+r.id+'" class="btn btn-default btn-sm" onClick="AjaxEspecialidad.verMatriculas(HTMLCargaMatri, '+r.id+','+tipo_curso+')"><i class="glyphicon glyphicon-book fa-lg"></i> </a></td>';
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

HTMLListSucursal=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.sucursal+"</option>";
    });
    $("#frm_pago_cuota #slct_sucursal").html(html); 
}

// PROCESOS NUEVOS
HTMLCargaMatri=function(result){ //INICIO HTML
    var html="";
    $("#div_cursos_progra #txt_persona_id").val('');
    $("#div_cursos_progra #txt_matricula_id").val('');
    $.each(result.data.data,function(index, r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"'>";
        html+=""+
                "<td class=''>"+r.id+"</td>"+
                "<td class=''>"+r.especialidad+"</td>"+
                "<td class=''>"+r.tipo_participante+"</td>"+
                "<td class=''>"+r.ode+"</td>"+
                "<td class=''>"+r.paterno+' '+r.materno+', '+r.nombre+"</td>"+
                "<td class=''>"+r.fecha_matricula+"</td>"+
                '<td><a id="btnvermatri_'+r.id+'" class="btn btn-primary btn-sm" onClick="CargaMatriDeta('+r.id+','+r.especialidad_programacion_id+')"><i class="glyphicon glyphicon-sort-by-attributes-alt"></i> </a>&nbsp;&nbsp;'+
                '</td>';

        html+="</tr>";
    });//FIN FUNCTION

    $("#tb_tabla2 tbody").html(html); 
};

CargaMatriDeta=function(id_matri,epid){
    AjaxEspecialidad.verMatriDeta(HTMLCargaMatriDeta, id_matri);
    AjaxEspecialidad.verMatriCuota(HTMLCargaMatriCuota, id_matri, epid);
};

HTMLCargaMatriDeta=function(result){ //INICIO HTML
    var html=""; var invi=1; var eliminar='';
    var archivopago=''; var archivopagoc='';
    $("#tb_tabla2_deta .curso").css('display','');
    $("#tb_tabla2_deta tbody").html('');

    $.each(result.data.data,function(index, r){ //INICIO FUNCTION
        html+="<tr id='trid_"+r.id+"'>";
        if( r.tipo_curso==2 ){
            invi=2;
        }

        archivopago="";
        if( $.trim(r.archivo_pago)!="" ){
            archivopago="<a target='_blank' class='btn btn-sm' href='"+r.archivo_pago+"'><i class='fa fa-lg fa-download'><i></a>";
        }

        archivopagoc="";
        if( $.trim(r.archivo_pago_certificado)!="" ){
            archivopagoc="<a target='_blank' class='btn btn-sm' href='"+r.archivo_pago_certificado+"'><i class='fa fa-lg fa-download'><i></a>";
        }

        if($.trim(r.sucursal)==''){
            r.modalidad='';
            eliminar='';
        }

        html+=""+
                "<td class=''>"+r.id+"</td>"+
                "<td class=''>"+$.trim(r.curso)+"</td>"+
                "<td class=''>"+r.modalidad+"</td>"+
                "<td class=''>"+$.trim(r.sucursal)+"</td>"+
                "<td class=''>"+$.trim(r.docente)+"</td>"+
                "<td class=''>"+$.trim(r.dia)+"</td>"+
                "<td class=''>"+$.trim(r.fecha_inicio)+"</td>"+
                "<td class=''>"+$.trim(r.fecha_final)+"</td>";
                
                /*"<td class='curso'>"+$.trim(r.nro_pago)+"</td>"+
                "<td class='curso'>"+r.monto_pago+"</td>"+
                "<td class='curso'>"+archivopago+"</td>"+
                "<td class=''>"+$.trim(r.nro_pago_certificado)+"</td>"+
                "<td class=''>"+r.monto_pago_certificado+"</td>"+
                "<td class=''>"+archivopagoc+"</td>"+*/
                //'<td><a id="btnv_'+r.id+'" class="btn btn-primary btn-sm" onClick="AjaxEspecialidad.verMatriDeta(HTMLCargaMatriDeta, '+r.id+')"><i class="glyphicon glyphicon-sort-by-attributes-alt"></i> </a></td>';
                /*'<td><button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1|tipo_curso:'+r.tipo_curso+'|id_matri:'+r.id_matri+'|curso_id:'+r.curso_id+'|id_matri_deta:'+r.id+'"><i class="glyphicon glyphicon-refresh"></i> Programación</button>'+
                '<td>&nbsp;</td>'+
                '<td>'+eliminar+'</td>';*/

        html+="</tr>";
    });//FIN FUNCTION

    $("#tb_tabla2_deta tbody").html(html);
    if( invi==2 ){
        $("#tb_tabla2_deta .curso").css('display','none');
    }
};

HTMLCargaMatriCuota=function(result){ //INICIO HTML
    var html=""; var fecha_aux=''; var tipo_pago='';
    $("#t_pago_cuota").html('');
    $('#btnPagoCuota').css('display','none');

    $.each(result.data,function(index, r){ //INICIO FUNCTION
        html="<tr>";

        if( fecha_aux!='' && r.fecha_cronograma=='' ){
            r.fecha_cronograma=fecha_aux;
        }

        btn='Registrar Pago';
        click='guardarPagoCuota';
        botton = '<input type="button" class="btn btn-primary" id="btnPagoCuota" onClick="'+click+'('+index+','+r.matricula_id+');" value="'+btn+'">';
        disabled= '';
        costo=r.monto_cronograma;
        saldo = costo;
        alerta = 'warning';
        if( r.fecha_cronograma!='' && r.nro_cuota!='' ){
            btn='Editar Pago';
            click='actualizarPagoCuota';
            botton='';
            disabled='readOnly';
            saldo = r.saldo;
            if( saldo==0 ){
                alerta='success';
            }
        }

        html+=""+
            '<td><input type="hidden" value="'+r.cuota+'" name="txt_cuota[]">'+r.cuota+'</td>'+
            '<td>'+r.fecha_cronograma+'</td>'+
            '<td><select id="slct_sucursal_'+index+'" name="slct_sucursal[]" class="form-control">'+
                $("#frm_pago_cuota #slct_sucursal").html()+
                '</select></td>'+
            '<td><input type="text" class="form-control" id="txt_nro_cuota'+index+'" name="txt_nro_cuota[]" value="'+$.trim(r.nro_cuota)+'" placeholder="Nro" '+disabled+'></td>'+
            '<td>'+
                "<div class='input-group'>"+
                    "<div class='input-group-addon'>"+
                    "<i>"+costo+"</i>"+
                    "</div>"+
                    '<div id="txt_monto_cuota_ico'+index+'" class="has-'+alerta+' has-feedback">'+
                        "<input type='text' class='form-control'  id='txt_monto_cuota"+index+"' name='txt_monto_cuota[]'"+
                        " onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(\""+costo+"\",this,\""+index+"\");' value='"+$.trim(r.monto_cuota)+"' "+disabled+">"+
                        '<span class="glyphicon glyphicon-'+alerta+'-sign form-control-feedback"></span>'+
                    '</div>'+
                "</div>"+
                '<div id="i_monto_deuda_cuota_ico'+index+'" class="has-'+alerta+' has-feedback">'+
                    "<div class='input-group-addon'>"+
                    "<label>Deuda:</label>"+
                    "<label id='i_monto_deuda_cuota"+index+"'>"+saldo+"</label>"+
                    '<span class="glyphicon glyphicon-'+alerta+'-sign form-control-feedback"></span>'+
                    "</div>"+
                '</div>'+
            '</td>'+
            '<td><select class="form-control"  id="slct_tipo_pago_cuota'+index+'" name="slct_tipo_pago_cuota[]" '+disabled+'>'+
                '<option value=\'0\'>.::Seleccione::.</option>'+
                "<option value='1.1'>Transferencia - BCP</option>"+
                "<option value='1.2'>Transferencia - Scotiabank</option>"+
                "<option value='1.3'>Transferencia - BBVA</option>"+
                "<option value='1.4'>Transferencia - Interbank</option>"+
                "<option value='2.1'>Depósito - BCP</option>"+
                "<option value='2.2'>Depósito - Scotiabank</option>"+
                "<option value='2.3'>Depósito - BBVA</option>"+
                "<option value='2.4'>Depósito - Interbank</option>"+
                '<option value=\'3.0\'>Caja</option>'+
                '</select></td>'+
            '<td>'+
                '<input type="text"  readOnly class="form-control input-sm" id="pago_nombre_cuota'+index+'"  name="pago_nombre_cuota[]" value="">'+
                '<input type="text" style="display: none;" id="pago_archivo_cuota'+index+'" name="pago_archivo_cuota[]">'+
                '<label class="btn btn-default btn-flat margin btn-xs">'+
                    '<i class="fa fa-file-image-o fa-3x"></i>'+
                    '<i class="fa fa-file-pdf-o fa-3x"></i>'+
                    '<i class="fa fa-file-word-o fa-3x"></i>'+
                    '<input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,\'#pago_nombre_cuota'+index+'\',\'#pago_archivo_cuota'+index+'\',\'#pago_img_cuota'+index+'\');" >'+
                '</label>'+
                '<div>'+
                '<a id="pago_href'+index+'">'+
                '<img id="pago_img_cuota'+index+'" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a>'+
                '</div>'+botton+
            '</td>';
            $('#btnPagoCuota').css('display','block');
        
        html+="</tr>";
        $("#t_pago_cuota").append(html);
        if( $.trim(r.sucursal_id)!='' ){
            $("#slct_sucursal_"+index).val(r.sucursal_id);
        }
        if(r.tipo_pago!='0'){
            $('#slct_tipo_pago_cuota'+index).val(r.tipo_pago);
        }
        if( $.trim(r.archivo_cuota)!='' ){
            masterG.SelectImagen(r.archivo_cuota,'#pago_img_cuota'+index,'#pago_cuota'+index);
        }
        fecha_aux= r.fecha_cronograma;
    });//FIN FUNCTION

};

ValidaDeuda = function(c,t,id){
    $("#txt_monto_cuota_ico"+id+",#i_monto_deuda_cuota_ico"+id).removeClass('has-warning').addClass("has-success").find('span').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    var saldo= c*1 - $(t).val()*1;
    if( saldo>0 ){
        $("#txt_monto_cuota_ico"+id+",#i_monto_deuda_cuota_ico"+id).removeClass('has-success').addClass("has-warning").find('span').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
    if(saldo<0){
        saldo=0;
    }
    $("#i_monto_deuda_cuota"+id).text(saldo.toFixed(2));
}

guardarPagoCuota=function(id, matricula_id){
    if( $("#slct_sucursal_"+id).val()=='' ){
        msjG.mensaje('warning','Seleccione Sucursal',3000);
    }
    else if( $("#txt_nro_cuota"+id).val()=='' ){
        msjG.mensaje('warning','Ingrese el Nro del Recibo',3000);
    }
    else if( $("#txt_monto_cuota"+id).val()=='' ){
        msjG.mensaje('warning','Ingrese el Monto del Recibo',3000);
    }
    else if( $("#slct_tipo_pago_cuota"+id).val()=='0' ){
        msjG.mensaje('warning','Seleccione la forma de Pago',3000);
    }
    else if( $("#pago_archivo_cuota"+id).val()=='' ){
        msjG.mensaje('warning','Seleccione la imagen del Pago',3000);
    }
    else{
        AjaxEspecialidad.guardarPagoCuota(HTMLguardarPagoCuota, id, matricula_id);
    }
}

actualizarPagoCuota=function(id, matricula_id){
    if( $("#slct_sucursal_"+id).val()=='' ){
        msjG.mensaje('warning','Seleccione Sucursal',3000);
    }
    else if( $("#txt_nro_cuota"+id).val()=='' ){
        msjG.mensaje('warning','Ingrese el Nro del Recibo',3000);
    }
    else if( $("#txt_monto_cuota"+id).val()=='' ){
        msjG.mensaje('warning','Ingrese el Monto del Recibo',3000);
    }
    else if( $("#slct_tipo_pago_cuota"+id).val()=='0' ){
        msjG.mensaje('warning','Seleccione la forma de Pago',3000);
    }
    else{
        AjaxEspecialidad.actualizarPagoCuota(HTMLguardarPagoCuota, id, matricula_id);
    }
}

HTMLguardarPagoCuota=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEspecialidad.verMatriCuota(HTMLCargaMatriCuota, result.matricula_id, result.especialidad_programacion_id);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

btnregresar_curso = function(){
    $("#div_alumnos_mat").slideDown();
    $("#div_cursos_progra").slideUp();
    $("#div_tabla2_deta").slideUp();
    $("#div_pago_cuota").slideUp();
}

</script>
