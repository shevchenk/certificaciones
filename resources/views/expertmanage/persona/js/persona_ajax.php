<script type="text/javascript">
var AjaxPersona={
    AgregarEditar:function(evento){
        $("#ModalPersonaForm input[name='cargos_selec']").remove();
        $("#ModalPersonaForm").append("<input type='hidden' value='"+cargos_selec+"' name='cargos_selec'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.PersonaEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.PersonaEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#PersonaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#PersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#PersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.PersonaEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalPersonaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalPersonaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/ExpertManage.PersonaEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarPrivilegio:function(evento){
        url='AjaxDinamic/ExpertManage.PersonaEM@ListPrivilegio';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/ExpertManage.SucursalEM@ListSucursal';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarAreas:function(evento,persona){
        url='AjaxDinamic/ExpertManage.PersonaEM@CargarAreas';
        data={persona_id:persona};
        masterG.postAjax(url,data,evento);
    },
};
</script>
