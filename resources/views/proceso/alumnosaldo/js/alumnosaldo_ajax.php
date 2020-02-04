<script type="text/javascript">
var AjaxEspecialidad={
    GuardarPago:function(evento,pag){
        data=$("#form_saldos").serialize().split("txt_").join("").split("slct_").join("");
        $("#form_saldos input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@SaveSaldos';
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#EspecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#EspecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#EspecialidadForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.AlumnoPR@LoadSaldos';
        masterG.postAjax(url,data,evento);
    },
    verCursos:function(evento, id){
        PersonaIdG=id;
        $("#div_alumnos_mat").slideUp();
        $("#div_cursos_progra").slideDown();

        dni = $("#btn_"+id).parents("tr").find("td").eq(0).html();
        paterno = $("#btn_"+id).parents("tr").find("td").eq(1).html();
        materno = $("#btn_"+id).parents("tr").find("td").eq(2).html();
        nombre = $("#btn_"+id).parents("tr").find("td").eq(3).html();

        curso = $("#btn_"+id).parents("tr").find("td").eq(4).html();
        saldo = $("#btn_"+id).parents("tr").find("td").eq(5).html();
        
        $("#div_dni").html(dni);
        $("#div_nombres").html(paterno+' '+materno+' '+nombre);
        $("#div_curso").html(curso);
        $("#div_saldo").html(saldo);

        url='AjaxDinamic/Proceso.AlumnoPR@ListarSaldos';
        data={matricula_detalle_id:id};
        masterG.postAjax(url,data,evento);
    },
    verSaldos:function(evento, cuota, id){
        PersonaIdG=id;
        $("#div_alumnos_mat").slideUp();
        $("#div_cursos_progra").slideDown();

        dni = $("#btn_c"+cuota).parents("tr").find("td").eq(0).html();
        paterno = $("#btn_c"+cuota).parents("tr").find("td").eq(1).html();
        materno = $("#btn_c"+cuota).parents("tr").find("td").eq(2).html();
        nombre = $("#btn_c"+cuota).parents("tr").find("td").eq(3).html();

        curso = $("#btn_c"+cuota).parents("tr").find("td").eq(4).html();
        saldo = $("#btn_c"+cuota).parents("tr").find("td").eq(5).html();
        
        $("#div_dni").html(dni);
        $("#div_nombres").html(paterno+' '+materno+' '+nombre);
        $("#div_curso").html(curso);
        $("#div_saldo").html(saldo);

        url='AjaxDinamic/Proceso.AlumnoPR@ListarSaldos';
        data={cuota:cuota, matricula_id:id};
        masterG.postAjax(url,data,evento);
    }
};
</script>
