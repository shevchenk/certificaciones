<script type="text/javascript">
var AjaxEspecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@Load';
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
    verMatriculas:function(evento, id, tc){
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

        url='AjaxDinamic/Proceso.MatriculaRectificaPR@verMatriculas';
        data={alumno_id:id, tipo_curso:tc,especialidad_matricula:1};
        masterG.postAjax(url,data,evento);
    },
    verMatriDeta:function(evento, id){
        $("#div_tabla2_deta").slideDown();

        url='AjaxDinamic/Proceso.MatriculaRectificaPR@verDetaMatriculas';
        data={matricula_id:id};
        masterG.postAjax(url,data,evento);
    },
    verMatriCuota:function(evento, id, epid){
        $("#div_pago_cuota").slideDown();

        url='AjaxDinamic/Proceso.MatriculaRectificaPR@verMatriculaCuota';
        data={matricula_id:id, especialidad_programacion_id:epid};
        masterG.postAjax(url,data,evento);
    },
    anularMatricula:function(evento, id){
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@EditEspecialidadStatus';
        data={id:id};
        masterG.postAjax(url,data,evento);
    },
    anularDetalleMatricula:function(evento, id){
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@EditDetalleEspecialidadStatus';
        data={id:id};
        masterG.postAjax(url,data,evento,null,false);
    },
    actualizarMatriDeta:function(id, id_progra){
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@UpdateMatriDeta';
        data={id:id, programacion_id:id_progra};
        masterG.postAjax(url,data,null,null,false);
    },
    actualizarPagosDM:function(evento){
        var data=$("#ModalPagosMDForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@UpdatePagosDM';
        masterG.postAjax(url,data,evento,null,false);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },
    guardarPagoCuota:function(evento,index,matricula_id){
        $("#frm_pago_cuota").append("<input type='hidden' value='"+index+"' name='index'>");
        $("#frm_pago_cuota").append("<input type='hidden' value='"+matricula_id+"' name='matricula_id'>");
        var data=$("#frm_pago_cuota").serialize().split("txt_").join("").split("slct_").join("");
        $("#frm_pago_cuota input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@guardarPagoCuota';
        masterG.postAjax(url,data,evento,null,false);
    },
    actualizarPagoCuota:function(evento,index,matricula_id){
        $("#frm_pago_cuota").append("<input type='hidden' value='"+index+"' name='index'>");
        $("#frm_pago_cuota").append("<input type='hidden' value='"+matricula_id+"' name='matricula_id'>");
        var data=$("#frm_pago_cuota").serialize().split("txt_").join("").split("slct_").join("");
        $("#frm_pago_cuota input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.MatriculaRectificaPR@actualizarPagoCuota';
        masterG.postAjax(url,data,evento,null,false);
    },
    ListSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursalandusuario';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
