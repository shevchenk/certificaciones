<script type="text/javascript">
var AjaxEspecialidad={
    /*
    AgregarEditar:function(evento){
        var data=$("#ModalEspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.EspecialidadEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    */
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@Load';
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
    verCursos:function(evento, id){
        $("#div_alumnos_mat").slideUp();
        $("#div_cursos_progra").slideDown();

        dni = $("#btn_"+id).parents("tr").find("td").eq(0).html();
        nombre = $("#btn_"+id).parents("tr").find("td").eq(1).html();
        paterno = $("#btn_"+id).parents("tr").find("td").eq(2).html();
        materno = $("#btn_"+id).parents("tr").find("td").eq(3).html();

        email = $("#btn_"+id).parents("tr").find("td").eq(4).html();
        telefono = $("#btn_"+id).parents("tr").find("td").eq(5).html();
        celular = $("#btn_"+id).parents("tr").find("td").eq(6).html();
        direccion = $("#btn_"+id).parents("tr").find("td").eq(7).html();
        
        $("#div_dni").html(dni);
        $("#div_nombres").html(nombre+' '+paterno+' '+materno);
        $("#div_email").html(email);
        $("#div_telefono").html(telefono);
        $("#div_celular").html(celular);
        $("#div_direccion").html(direccion);

        url='AjaxDinamic/Proceso.AlumnoPR@ListarSeminarios';
        data={alumno_id:id};
        masterG.postAjax(url,data,evento);
    },
    guardarNotasProg:function(evento){
        var data=$("#frmcursoprogramdos").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@guardarNotas';
        masterG.postAjax(url,data, evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    }
    
};
</script>
