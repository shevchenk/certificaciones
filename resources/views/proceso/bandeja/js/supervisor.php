<script type="text/javascript">
let MatriculaG = {Valida: [], Historica:[], Id:'', Estado_Mat: '', Observacion: ''};
$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: true,
        time:true,
        minView:2,
        autoclose: true,
        todayBtn: false
    }).on('change', (e) => { 
        id = e.target.parentNode.parentNode.parentNode.parentNode.getAttribute("id");
        if( id == "TableBandejaValida" ){
            AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida);
        }
        else{
            AjaxBandeja.BandejaHistorica(Historica.HTMLBandejaHistorica);
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab
        $("#FormBandeja").hide(500);
    });

    $("#TableBandejaHistorica, #TableBandejaValida").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    $("#TableBandejaHistorica #txt_fecha_estado, #TableBandejaValida #txt_fecha_estado").val("<?php echo date("Y-m-d");?>");

    AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida);
    $("#BandejaValidaForm #TableBandejaValida select").change(() => { AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida); });
    $("#BandejaValidaForm #TableBandejaValida input").not(".fecha").blur(() => { AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida); });

    AjaxBandeja.BandejaHistorica(Historica.HTMLBandejaHistorica);
    $("#BandejaHistoricaForm #TableBandejaHistorica select").change(() => { AjaxBandeja.BandejaHistorica(Historica.HTMLBandejaHistorica); });
    $("#BandejaHistoricaForm #TableBandejaHistorica input").not(".fecha").blur(() => { AjaxBandeja.BandejaHistorica(Historica.HTMLBandejaHistorica); });

    $("#FormBandeja .Aprobado").click(()=>{
        MatriculaG.Estado_Mat = 'Pre Aprobado';
        MatriculaG.Observacion = '';
        $("#FormBandeja .observacion").val('');
        let msj = '<h2 class="alert-success"> Confirmar para PRE APROBAR la inscripción </h2>';
        msjG2.question('¿Desea Continuar?', msj, () => { AjaxBandeja.ActualizaEstadoMat(Detalle.HTMLActualizaEstadoMat); });
    });

    $("#FormBandeja .Observado").click(()=>{
        MatriculaG.Estado_Mat = 'Observado';
        MatriculaG.Observacion = $.trim( $("#FormBandeja .observacion").val() );
        if( MatriculaG.Observacion == '' ){
            msjG.mensaje('warning','Ingrese la descripción de la observación',3000);
            $("#FormBandeja .observacion").focus();
        }
        else{
            let msj = '<h2 class="alert-warning"> Confirmar para OBSERVAR la inscripción </h2>';
            msjG2.question('¿Desea Continuar?', msj, () => { AjaxBandeja.ActualizaEstadoMat(Detalle.HTMLActualizaEstadoMat); });
        }
    });

    $("#FormBandeja .Anulado").click(()=>{
        MatriculaG.Estado_Mat = 'Anulado';
        MatriculaG.Observacion = $.trim( $("#FormBandeja .observacion").val() );
        if( MatriculaG.Observacion == '' ){
            msjG.mensaje('warning','Ingrese la descripción de la anulación',3000);
            $("#FormBandeja .observacion").focus();
        }
        else{
            let msj = '<h2 class="alert-danger"> Confirmar para ANULAR la inscripción </h2>';
            msjG2.question('¿Desea Continuar?', msj, () => { AjaxBandeja.ActualizaEstadoMat(Detalle.HTMLActualizaEstadoMat); });
        }
    });


});
let Valida = {
    HTMLBandejaValida: (result) => {
        var html="";
        MatriculaG.Valida = [];
        $("#FormBandeja").hide(500);
        $('#TableBandejaValida').DataTable().destroy();
    
        $.each(result.data.data, (index,r) => {
            r.curso = [];
            r.frecuencia = [];
            r.horario = [];
            r.turno = [];
            r.inicio = [];
            r.programacion = [];
            $.each( r.detalle.split("^^"), (ind, r2) =>{
                r.curso.push( r2.split("|")[0] );
                r.frecuencia.push( r2.split("|")[1] );
                r.horario.push( r2.split("|")[2] );
                r.turno.push( r2.split("|")[3] );
                r.inicio.push( r2.split("|")[4] );
                r.programacion.push( r2.split("|")[1] +" | "+ r2.split("|")[2] );
            })
            html+=  "<tr id='trid_"+r.id+"'>"+
                        "<td class='fecha_matricula'>"+r.fecha_matricula+"</td>"+
                        "<td class='marketing'>"+r.marketing+"</td>"+
                        "<td class='alumno'>"+ r.paterno + " " + r.materno+ " " + r.nombre +"</td>"+
                        "<td class='formacion'>"+r.formacion+"</td>"+
                        "<td class='curso'><ul><li>"+r.curso.join("</li><li>")+"</li></ul></td>"+
                        "<td class='programacion'><ul><li>"+r.programacion.join("</li><li>")+"</li></ul></td>"+
                        "<td class='estado_mat'>"+r.estado_mat+"</td>"+
                        "<td class='fecha_estado'>"+r.fecha_estado+"</td>"+
                        '<td><a class="btn btn-primary" onClick="Detalle.Visualizar('+r.id+', \'Valida\')"><i class="fa fa-edit fa-lg"></i> </a></td>'+
                    "</tr>";
            MatriculaG.Valida[r.id] = r;
        });
        $("#TableBandejaValida tbody").html(html); 
        $("#TableBandejaValida").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "lengthEmpresa": [10],
            "language": {
                "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
                "infoEmpty": "No éxite registro(s) aún",
            },
            "initComplete": function () {
                $('#TableBandejaValida_paginate ul').remove();
                masterG.CargarPaginacion('Valida.HTMLBandejaValida','AjaxBandeja.BandejaValida',result.data,'#TableBandejaValida_paginate');
            }
        });        
    },
}

