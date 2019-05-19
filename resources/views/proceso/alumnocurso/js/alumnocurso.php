<script type="text/javascript">
$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    });

    AjaxAlumnoCurso.CargarAlumno(HTMLCargarAlumno);
    AjaxAlumnoCurso.CargarCurso(HTMLCargarCurso);
});

HTMLCargarAlumno=function(result){
        $("#txt_paterno").val( result.data.paterno );
        $("#txt_materno").val( result.data.materno );
        $("#txt_nombre").val( result.data.nombre );
        $("#txt_dni").val( result.data.dni );
}

HTMLCargarCurso=function(result){
    var html="";
    
    $.each(result.data,function(index,r){
        html+="<tr>";
        html+="<td>"+r.modalidad+"</td>";
        html+="<td>"+r.curso+"</td>";
        html+="<td>"+r.profesor+"</td>";
        html+="<td>"+r.fecha+"</td>";
        html+="<td>"+r.horario+"</td>";
        html+="<td>"+r.sucursal+"</td>";
        html+="</tr>";
    });
    $("#t_matricula tbody").html(html); 
};
</script>
