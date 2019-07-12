<script type="text/javascript">
var AjaxEspecialidad={
    AgregarEditar:function(evento){
        var data=$("#ModalEspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.EspecialidadEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalEspecialidadForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalEspecialidadForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalEspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalEspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarEspecialidadCurso:function(evento,id){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@CargarEspecialidadCurso';
        data={id:id};
        masterG.postAjax(url,data,evento);
    }
};
</script>
