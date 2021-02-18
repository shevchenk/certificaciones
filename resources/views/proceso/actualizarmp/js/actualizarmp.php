<script type="text/javascript">
$(document).ready(function() {

    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        startView:2,
        autoclose: true,
        todayBtn: false
    });

    $('#spn_fecha_ini').on('click', function(){
        $('#txt_fecha_inicial').focus();
    });
    $('#spn_fecha_fin').on('click', function(){
        $('#txt_fecha_final').focus();
    });

    
    $("#TableReporte").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });
    
    $("#PaeForm #btn_generar").click(function (){
        $("#MPForm #txt_paterno").val( $("#PaeForm #txt_paterno").val() );
        $("#MPForm #txt_materno").val( $("#PaeForm #txt_materno").val() );
        $("#MPForm #txt_nombre").val( $("#PaeForm #txt_nombre").val() );
        $("#MPForm #txt_dni").val( $("#PaeForm #txt_dni").val() );
        if( !$("#MPForm").hasClass('hidden') ){
            $("#MPForm").addClass("hidden");
        }
        MPAjax.Cargar(MP.HTMLCargarReporte);
    });

    $("#MPForm #btn_DividirMP").click(function (){
        MP.Dividir();
    });

    $("#MPForm #btn_ActualizarMP").click(function (){
        MP.Actualizar();
    });

    MPAjax.CargarEspecialidad(MP.HTMLCargarEspecialidad);
    MPAjax.CargarCurso(MP.HTMLCargarCurso);
});

