<script type="text/javascript">
var AjaxListaespecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaespecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#ListaespecialidadForm").append("<input type='hidden' value='"+$("#ModalMatriculaForm #txt_persona_id").val()+"' name='persona_id'>");
        data=$("#ListaespecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaespecialidadForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidadDisponible';
        masterG.postAjax(url,data,evento);
    }
};
</script>
