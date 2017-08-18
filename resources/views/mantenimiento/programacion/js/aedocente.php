<script type="text/javascript">
var AddEdit1=0; 
var DocenteG={id:0,docente:"",persona_id:0,estado:1}; // 

$(document).ready(function() {
    $("#TableDocente").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $('#ModalDocente').on('shown.bs.modal', function (event) {

        if( AddEdit1==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax1();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax1();');
            $("#ModalDocenteForm").append("<input type='hidden' value='"+DocenteG.id+"' name='id'>");
        }

        $('#ModalDocenteForm #txt_persona_id').val( DocenteG.persona_id );
        $('#ModalDocenteForm #txt_docente').val( DocenteG.docente );
        $('#ModalDocenteForm #slct_estado').val( DocenteG.estado );
        $("#ModalDocenteForm select").selectpicker('refresh');
        $('#ModalDocenteForm #txt_docente').focus();
    });

    $('#ModalDocente').on('hidden.bs.modal', function (event) {
        $("#ModalDocenteForm input[type='hidden']").not(".mant").remove();
        $("#ModalDocenteForm input").val('');
    });
});

ValidaForm1=function(){
    var r=true;
    if( $.trim( $("#ModalDocenteForm #txt_persona_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
   
    return r;
}

AgregarEditar1=function(val,id){
    AddEdit1=val;
    DocenteG.id='';
    DocenteG.persona_id='';
    DocenteG.docente='';
    DocenteG.estado='1';
    if( val==0 ){

        DocenteG.id=id;
        DocenteG.persona_id=$("#TableListadocente #trid_"+id+" .persona_id").val();
        DocenteG.docente=$("#TableListadocente #trid_"+id+" .docente").text();
        DocenteG.estado=$("#TableListadocente #trid_"+id+" .estado").val();
      
    }
    $('#ModalDocente').modal('show');
}

AgregarEditarAjax1=function(){
    if( ValidaForm1() ){
        AjaxAEDocente.AgregarEditar1(HTMLAgregarEditar1);
    }
}

HTMLAgregarEditar1=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalDocente').modal('hide');
        AjaxListadocente.Cargar(HTMLCargarDocente);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}
</script>