var MP = {
    HTMLCargarEspecialidad : (result) => {
        var html='';
        $.each(result.data,function(index,r){
            html+='<option value="'+r.id+'">'+r.especialidad+'</option>';
        })
        
        $("#PaeForm #slct_especialidad").html(html);
        $("#MPForm #slct_especialidad_0").html("<option value = '' selected>.::Seleccione Carrera / Módulo::.</option>"+html);
        $("#PaeForm #slct_especialidad, #MPForm #slct_especialidad_0").selectpicker('refresh');
    },

    HTMLCargarCurso : (result) => {
        var html='';
        $.each(result.data,function(index,r){
            html+='<option value="'+r.id+'">'+r.curso+'</option>';
        })
        $("#PaeForm #slct_curso").html(html);
        $("#PaeForm #slct_curso").selectpicker('refresh');
    },

    ListarProgramacion : (t) => {
        let pos = $(t).attr("id").split("_")[2];
        let id = $(t).val();
        if( id != '' ){
            data = { pos: pos, especialidad_id: id }
            MPAjax.CargarProgramacion(MP.HTMLCargarProgramacion, data);
        }
        else{
            let result = { pos:0, data:[] };
            MP.HTMLCargarProgramacion(result);
        }
    },

    HTMLCargarProgramacion : (result) => {
        var html='';
        $.each(result.data,function(index,r){
            html+='<option value="'+r.programacion_id+'_'+r.curso_id+'">'
                        + r.curso
                        + ' ||| Local: '+ $.trim(r.local) 
                        + ' ||| Frec: '+ $.trim(r.frecuencia)
                        + ' ||| Horario: '+ $.trim(r.horario)
                        + ' ||| Inicio: '+ $.trim(r.inicio)
                    +'</option>';
        });
        
        $("#MPForm #slct_programacion_"+ result.pos).html("<option value = '' selected>.::Seleccione Inicio / Curso::.</option>"+html);
        $("#MPForm #slct_programacion_"+ result.pos).selectpicker('refresh');
    },

    HTMLCargarReporte : (result) => {
        var html="";
        $('#PaeForm #TableReporte').DataTable().destroy();
        $("#PaeForm #TableReporte tbody").html('');

        $.each(result.data,function(index,r){
            html =  "<tr id='trid_"+r.programacion_id+"_"+r.especialidad_id*1+"'>"+
                        "<td>"+r.tipo_formacion+"</td>"+
                        "<td>"+r.formacion+"</td>"+
                        "<td>"+r.curso+"</td>"+
                        "<td>"+$.trim(r.local)+"</td>"+
                        "<td>"+$.trim(r.frecuencia)+"</td>"+
                        "<td>"+$.trim(r.horario)+"</td>"+
                        "<td>"+$.trim(r.turno)+"</td>"+
                        "<td>"+$.trim(r.inicio)+"</td>"+
                        
                        "<td>"+$.trim(r.cant)+"</td>"+    
                        "<td class='not'><a class='btn btn-default btn-sm' onClick='MP.Procesar("+r.programacion_id+", "+r.especialidad_id*1+")'>"+
                                "<i class='glyphicon glyphicon-book fa-lg'></i>"+
                            "</a>"+
                        "</td>"+
                    "</tr>";

            $("#PaeForm #TableReporte tbody").append(html);
        });

        $("#PaeForm #TableReporte").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    },

    Procesar : (programacion_id, especialidad_id) => {
        $("#MPForm").removeClass("hidden");//Aqui vamos a mostrar la ventana escondida
        var html = "<tr>"+$("#trid_"+programacion_id+"_"+especialidad_id).html()+"</tr>";
        $("#MPTable tbody").html(html);
        $("#MPTable tbody tr").children("td.not").remove();
        $("#MPForm #txt_programacion_id").val(programacion_id);
        $("#MPForm #txt_especialidad_id").val(especialidad_id);
        $("#MPForm .validar").css('display','none');
        if( especialidad_id == 0 ){
            data = { pos: 0, especialidad_id: 0 }
            MPAjax.CargarProgramacion(MP.HTMLCargarProgramacion, data);
        }
        else{
            $("#MPForm .validar").css('display','block');
            let result = { pos:0, data:[] };
            MP.HTMLCargarProgramacion(result);
        }

        $("#MPForm #txt_cant").val(1);// Se genera
        $("#btn_DividirMP").click();
    },

    Dividir : () => {
        $("#MPForm .agregados fieldset").remove();
        $("#MPForm select").selectpicker('destroy');
        $("#MPForm select").val('');
        $("#MPForm input").not('.mant').val('');
        let cant = $("#MPForm #txt_cant").val();
        let html = '';
        for (let index = 1; index < cant; index++) {
            html = $("#MPForm .plantilla").html().replace(/_0/g,"_"+index).replace(/Bloque #1/g, "Bloque #"+(index*1+1));
            $("#MPForm .agregados").append(html);
        }
        $("#MPForm select").selectpicker('refresh');
    },

    validarActualizar : () => {
        let r = true;
        let canttotal = 0;
        let especialidad_id = $("#MPForm #txt_especialidad_id").val();
        let cant = $("#MPTable tbody tr").children("td:last").text();
        let cantbloque = $("#MPForm .agregados fieldset").length + 1;

        for (let index = 0; index < cantbloque; index++) {
            canttotal += $("#MPForm #txt_cant_"+index).val()*1; 
            if( $.trim( $("#MPForm #txt_cant_"+index).val() ) == '' && r == true){
                msjG.mensaje('warning','Ingrese la cantidad a actualizar del bloque #'+(index+1),5000);
                r = false;
            }
            else if( especialidad_id != 0  && $("#MPForm #slct_especialidad_"+index).val() == '' && r == true){
                msjG.mensaje('warning','Seleccione la especialidad a actualizar del bloque #'+(index+1),5000);
                r = false;
            }
            else if( $("#MPForm #slct_programacion_"+index).val() == '' && r == true){
                msjG.mensaje('warning','Seleccione la programación a actualizar del bloque #'+(index+1),5000);
                r = false;
            }
        }

        if( canttotal > cant && r == true ){
            msjG.mensaje('warning','Cantidad total('+canttotal+') no puede ser superior a la cantidad asignada('+cant+')',5000);
            r = false;
        }
        return r;
    },

    Actualizar : () => {
        if( MP.validarActualizar() ){
            MPAjax.Actualizar(MP.HTMLActualizar);
        }
    },

    HTMLActualizar : (result) => {
        if( result.rst==1 ){
            msjG.mensaje('success',result.msj,4000);
            $("#PaeForm #btn_generar").click();
        }
        else if( result.rst==2 ){
            msjG.mensaje('warning',result.msj,4000);
        }
    }
}


</script>
