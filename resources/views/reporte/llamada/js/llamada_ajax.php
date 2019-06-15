<script type="text/javascript">
var AjaxLlamada={
    ListarVendedor:function(evento){
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@ListarTeleoperadores';
        data={};
        masterG.postAjax(url,data,evento);
    },
    ListarFuente:function(evento){
        url='AjaxDinamic/Mantenimiento.PersonaEM@ListarFuente';
        data={};
        masterG.postAjax(url,data,evento);
    },
    ListarTipo:function(evento){
        url='AjaxDinamic/Mantenimiento.PersonaEM@ListarTipo';
        data={};
        masterG.postAjax(url,data,evento);
    },
    ListarEmpresa:function(evento){
        url='AjaxDinamic/Mantenimiento.PersonaEM@ListarEmpresa';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
