<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@Load';
        masterG.postAjax(url,data,evento);
    },
    verCursos:function(evento, id){
        PersonaIdG=id;
        $("#div_alumnos_mat").slideUp();
        $("#div_cursos_progra").slideDown();

        dni = $("#btn_"+id).parents("tr").find("td").eq(0).html();
        nombre = $("#btn_"+id).parents("tr").find("td").eq(1).html();
        paterno = $("#btn_"+id).parents("tr").find("td").eq(2).html();
        materno = $("#btn_"+id).parents("tr").find("td").eq(3).html();

        email = $("#btn_"+id).parents("tr").find("td").eq(4).html();
        celular = $("#btn_"+id).parents("tr").find("td").eq(6).html();
        
        $("#div_dni").html(dni);
        $("#div_nombres").html(nombre+' '+paterno+' '+materno);
        $("#div_email").html(email);
        $("#div_celular").html(celular);

        url='AjaxDinamic/Proceso.AlumnoPR@ListarSeminarios';
        data={alumno_id:id};
        masterG.postAjax(url,data,evento);
    },
    RegistrarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@RegistrarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CargarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@CargarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },
    
};
</script>
