<script type="text/javascript">
var AjaxProgramacion={
    AgregarEditar:function(evento){
        var data=$("#ModalMatriculaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.MatriculaPR@New';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursalandusuario';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