let Historica = {
    HTMLBandejaHistorica: (result) => {
        var html="";
        MatriculaG.Historica = [];
        $("#FormBandeja").hide(500);
        $('#TableBandejaHistorica').DataTable().destroy();

        $.each(result.data.data, (index,r) => {
            r.curso = [];
            r.frecuencia = [];
            r.horario = [];
            r.turno = [];
            r.inicio = [];
            r.programacion = [];
            $.each( r.detalle.split("^^"), (ind, r2) =>{
                r.curso.push( r2.split("|")[0] );
                r.frecuencia.push( r2.split("|")[1] );
                r.horario.push( r2.split("|")[2] );
                r.turno.push( r2.split("|")[3] );
                r.inicio.push( r2.split("|")[4] );
                r.programacion.push( r2.split("|")[1] +" | "+ r2.split("|")[2] );
            })
            html+=  "<tr id='trid_"+r.id+"'>"+
                        "<td class='fecha_matricula'>"+r.fecha_matricula+"</td>"+
                        "<td class='marketing'>"+r.marketing+"</td>"+
                        "<td class='alumno'>"+ r.paterno + " " + r.materno+ " " + r.nombre +"</td>"+
                        "<td class='formacion'>"+r.formacion+"</td>"+
                        "<td class='curso'><ul><li>"+r.curso.join("</li><li>")+"</li></ul></td>"+
                        "<td class='programacion'><ul><li>"+r.programacion.join("</li><li>")+"</li></ul></td>"+
                        "<td class='estado_mat'>"+r.estado_mat+"</td>"+
                        "<td class='fecha_estado'>"+r.fecha_estado+"</td>"+
                        '<td><a class="btn btn-primary" onClick="Detalle.Visualizar('+r.id+',\'Historica\')"><i class="fa fa-edit fa-lg"></i> </a></td>'+
                    "</tr>";
            MatriculaG.Historica[r.id] = r;
        });
        $("#TableBandejaHistorica tbody").html(html); 
        $("#TableBandejaHistorica").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "lengthEmpresa": [10],
            "language": {
                "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
                "infoEmpty": "No éxite registro(s) aún",
            },
            "initComplete": function () {
                $('#TableBandejaHistorica_paginate ul').remove();
                masterG.CargarPaginacion('Historica.HTMLBandejaHistorica','AjaxBandeja.BandejaHistorica',result.data,'#TableBandejaHistorica_paginate');
            }
        });
    },
}

