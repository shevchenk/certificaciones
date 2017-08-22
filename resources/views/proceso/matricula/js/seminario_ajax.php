<script type="text/javascript">
var AjaxMatricula={
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
    CargarRegion:function(evento){
        url='AjaxDinamic/Mantenimiento.RegionEM@ListRegion';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarProvincia:function(evento,region_id){
        url='AjaxDinamic/Mantenimiento.RegionEM@ListProvincia';
        data={region_id:region_id};
        masterG.postAjax(url,data,evento);
    },
    CargarDistrito:function(evento,provincia_id){
        url='AjaxDinamic/Mantenimiento.RegionEM@ListDistrito';
        data={provincia_id:provincia_id};
        masterG.postAjax(url,data,evento);
    },
    CargarTipoParticipante:function(evento){
        url='AjaxDinamic/Mantenimiento.TipoParticipanteEM@ListTipoParticipante';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
