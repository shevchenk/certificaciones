<script type="text/javascript">
var AjaxAlumnoCurso={
    CargarAlumno:function(evento,pag){
        url='AjaxDinamic/Proceso.AlumnoPR@BuscarPersona';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Proceso.AlumnoPR@MisCursos';
        data={};
        masterG.postAjax(url,data,evento);
    },
    ValidarVideo:function(id){
        url='AjaxDinamic/Proceso.AlumnoPR@ValidarVideo';
        data={id:id};
        masterG.postAjax(url,data);
    },
    ValidarComentario:function(id,texto,evento){
        url='AjaxDinamic/Proceso.AlumnoPR@ValidarComentario';
        data={id:id,comentario:texto};
        masterG.postAjax(url,data,evento);
    }
};
</script>
