<script type="text/javascript">
var AjaxEmpleado={
    AgregarEditar:function(evento){
        var data=$("#ModalEmpleadoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/BasicManage.EmpleadoBM@New';
        if(AddEdit==0){
            url='AjaxDinamic/BasicManage.EmpleadoBM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        data={};
        url='AjaxDinamic/BasicManage.EmpleadoBM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEmpleadoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEmpleadoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEmpleadoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEmpleadoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.EmpleadoBM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/BasicManage.SucursalBM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCargo:function(evento){
        url='AjaxDinamic/BasicManage.CargoBM@ListCargo';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
