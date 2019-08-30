<script type="text/javascript">
var AjaxPersona={
    AgregarEditar:function(evento){
//        $("#ModalPersonaForm input[name='cargos_selec']").remove();
        $("#ModalPersonaForm").append("<input type='hidden' value='"+cargos_selec+"' name='cargos_selec'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.PersonaEM@NewVisitante';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.PersonaEM@EditVisitante';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#PersonaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#PersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#PersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@LoadVisitante';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalPersonaForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalPersonaForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalPersonaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalPersonaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@EditStatusVisitante';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursalandusuario';
        data={};
        masterG.postAjax(url,data,evento,null,false);
    },
    CargarMedioPublicitario:function(evento){
        url='AjaxDinamic/Mantenimiento.MedioPublicitarioMA@ListMedioPublicitario';
        data={};
        masterG.postAjax(url,data,evento,null,false);
    },
    ListarTipoLlamada:function(evento){
        url='AjaxDinamic/Mantenimiento.TipoLlamadaMA@ListTipoLlamada';
        data={plataforma:1};
        masterG.postAjax(url,data,evento);
    },
    ListarSubTipoLlamada:function(evento,id){
        url='AjaxDinamic/Mantenimiento.SubTipoLlamadaMA@ListSubTipoLlamada';
        data={tipo_llamada_id:id};
        masterG.postAjax(url,data,evento);
    },
    ListarDetalleTipoLlamada:function(evento,id){
        url='AjaxDinamic/Mantenimiento.DetalleTipoLlamadaMA@ListDetalleTipoLlamada';
        data={tipo_llamada_sub_id:id};
        masterG.postAjax(url,data,evento);
    },
};
</script>
