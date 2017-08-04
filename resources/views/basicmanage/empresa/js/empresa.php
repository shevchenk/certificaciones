<script type="text/javascript">
var AddEdit=0; //0: Editar | 1: Agregar
var EmpresaG={id:0,
persona_id:0,
persona:'',
razon_social:"",
ruc:"",
nombre_comercial:"",
direccion_fiscal:"",
telefono:"",
celular:"",
email:"",
imagen_nombre:"",
foto:"",
imagen_archivo:"",
estado:1}; // Datos Globales
$(document).ready(function() {
     $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
     AjaxEmpresa.Cargar(HTMLCargarEmpresa);

    $('#ModalEmpresa').on('shown.bs.modal', function (event) {
        if( AddEdit==1 ){
            $(this).find('.modal-footer .btn-primary').text('Guardar').attr('onClick','AgregarEditarAjax();');
        }
        else{
            
            $(this).find('.modal-footer .btn-primary').text('Actualizar').attr('onClick','AgregarEditarAjax();');
            $("#ModalEmpresaForm").append("<input type='hidden' value='"+EmpresaG.id+"' name='id'>");
        }

        $('#ModalEmpresaForm #txt_persona').val( EmpresaG.persona );
        $('#ModalEmpresaForm #txt_persona_id').val( EmpresaG.persona_id );
        
        $('#ModalEmpresaForm #txt_razon_social').val( EmpresaG.razon_social );
        $('#ModalEmpresaForm #txt_ruc').val( EmpresaG.ruc );
        $('#ModalEmpresaForm #txt_nombre_comercial').val( EmpresaG.nombre_comercial );
        $('#ModalEmpresaForm #txt_direccion_fiscal').val( EmpresaG.direccion_fiscal );
        $('#ModalEmpresaForm #txt_telefono').val( EmpresaG.telefono );
        $('#ModalEmpresaForm #txt_celular').val( EmpresaG.celular );
        $('#ModalEmpresaForm #txt_email').val( EmpresaG.email );
        $('#ModalEmpresaForm #slct_estado').val( EmpresaG.estado );
        $('#ModalEmpresaForm #txt_imagen_nombre').val(EmpresaG.imagen_nombre);
        $('#ModalEmpresaForm #txt_imagen_archivo').val('');
        $('#ModalEmpresaForm .img-circle').attr('src',EmpresaG.imagen_archivo);
        $('#ModalEmpresaForm #txt_razon_social').focus();
    });

    $('#ModalEmpresa').on('hidden.bs.modal', function (event) {
        $("#ModalEmpresaForm input[type='hidden']").not('.mant').remove();
    });
});

ValidaForm=function(){
    var r=true;
    if( $.trim( $("#ModalEmpresaForm #txt_persona_id").val() )=='0' ){
        r=false;
        msjG.mensaje('warning','Seleccione Persona',4000);
    }
    else if( $.trim( $("#ModalEmpresaForm #txt_razon_social").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese Razon Social',4000);
    }
    else if( $.trim( $("#ModalEmpresaForm #txt_ruc").val() )=='' ){
        r=false;
        msjG.mensaje('warning','Ingrese RUC',4000);
    }
    return r;
}

AgregarEditar=function(val,id){
    AddEdit=val;
    EmpresaG.id='';
    EmpresaG.persona_id='0';
    EmpresaG.persona='';
    EmpresaG.razon_social='';
    EmpresaG.ruc='';
    EmpresaG.nombre_comercial='';
    EmpresaG.direccion_fiscal='';
    EmpresaG.telefono='';
    EmpresaG.celular='';
    EmpresaG.email='';
    EmpresaG.imagen_nombre='';
    EmpresaG.imagen_archivo='';
    EmpresaG.estado='1';
    if( val==0 ){
        EmpresaG.id=id;
        EmpresaG.persona_id=$("#TableEmpresa #trid_"+id+" .persona_id").val();
        EmpresaG.persona=$("#TableEmpresa #trid_"+id+" .persona").text();
        EmpresaG.razon_social=$("#TableEmpresa #trid_"+id+" .razon_social").text();
        EmpresaG.ruc=$("#TableEmpresa #trid_"+id+" .ruc").text();
        EmpresaG.nombre_comercial=$("#TableEmpresa #trid_"+id+" .nombre_comercial").text();
        EmpresaG.direccion_fiscal=$("#TableEmpresa #trid_"+id+" .direccion_fiscal").val();
        EmpresaG.telefono=$("#TableEmpresa #trid_"+id+" .telefono").val();
        EmpresaG.celular=$("#TableEmpresa #trid_"+id+" .celular").val();
        EmpresaG.email=$("#TableEmpresa #trid_"+id+" .email").text();
        EmpresaG.foto=$("#TableEmpresa #trid_"+id+" .foto").val();
        
        if(EmpresaG.foto!='undefined'){
            EmpresaG.imagen_archivo='img/empres/'+EmpresaG.foto;
            EmpresaG.imagen_nombre=EmpresaG.foto;
        }else {
            EmpresaG.imagen_archivo='';
            EmpresaG.imagen_nombre='';
        } 
        EmpresaG.estado=$("#TableEmpresa #trid_"+id+" .estado").val();

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

    $.each(result.data,function(index,r){
        estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(1,'+r.id+')" class="btn btn-danger">Inactivo</span>';
        if(r.estado==1){
            estadohtml='<span id="'+r.id+'" onClick="CambiarEstado(0,'+r.id+')" class="btn btn-success">Activo</span>';
        }

        html+="<tr id='trid_"+r.id+"'>"+
            "<td>";
            if(r.foto!=null){    
            html+="<a  target='_blank' href='img/empres/"+r.foto+"'><img src='img/empres/"+r.foto+"' style='height: 40px;width: 40px;'></a>";}
            html+="</td>"+
            "<td class='persona'>"+r.paterno+' '+r.materno+' '+r.nombre+"</td>"+
            "<td class='razon_social'>"+r.razon_social+"</td>"+
            "<td class='ruc'>"+r.ruc+"</td>"+
            "<td class='nombre_comercial'>"+r.nombre_comercial+"</td>"+
            "<td class='email'>"+r.email+"</td>"+
            "<td>"+
            

            "<input type='hidden' class='persona_id' value='"+r.persona_id+"'>"+
            "<input type='hidden' class='direccion_fiscal' value='"+r.direccion_fiscal+"'>"+
            "<input type='hidden' class='telefono' value='"+r.telefono+"'>"+
            "<input type='hidden' class='celular' value='"+r.celular+"'>";
            
            //"<td>";
            if(r.foto!=null){
            html+="<input type='hidden' class='foto' value='"+r.foto+"'>";}
            html+="<input type='hidden' class='estado' value='"+r.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" onClick="AgregarEditar(0,'+r.id+')"><i class="fa fa-edit fa-lg"></i> </a></td>';
        html+="</tr>";
    });
    $("#TableEmpresa tbody").html(html); 
    $("#TableEmpresa").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
        
    });
};

onImagen = function (event) {
        var files = event.target.files || event.dataTransfer.files;
        if (!files.length)
            return;
        var image = new Image();
        var reader = new FileReader();
        reader.onload = (e) => {
            $('#ModalEmpresaForm #txt_imagen_archivo').val(e.target.result);
            $('#ModalEmpresaForm .img-circle').attr('src',e.target.result);
        };
        reader.readAsDataURL(files[0]);
        $('#ModalEmpresaForm #txt_imagen_nombre').val(files[0].name);
        console.log(files[0].name);
    };
</script>
