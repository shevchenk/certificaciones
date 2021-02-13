<script type="text/javascript">
var ComentarioG='';
$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    AjaxAlumnoCurso.CargarAlumno(HTMLCargarAlumno);
    AjaxAlumnoCurso.CargarCurso(HTMLCargarCurso);

    $('#ModalComentario').on('shown.bs.modal', function (event) {
        $(this).find('.modal-footer .btn-primary').text('Confirmar').attr('onClick','ValidarComentario();');
        $("#ModalComentarioForm").append("<input type='hidden' value='"+ComentarioG+"' name='id'>");
        $("#ModalComentarioForm #txt_comentario").focus();
    });

    $('#ModalComentario').on('hidden.bs.modal', function (event) {
        $("#ModalComentarioForm input[type='hidden']").not('.mant').remove();
        $("#ModalComentarioForm #txt_comentario").val('');
    });
});

HTMLCargarAlumno=function(result){
        $("#txt_paterno").val( result.data.paterno );
        $("#txt_materno").val( result.data.materno );
        $("#txt_nombre").val( result.data.nombre );
        $("#txt_dni").val( result.data.dni );
}

HTMLCargarCurso=function(result){
    var html="";
    $("#t_matricula tbody").html(html);
    
    $.each(result.data,function(index,r){
        html="<tr id='tr"+r.id+"'>";
        html+="<td>"+r.modalidad+"</td>";
        html+="<td class='curso'>"+r.curso+"</td>";
        html+="<td>"+r.profesor+"</td>";
        html+="<td class='fecha'>"+r.fecha+"</td>";
        html+="<td>"+r.horario+"</td>";
        html+="<td>"+r.sucursal+"</td>";
        if( $.trim(r.link)!='' ){
            colorbtn='google';
            if(r.validavideo*1>0){
                colorbtn='success';
            }
            html+="<td><a class='btn btn-lg btn-"+colorbtn+"' onClick='ValidarVideo("+r.id+")' href='"+r.link+"' target='__blank'><i class='fa fa-youtube-play'></i></a></td>";
        }
        else{
            html+="<td>&nbsp;</td>";
        }

        if( $.trim(r.comentario)!='' ){
            html+="<td><textarea rows='4' cols='30' class='form-control' disabled>ss"+$.trim(r.comentario)+"</textarea></td>";
        }
        else{
            html+="<td><a class='btn btn-lg btn-dropbox' onClick='ComentarSeminario("+r.id+")'><i class='fa fa-pencil-square-o'></i></a></td>";
        }

        if( r.deuda_total * 1 > 0 ){
            html += '<td><div class="alert alert-danger" role="alert">'+
                        'Tiene deuda pendiente'+
                    '</div></td>';
        }
        else if( r.deuda_total * 1 == 0 && $.trim(r.archivo_certificado) == '' ){
            html += '<td><div class="alert alert-success" role="alert">'+
                        'Estamos generando su certificado'+
                    '</div></td>';
        }
        else{
            html+="<td> Click para descargar"+
                '<a id="pago_href'+index+'">'+
                    '<img id="pago_img'+index+'" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                '</a>'+
            "</td>";
        }

        html+="</tr>";
        $("#t_matricula tbody").append(html);
        if( $.trim(r.archivo_certificado) != '' ){
            masterG.SelectImagen(r.archivo_certificado, "#pago_img"+index, "#pago_href"+index);
        }
    });
};

ValidarVideo=function(id){
    AjaxAlumnoCurso.ValidarVideo(id);
}

ValidarComentario=function(){
    var cantidad= $("#ModalComentarioForm #txt_comentario").val().length;
    if( $("#ModalComentarioForm #txt_fecha").val()!='' && cantidad>=50 && cantidad<=750){
        AjaxAlumnoCurso.ValidarComentario(ComentarioG,$("#ModalComentarioForm #txt_comentario").val(),HTMLConfirmacion);
    }
    else if( cantidad<50 ){
        msjG.mensaje('warning','El comentario, debe contener un mínimo de 50 caracteres',4000);
    }
    else if( cantidad>750 ){
        msjG.mensaje('warning','El comentario, debe contener un máximo de 750 caracteres',4000);
    }
    else{
        msjG.mensaje('warning','Ingrese Comentario del Seminario',4000);
    }
}

HTMLConfirmacion=function(){
    $('#ModalComentario').modal('hide');
    msjG.mensaje('success','Comentario Confirmado',4000);
    AjaxAlumnoCurso.CargarCurso(HTMLCargarCurso);
}

ComentarSeminario=function(id){
    ComentarioG=id;
    $("#ModalComentarioForm #txt_curso").val( $("#tr"+id+" .curso").text() );
    $("#ModalComentarioForm #txt_fecha").val( $("#tr"+id+" .fecha").text() );
    $('#ModalComentario').modal('show');
}
</script>
