<script type="text/javascript">
var AjaxEmpleado={
    AgregarEditar:function(evento){
        var data=$("#ModalEmpleadoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.EmpleadoEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.EmpleadoEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EmpleadoForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EmpleadoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EmpleadoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.EmpleadoEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEmpleadoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEmpleadoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEmpleadoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEmpleadoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.EmpleadoEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/ExpertManage.SucursalEM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCargo:function(evento){
        url='AjaxDinamic/ExpertManage.CargoEM@ListCargo';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
