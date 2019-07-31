<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var MedioPublicitarioG={id:0,medio_publicitario:'',estado:1}; // Datos Globales

$(document).ready(function() {
    $("#TableMedioPublicitario").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxMedioPublicitario.Cargar(HTMLCargarMedioPublicitario);
    $("#MedioPublicitarioForm #TableMedioPublicitario select").change(function(){ AjaxMedioPublicitario.Cargar(HTMLCargarMedioPublicitario); });
    $("#MedioPublicitarioForm #TableMedioPublicitario input").blur(function(){ AjaxMedioPublicitario.Cargar(HTMLCargarMedioPublicitario); });

    $('#ModalMedioPublicitario').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalMedioPublicitarioForm").append("<input type='hidden' value='"+MedioPublicitarioG.id+"' name='id'>");
        }

        $('#ModalMedioPublicitarioForm #txt_medio_publicitario').val( MedioPublicitarioG.medio_publicitario );
        
        $('#ModalMedioPublicitarioForm #slct_estado').selectpicker('val', MedioPublicitarioG.estado );
        $('#ModalMedioPublicitarioForm #txt_medio_publicitario').focus();
    });

    $('#ModalMedioPublicitario').on('hidden.bs.modal', function (event) {
        $("#ModalMedioPublicitarioForm input[type='hidden']").not('.mant').remove();
       // $("ModalMedioPublicitarioForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalMedioPublicitarioForm #txt_medio_publicitario").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Tipo Evaluación',4000);
    }

    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    MedioPublicitarioG.id='';
    MedioPublicitarioG.medio_publicitario='';
    MedioPublicitarioG.estado='1';
    if( val==0 ){
        MedioPublicitarioG.id=id;
        MedioPublicitarioG.medio_publicitario=$("#TableMedioPublicitario #trid_"+id+" .medio_publicitario").text();
        MedioPublicitarioG.estado=$("#TableMedioPublicitario #trid_"+id+" .estado").val();
    }
    $('#ModalMedioPublicitario').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxMedioPublicitario.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxMedioPublicitario.Cargar(HTMLCargarMedioPublicitario);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxMedioPublicitario.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalMedioPublicitario').modal('hide');
        AjaxMedioPublicitario.Cargar(HTMLCargarMedioPublicitario);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarMedioPublicitario=function(result){
    var html="";
    $('#TableMedioPublicitario').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='medio_publicitario'>"+r.medio_publicitario+"</td>"+
            "<td>";
        html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableMedioPublicitario tbody").html(html); 
    $("#TableMedioPublicitario").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthMedioPublicitario": [10],
        "language": {
            "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
            "infoEmpty": "No éxite registro(s) aún",
        },
        "initComplete": function () {
            $('#TableMedioPublicitario_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarMedioPublicitario','AjaxMedioPublicitario',result.data,'#TableMedioPublicitario_paginate');
        }
    });
};

</script>
