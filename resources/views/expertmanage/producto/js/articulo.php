<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var ArticuloG={id:0,articulo:"",categoria_id:0,estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableArticulo").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $("#ArticuloForm #TableArticulo select").change(function(){ AjaxArticulo.Cargar(HTMLCargarArticulo2); });
    $("#ArticuloForm #TableArticulo input").blur(function(){ AjaxArticulo.Cargar(HTMLCargarArticulo2); });
    
    $('#ModalArticulo').on('shown.bs.modal', function (event) {
        
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax2();');
        }
        else{
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax2();');
            $("#ModalArticuloForm").append("<input type='hidden' value='"+ArticuloG.id+"' name='id'>");
        }

        $('#ModalArticuloForm #txt_articulo').val( ArticuloG.articulo );
        $('#ModalArticuloForm #slct_categoria').val( ArticuloG.categoria_id );
        $('#ModalArticuloForm #slct_estado').val( ArticuloG.estado );
        $("#ModalArticuloForm select").selectpicker('refresh');
        $('#ModalArticuloForm #txt_articulo').focus();
    });

    $('#ModalArticulo').on('hidden.bs.modal', function (event) {
        $("#ModalArticuloForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm2=function(){
    var r=true;
    if( $.trim( $("#ModalArticuloForm #txt_articulo").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Articulo',4000);
    }
    else if( $.trim( $("#ModalArticuloForm #slct_categoria").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Categoria',4000);
    }
    return r;
}

AgregarEditar2=function(val,id){
    AddEdit=val;
    ArticuloG.id='';
    ArticuloG.articulo='';
    ArticuloG.categoria_id='0';
    ArticuloG.estado='1';
    if( val==0 ){
        ArticuloG.id=id;
        ArticuloG.articulo=$("#TableArticulo #trid_"+id+" .articulo").text();
        ArticuloG.categoria_id=$("#TableArticulo #trid_"+id+" .categoria_id").val();
        ArticuloG.estado=$("#TableArticulo #trid_"+id+" .estado").val();
    }
    $('#ModalArticulo').modal('show');
}

CambiarEstado2=function(estado,id){
    AjaxArticulo.CambiarEstado(HTMLCambiarEstado2,estado,id);
}

HTMLCambiarEstado2=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxArticulo.Cargar(HTMLCargarArticulo2);
        CargarSlct(3);
    }
}

AgregarEditarAjax2=function(){
    if( ValidaForm2() ){
        AjaxArticulo.AgregarEditar(HTMLAgregarEditar2);
    }
}

HTMLAgregarEditar2=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalArticulo').modal('hide');
        AjaxArticulo.Cargar(HTMLCargarArticulo2);
        CargarSlct(3);
    }    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarArticulo2=function(result){
    var html="";
    $('#TableArticulo').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado2(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado2(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td class='articulo'>"+r.articulo+"</td>"+
            "<td><input type='hidden' class='categoria_id' value='"+r.categoria_id+"'>"+r.categoria+"</td>"+
            "<td><input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar2(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableArticulo tbody").html(html); 
    $("#TableArticulo").DataTable({
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
            $('#TableArticulo_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarArticulo2','AjaxArticulo',result.data,'#TableArticulo_paginate');
        }
    });
};

SlctCargarCategoria=function(result){
    var html="<option value='0'>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.categoria+"</option>";
    });
    $("#ModalArticulo #slct_categoria").html(html); 

};
</script>
