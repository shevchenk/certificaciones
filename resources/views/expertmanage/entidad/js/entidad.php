<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var EntidadG={id:0,
persona_id:0,
persona:'',
entidad:"",
ruc:"",
nombre_comercial:"",
igv:"",
cambio_moneda:"",
//celular:"",

estado:1}; // Datos Globales
$(document).ready(function() {
    $("#TableEntidad").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    AjaxEntidad.Cargar(HTMLCargarEntidad);
    $("#EntidadForm #TableEntidad select").change(function(){ AjaxEntidad.Cargar(HTMLCargarEntidad); });
    $("#EntidadForm #TableEntidad input").blur(function(){ AjaxEntidad.Cargar(HTMLCargarEntidad); });

    $('#ModalEntidad').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEntidadForm").append("<input type='hidden' value='"+EntidadG.id+"' name='id'>");
        }

        $('#ModalEntidadForm #txt_persona_id').val( EntidadG.persona_id );
        $('#ModalEntidadForm #txt_persona').val( EntidadG.persona );
        $('#ModalEntidadForm #txt_entidad').val( EntidadG.entidad );
        $('#ModalEntidadForm #txt_ruc').val( EntidadG.ruc );
        $('#ModalEntidadForm #txt_nombre_comercial').val( EntidadG.nombre_comercial );
        $('#ModalEntidadForm #txt_igv').val( EntidadG.igv );
        $('#ModalEntidadForm #txt_cambio_moneda').val( EntidadG.cambio_moneda );
        //$('#ModalEntidadForm #txt_celular').val( EntidadG.celular );
       
        $('#ModalEntidadForm #slct_estado').val( EntidadG.estado );
        $('#ModalEntidadForm #txt_persona_id').focus();
    });

    $('#ModalEntidad').on('hidden.bs.modal', function (event) {
        $("ModalEntidadForm input[type='hidden']").not('.mant').remove();
        $("ModalEntidadForm input").val('');
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEntidadForm #txt_persona_id").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Persona',4000);
    }
    else if( $.trim( $("#ModalEntidadForm #txt_entidad").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Entidad',4000);
    }
    else if( $.trim( $("#ModalEntidadForm #txt_ruc").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese RUC',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EntidadG.id='';
    EntidadG.persona_id='0';
    EntidadG.persona='';
    EntidadG.entidad='';
    EntidadG.ruc='';
    EntidadG.nombre_comercial='';
    EntidadG.igv='';
    EntidadG.cambio_moneda='';
    //EntidadG.celular='';
    
    EntidadG.estado='1';
    if( val==0 ){
        EntidadG.id=id;
        EntidadG.persona_id=$("#TableEntidad #trid_"+id+" .persona_id").val();
        EntidadG.persona=$("#TableEntidad #trid_"+id+" .persona").text();
        EntidadG.entidad=$("#TableEntidad #trid_"+id+" .entidad").text();
        EntidadG.ruc=$("#TableEntidad #trid_"+id+" .ruc").text();
        EntidadG.nombre_comercial=$("#TableEntidad #trid_"+id+" .nombre_comercial").text();
        EntidadG.igv=$("#TableEntidad #trid_"+id+" .igv").val();
        EntidadG.cambio_moneda=$("#TableEntidad #trid_"+id+" .cambio_moneda").val();
        //EntidadG.celular=$("#TableEntidad #trid_"+id+" .celular").val();
        
        EntidadG.estado=$("#TableEntidad #trid_"+id+" .estado").val();

    }
    $('#ModalEntidad').modal('show');
}

CambiarEstado=function(estado,id){
    AjaxEntidad.CambiarEstado(HTMLCambiarEstado,estado,id);
}

HTMLCambiarEstado=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxEntidad.Cargar(HTMLCargarEntidad);
    }
}

AgregarEditarAjax=function(){
    if( ValidaForm() ){
        AjaxEntidad.AgregarEditar(HTMLAgregarEditar);
    }
}

HTMLAgregarEditar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        $('#ModalEntidad').modal('hide');
        AjaxEntidad.Cargar(HTMLCargarEntidad);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

HTMLCargarEntidad=function(result){
    var html="";
    $('#TableEntidad').DataTable().destroy();

    $.each(result.data.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
             "<td class='persona'>"+r.paterno+' '+r.materno+' '+r.nombre+"</td>"+
            "<td class='entidad'>"+r.entidad+"</td>"+
            "<td class='ruc'>"+r.ruc+"</td>"+
            "<td class='nombre_comercial'>"+r.nombre_comercial+"</td>"+
            "<td>"+
            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='igv' value='"+r.igv+"'>"+
            "<input type='hidden' class='cambio_moneda' value='"+r.cambio_moneda+"'>"+
           // "<input type='hidden' class='celular' value='"+r.celular+"'>"+
            
            "<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+
            "</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableEntidad tbody").html(html); 
    $("#TableEntidad").DataTable({
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
            $('#TableEntidad_paginate ul').remove();
            masterG.CargarPaginacion('HTMLCargarEntidad','AjaxEntidad',result.data,'#TableEntidad_paginate');
        }
    });
};
</script>
