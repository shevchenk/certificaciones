<script type="text/javascript">
var AjaxAEPersona={
    AgregarEditar2:function(evento){
//        $("#ModalPersonaForm input[name='cargos_selec']").remove();
        $("#ModalPersonaForm").append("<input type='hidden' value='"+cargos_selec+"' name='cargos_selec'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.PersonaEM@New';
        if(AddEdit2==0){
            url='AjaxDinamic/Mantenimiento.PersonaEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    CargarPrivilegio:function(evento){
        url='AjaxDinamic/Mantenimiento.PersonaEM@ListPrivilegio';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento,null,false);
    },
    CargarAreas:function(evento,persona){
        url='AjaxDinamic/Mantenimiento.PersonaEM@CargarAreas';
        data={persona_id:persona};
        masterG.postAjax(url,data,evento);
    },

};
</script>
