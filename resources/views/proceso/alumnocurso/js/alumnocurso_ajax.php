<script type="text/javascript">
var AjaxAlumnoCurso={
    CargarAlumno:function(evento,pag){
        url='AjaxDinamic/Proceso.AlumnoPR@BuscarPersona';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Proceso.AlumnoPR@MisSeminarios';
        data={};
        masterG.postAjax(url,data,evento);
    }
};
</script>
