<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.PersonaEM@Load';
        masterG.postAjax(url,data,evento);
    },
    verCursos:function(evento, id){
        PersonaIdG=id;
        $("#div_alumnos_mat").slideUp();
        $("#div_cursos_progra").slideDown();

        dni = $("#btn_"+id).parents("tr").find(".dni").html();
        nombre = $("#btn_"+id).parents("tr").find(".nombre").html();
        paterno = $("#btn_"+id).parents("tr").find(".paterno").html();
        materno = $("#btn_"+id).parents("tr").find(".materno").html();

        email = $("#btn_"+id).parents("tr").find(".email").html();
        telefono = $("#btn_"+id).parents("tr").find(".telefono").html();
        celular = $("#btn_"+id).parents("tr").find(".celular").html();

        $("#btnpersonal").attr('onClick','ConfirmarEntregaPersonal('+PersonaIdG+')');
        
        $("#div_dni").html(dni);
        $("#div_nombres").html(nombre+' '+paterno+' '+materno);
        $("#div_email").html(email);
        $("#div_celular").html(telefono+' / '+celular);

        url='AjaxDinamic/Proceso.AlumnoPR@ListarSeminarios';
        data={persona_id:id};
        console.log(data);
        masterG.postAjax(url,data,evento);
    },
    RegistrarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@RegistrarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CerrarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@CerrarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CargarLlamada:function(evento){
        var data=$("#ModalEntregaForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@CargarLlamada';
        masterG.postAjax(url,data, evento);
    },
    CargarLlamadaPendiente:function(evento){
        var data={fecha_inicio:$("#txt_fecha_ini").val(),fecha_final:$("#txt_fecha_fin").val()};
        url='AjaxDinamic/Proceso.AlumnoPR@CargarLlamadaPendiente';
        masterG.postAjax(url,data, evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },
    
};
</script>
