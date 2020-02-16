<script type="text/javascript">
var AjaxPersonaModal={
    CargarPersonaAdicional:function(evento){
        url='AjaxDinamic/Mantenimiento.PersonaEM@LoadAdicional';
        data={persona_id:PersonaG.id};
        masterG.postAjax(url,data,evento);
    },
};
</script>
