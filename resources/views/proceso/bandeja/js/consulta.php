<script type="text/javascript">
let MatriculaG = {
    Valida: [], Historica:[], Id:'', Estado_Mat: '', Observacion: '',
    BtnAuxSi: '<a class="btn btn-flat btn-info btn-lg" href="#" target="blank"><i class="fa fa-download fa-lg"></i></a>',
    BtnAuxNo: '<a class="btn btn-flat btn-danger btn-lg"><i class="fa fa-ban fa-lg"></i></a>',
    BtnExportar: '<a class="btn btn-info" onClick="Detalle.ExportarFicha(#)"><i class="fa fa-file-pdf-o fa-lg"></i>',
};
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
                r.programacion.push( r2.split("|")[1] +" | "+ r2.split("|")[4] +" | "+ r2.split("|")[2] );
            })
            btnaux = '';
            if( r.estado_mat == 'Pre Aprobado' || r.estado_mat == 'Aprobado' || r.estado_mat == 'Registrado' ){
                btnaux = MatriculaG.BtnExportar.replace("#", r.id);
            }
            html+=  "<tr id='trid_"+r.id+"'>"+
                        "<td class='fecha_matricula'>"+r.fecha_matricula+"</td>"+
                        "<td class='marketing'>"+r.marketing+"</td>"+
                        "<td class='alumno'>"+ r.paterno + " " + r.materno+ " " + r.nombre +"</td>"+
                        "<td class='formacion'>"+r.formacion+"</td>"+
                        "<td class='curso'><ul><li>"+r.curso.join("</li><li>")+"</li></ul></td>"+
                        "<td class='programacion'><ul><li>"+r.programacion.join("</li><li>")+"</li></ul></td>"+
                        "<td class='estado_mat'>"+r.estado_mat+"</td>"+
                        "<td class='fecha_estado'>"+r.fecha_estado+"</td>"+
                        '<td><a class="btn btn-primary" onClick="Detalle.Visualizar('+r.id+', \'Valida\')"><i class="fa fa-edit fa-lg"></i> </a>'+btnaux+'</td>'+
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
                r.programacion.push( r2.split("|")[1] +" | "+ r2.split("|")[4] +" | "+ r2.split("|")[2] );
            })
            btnaux = '';
            if( r.estado_mat == 'Pre Aprobado' || r.estado_mat == 'Aprobado' || r.estado_mat == 'Registrado' ){
                btnaux = MatriculaG.BtnExportar.replace("#", r.id);
            }
            html+=  "<tr id='trid_"+r.id+"'>"+
                        "<td class='fecha_matricula'>"+r.fecha_matricula+"</td>"+
                        "<td class='marketing'>"+r.marketing+"</td>"+
                        "<td class='alumno'>"+ r.paterno + " " + r.materno+ " " + r.nombre +"</td>"+
                        "<td class='formacion'>"+r.formacion+"</td>"+
                        "<td class='curso'><ul><li>"+r.curso.join("</li><li>")+"</li></ul></td>"+
                        "<td class='programacion'><ul><li>"+r.programacion.join("</li><li>")+"</li></ul></td>"+
                        "<td class='estado_mat'>"+r.estado_mat+"</td>"+
                        "<td class='fecha_estado'>"+r.fecha_estado+"</td>"+
                        '<td><a class="btn btn-primary" onClick="Detalle.Visualizar('+r.id+',\'Historica\')"><i class="fa fa-edit fa-lg"></i> </a>'+btnaux+'</td>'+
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
        const d = new Date();
        let time = d.getTime();

        $("#archivo_inscripcion").addClass('btn-danger').removeClass('btn-info').removeAttr('href');
        $("#archivo_inscripcion i").addClass('fa-ban').removeClass('fa-download');
        if( Matricula.archivo_pago_inscripcion != '' ){
            $("#archivo_inscripcion").addClass('btn-info').removeClass('btn-danger').attr('href', Matricula.archivo_pago_inscripcion+"?time="+time);
            $("#archivo_inscripcion i").addClass('fa-download').removeClass('fa-ban');
        }

        $("#archivo_matricula").addClass('btn-danger').removeClass('btn-info').removeAttr('href');
        $("#archivo_matricula i").addClass('fa-ban').removeClass('fa-download');
        if( Matricula.archivo_pago_matricula != '' ){
            $("#archivo_matricula").addClass('btn-info').removeClass('btn-danger').attr('href', Matricula.archivo_pago_matricula+"?time="+time);
            $("#archivo_matricula i").addClass('fa-download').removeClass('fa-ban');
        }

        $("#archivo_promocion").addClass('btn-danger').removeClass('btn-info').removeAttr('href');
        $("#archivo_promocion i").addClass('fa-ban').removeClass('fa-download');
        if( Matricula.archivo_pago_promocion != '' ){
            $("#archivo_promocion").addClass('btn-info').removeClass('btn-danger').attr('href', Matricula.archivo_pago_promocion+"?time="+time);
            $("#archivo_promocion i").addClass('fa-download').removeClass('fa-ban');
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
                        "<td>"+Matricula.inicio[key]+"</td>"+
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
                btnaux = MatriculaG.BtnAuxNo;
                if( Matricula.archivo.split(",")[key] != '' ){
                    btnaux = MatriculaG.BtnAuxSi.replace("#", Matricula.archivo.split(",")[key]+"?time="+time);
                }
                html+=  "<tr>"+
                            "<td>"+Matricula.curso[key]+"</td>"+
                            "<td>"+value+"</td>"+
                            "<td>"+Matricula.monto_pago.split(",")[key]+"</td>"+
                            "<td>"+Matricula.tipo_pago.split(",")[key]+"</td>"+
                            "<td>"+btnaux+"</td>"+
                        "</tr>";
            }
        });
        html+=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td class='text-right' colspan='2'>Total:</td>"+
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
            let importe = 0; //Pendiente
            let comprometido = 0;
            let pagado = 0;
            
            if( $.trim(r.saldo)!='' ){
                ds = r.curso;
                importe = r.saldo*1;
                comprometido = r.precio*1;
            }
            else{
                comprometido = r.monto_cronograma*1;
            }

            if( $.trim(r.salcd)!='' && r.tipo_matricula == 1 ){
                ds = r.cuotacd+' / FV:'+r.fecha_cronograma;
                importe = r.salcd*1;
            }
            else if( $.trim(r.cuota_cronograma)!='' && r.tipo_matricula == 1){
                ds = r.cuota_cronograma+' / FV:'+r.fecha_cronograma;
                importe = r.monto_cronograma*1;
            }
            else if( $.trim(r.salcd)!='' && r.tipo_matricula == 2 ){
                ds = r.cuotacd;
                importe = r.salcd*1;
            }
            else if( $.trim(r.cuota_cronograma)!='' && r.tipo_matricula == 2){
                ds = r.cuota_cronograma;
                importe = r.monto_cronograma*1;
            }

            pagado = comprometido - importe;

            if( index == 0 ){
                if( $.trim(r.salsi)!='' ){
                    html+=  "<tr id='trid_"+index+"_0'>"+
                                "<td>Inscripción</td>"+
                                "<td>"+$.trim(r.presi)+"</td>"+
                                "<td>"+(r.presi*1 - r.salsi*1).toFixed(2)+"</td>"+
                                "<td>"+$.trim(r.salsi)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsi)*1;
                }

                if( $.trim(r.salsm)!='' ){
                    html+=  "<tr id='trid_"+index+"_0m'>"+
                                "<td>Matrícula</td>"+
                                "<td>"+$.trim(r.presm)+"</td>"+
                                "<td>"+(r.presm*1 - r.salsm*1).toFixed(2)+"</td>"+
                                "<td>"+$.trim(r.salsm)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsm)*1;
                }
            }

            if( importe*1>0 ){
                html+=  "<tr id='trid_"+index+"'>"+
                            "<td>"+$.trim(ds)+"</td>"+
                            "<td>"+comprometido.toFixed(2)+"</td>"+
                            "<td>"+pagado.toFixed(2)+"</td>"+
                            "<td>"+importe.toFixed(2)+"</td>"+
                        "</tr>";
                total+= $.trim(importe)*1;
            }
        });
        html+=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td colspan='3' class='text-right'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .deudas").html(html);
    },
    HTMLLoadCuotas: (result) => {
        let html = '';
        let total = 0;
        const d = new Date();
        let time = d.getTime();
        $.each(result.data,function(index,r){
            let importe = r.monto_cuota;
            if( importe*1>0 ){
                btnaux = MatriculaG.BtnAuxNo;
                if( $.trim(r.archivo_cuota) != '' ){
                    btnaux = MatriculaG.BtnAuxSi.replace("#", r.archivo_cuota+"?time="+time);
                }
                html+=  "<tr>"+
                            "<td>"+$.trim(r.cuota)+"</td>"+
                            //"<td>"+$.trim( (r.programado.split("-")[1]*1).toFixed(2) )+"</td>"+
                            "<td>"+$.trim(r.nro_cuota)+"</td>"+
                            "<td>"+$.trim(importe)+"</td>"+
                            "<td>"+$.trim(r.tipo_pago_cuota)+"</td>"+
                            "<td>"+btnaux+"</td>"+
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
        else if(result.rst == 2){
            msjG.mensaje('warning','Usuario responsable no registrado en el Software de Procesos',4000);
        }
        else if(result.rst == 3){
            msjG.mensaje('warning','Local de estudios no registrado en el Software de Procesos',4000);
        }
        else if(result.rst == 4){
            msjG.mensaje('warning','Alumno no pudo registrarse, verifique que su email sea único en el Software de Procesos',6000);
        }
        else{
            msjG.mensaje('warning','No se pudo generar el expediente, vuelva a intentarlo',4000);
        }
    },
    ExportarFicha: (id) =>{
        window.open('ReportDinamic/Reporte.PDFRE@ExportMatricula?matricula_id='+id, '_blank');
    }
}
</script>
