<script type="text/javascript">
var AjaxTipoParticipante={
    AgregarEditar:function(evento){
        var data=$("#ModalTipoParticipanteForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.TipoParticipanteEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.TipoParticipanteEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#TipoParticipanteForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#TipoParticipanteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#TipoParticipanteForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TipoParticipanteEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalTipoParticipanteForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalTipoParticipanteForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalTipoParticipanteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalTipoParticipanteForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.TipoParticipanteEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