let Detalle = {
    Visualizar: (id, tipo) => {
        let Matricula = {};
        let html = '';
        MatriculaG.Id = id;
        
        $("#FormBandeja").show(500);
        $("#FormBandeja .btns").hide(1000);
        if( tipo == 'Valida' ){
            $("#FormBandeja .btns").show(1500);
            Matricula = MatriculaG.Valida[id];
        }
        else{
            Matricula = MatriculaG.Historica[id];
        }
        
        $.each( Matricula, (key, value) => {
            $("#FormBandeja ."+key).text( $.trim(value) );
        });
        /*****************************************************************/
        html = '';
        $.each( Matricula.curso, (key, value) =>{
            html+=  "<tr>"+
                        "<td>"+value+"</td>"+
                        "<td>"+Matricula.frecuencia[key]+"</td>"+
                        "<td>"+Matricula.horario[key]+"</td>"+
                    "</tr>";
        });
        $("#FormBandeja .cursos").html(html);
        /*****************************************************************/
        /*****************************************************************/
        let total = 0;
        html = '';
        $.each( $.trim(Matricula.nro_pago).split(","), (key, value) =>{
            total += Matricula.monto_pago.split(",")[key]*1;
            if( value != '' && Matricula.monto_pago.split(",")[key]*1 > 0 ){
                html+=  "<tr>"+
                            "<td>"+value+"</td>"+
                            "<td>"+Matricula.monto_pago.split(",")[key]+"</td>"+
                            "<td>"+Matricula.tipo_pago.split(",")[key]+"</td>"+
                        "</tr>";
            }
        });
        html+=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td class='text-right'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .curso_pagos").html(html);
        /*****************************************************************/
        AjaxBandeja.LoadPagos(Detalle.HTMLLoadPagos);
        AjaxBandeja.LoadCuotas(Detalle.HTMLLoadCuotas);
        msjG.mensaje('info','Matricula seleccionada:'+ id,3000);
    },
    HTMLLoadPagos: (result) => {
        let html = '';
        let total = 0;
        $.each(result.data,function(index,r){
            let ds = '';
            let ddc = '';
            let importe = 0;
            
            if( $.trim(r.saldo)!='' ){
                ds = 'Curso';
                importe = r.saldo;
            }

            if( $.trim(r.salcd)!='' ){
                ds = r.cuotacd;
                importe = r.salcd;
            }
            else if( $.trim(r.cuota_cronograma)!='' ){
                ddc = r.cuota_cronograma+' / FV:'+r.fecha_cronograma;
                importe = r.monto_cronograma;
            }

            if( index == 0 ){
                if( $.trim(r.salsi)!='' ){
                    html+=  "<tr id='trid_"+index+"_0'>"+
                                "<td>Inscripción</td>"+
                                "<td>&nbsp;</td>"+
                                "<td>"+$.trim(r.salsi)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsi)*1;
                }

                if( $.trim(r.salsm)!='' ){
                    html+=  "<tr id='trid_"+index+"_0m'>"+
                                "<td>Matrícula</td>"+
                                "<td>&nbsp;</td>"+
                                "<td>"+$.trim(r.salsm)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsm)*1;
                }
            }

            if( importe*1>0 ){
                html+=  "<tr id='trid_"+index+"'>"+
                            "<td>"+$.trim(ds)+"</td>"+
                            "<td>"+$.trim(ddc)+"</td>"+
                            "<td>"+$.trim(importe)+"</td>"+
                        "</tr>";
                total+= $.trim(importe)*1;
            }
        });
        html+=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td>&nbsp;</td>"+
                    "<td class='text-right'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .deudas").html(html);
    },
    HTMLLoadCuotas: (result) => {
        let html = '';
        let total = 0;
        $.each(result.data,function(index,r){
            let importe = r.monto_cuota;
            if( importe*1>0 ){
                html+=  "<tr>"+
                            "<td>"+$.trim(r.cuota)+"</td>"+
                            "<td>"+$.trim(r.nro_cuota)+"</td>"+
                            "<td>"+$.trim(importe)+"</td>"+
                            "<td>"+$.trim(r.tipo_pago_cuota)+"</td>"+
                        "</tr>";
                total+= $.trim(importe)*1;
            }
        });
        html+=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td>&nbsp;</td>"+
                    "<td class='text-right'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .cuotas").html(html);
    },
    HTMLActualizaEstadoMat: (result) =>{
        if(result.rst == 1){
            $("#FormBandeja .observacion").val('');
            msjG.mensaje('success','Se proceso correctamente',3000);
            AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida);
        }
    },
}
</script>
