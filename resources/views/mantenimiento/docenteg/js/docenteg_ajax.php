<script type="text/javascript">
var AjaxPersona={
    AgregarEditar:function(evento){
//        $("#ModalPersonaForm input[name='cargos_selec']").remove();
        $("#ModalPersonaForm").append("<input type='hidden' value='"+cargos_selec+"' name='cargos_selec'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.DocenteEM@NewPersona';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.DocenteEM@EditPersona';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#PersonaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#PersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#PersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.DocenteEM@LoadDocente';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id,persona_id){
        $("#ModalPersonaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalPersonaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        $("#ModalPersonaForm").append("<input type='hidden' value='"+persona_id+"' name='persona_id'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.DocenteEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
