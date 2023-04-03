<script type="text/javascript">
let MatriculaG = {
    Valida: [], Historica:[], Programacion:[], 
    Id:'', Estado_Mat: '', Observacion: '', Tipo: '', TotalIns: 0, TotalMat: 0, LDfiltrosG: '', Programacion_Id: '', Btn:'Default',
    TotalCuo: [], TotalCur: [], TotalIds: [],
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

    AjaxBandeja.CargarBanco(Valida.SlctCargarBanco);;

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
        MatriculaG.Btn = 'Aprobado';
        MatriculaG.Estado_Mat = 'Pendiente';
        MatriculaG.Observacion = '';
        $("#FormBandeja .observacion").val('');
        let msj = '<h2 class="alert-success"> Confirmar para pasar a PENDIENTE la inscripción </h2>';
        msjG2.question('¿Desea Continuar?', msj, () => { AjaxBandeja.ActualizaEstadoMat(Detalle.HTMLActualizaEstadoMat); });
    });

    $("#FormBandeja .Actualizar").click(()=>{
        MatriculaG.Btn = 'Actualizar';
        $("#FormBandeja .observacion").val('');
        valida = true;
        if( MatriculaG.TotalIns < $("#txt_monto_pago_inscripcion").val()*1 ){
            valida = false;
            msjG.mensaje('warning','Monto de la inscripción no puede superar el monto máximo de: '+ MatriculaG.TotalIns,5000);
        }
        else if( MatriculaG.TotalMat < $("#txt_monto_pago_matricula").val()*1 ){
            valida = false;
            msjG.mensaje('warning','Monto de la matrícula no puede superar el monto máximo de: '+ MatriculaG.TotalMat,5000);
        }

        MatriculaG.TotalIds.forEach( (val, index) => {
            if( typeof(MatriculaG.TotalCuo[val]) != 'undefined' && MatriculaG.TotalCuo[val] < $("#"+val).val()*1  && valida ){
                valida = false;
                msjG.mensaje('warning','Monto de la cuota #'+val.split("_")[1]+' no puede superar el monto máximo de: '+ MatriculaG.TotalCuo[val],5000);
            }
            else if( typeof(MatriculaG.TotalCur[val]) != 'undefined' && MatriculaG.TotalCur[val] < $("#"+val).val()*1  && valida ){
                valida = false;
                msjG.mensaje('warning','Monto del curso #'+(index+1)+' no puede superar el monto máximo de: '+ MatriculaG.TotalCur[val],5000);
            }
        });


        if(valida){
            let msj = '<h2 class="alert-info"> Confirmar para actualizar la inscripción </h2>';
            msjG2.question('¿Desea Continuar?', msj, () => { AjaxBandeja.ActualizaMat(Detalle.HTMLActualizaEstadoMat); });
        }
    });

    $("#FormBandeja .Anulado").click(()=>{
        MatriculaG.Btn = 'Anulado';
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

    /* TODO:  Modal de Programaciones *****************************************************************************************************/
    $("#ListaprogramacionForm #TableListaprogramacion select").change(function(){ AjaxBandeja.ListaProgramacion(Modal.HTMLListaProgramacion); });
    $("#ListaprogramacionForm #TableListaprogramacion input").blur(function(){ AjaxBandeja.ListaProgramacion(Modal.HTMLListaProgramacion); });
    $('#ModalListaProgramacion').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget); // captura al boton
        var bfiltros= button.data('filtros');
        MatriculaG.Programacion_Id = button.parent().find("input:eq(1)").val();
        if( typeof (bfiltros)!='undefined'){
            MatriculaG.LDfiltrosG=bfiltros;
        }
        AjaxBandeja.ListaProgramacion(Modal.HTMLListaProgramacion);//ListaProgramacionModalidad();
    });
    /****************************************************************************************************************************************/

});
let Valida = {
    HTMLBandejaValida: (result) => {
        var html="";
        MatriculaG.Valida = [];
        if(MatriculaG.Btn != 'Actualizar'){
            $("#FormBandeja").hide(500);
        }        
        $('#TableBandejaValida').DataTable().destroy();
    
        $.each(result.data.data, (index,r) => {
            r.curso = [];
            r.frecuencia = [];
            r.horario = [];
            r.turno = [];
            r.inicio = [];
            r.programacion = [];
            r.mmd_id = [];
            r.mmd_especialidad_id = [];
            r.mmd_programacion_id = [];
            $.each( r.detalle.split("^^"), (ind, r2) =>{
                r.curso.push( r2.split("|")[0] );
                r.frecuencia.push( r2.split("|")[1] );
                r.horario.push( r2.split("|")[2] );
                r.turno.push( r2.split("|")[3] );
                r.inicio.push( r2.split("|")[4] );
                r.mmd_id.push( r2.split("|")[5] );
                r.mmd_especialidad_id.push( r2.split("|")[6] );
                r.mmd_programacion_id.push( r2.split("|")[7] );
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
        
        if(MatriculaG.Btn == 'Actualizar'){
            Detalle.Visualizar(MatriculaG.Id, MatriculaG.Tipo);
        }
    },
    SlctCargarBanco: (result) => {
        var html="<option value='0'>.::Seleccione::.</option>";
        $.each(result.data,function(index,r){
            html+="<option value="+r.id+">"+r.banco+"</option>";
        });
        $("#slct_tipo_demo").html(html);
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
            r.mmd_id = [];
            r.mmd_especialidad_id = [];
            r.mmd_programacion_id = [];
            $.each( r.detalle.split("^^"), (ind, r2) =>{
                r.curso.push( r2.split("|")[0] );
                r.frecuencia.push( r2.split("|")[1] );
                r.horario.push( r2.split("|")[2] );
                r.turno.push( r2.split("|")[3] );
                r.inicio.push( r2.split("|")[4] );
                r.mmd_id.push( r2.split("|")[5] );
                r.mmd_especialidad_id.push( r2.split("|")[6] );
                r.mmd_programacion_id.push( r2.split("|")[7] );
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
                        '<td><a class="btn btn-primary" onClick="Detalle.Visualizar('+r.id+',\'Historica\')"><i class="fa fa-edit fa-lg"></i></a>'+btnaux+'</td>'+
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
        let datos = "monto_pago_inscripcion,monto_pago_matricula,monto_promocion";
        let datos2 = "nro_pago_inscripcion,nro_pago_matricula,nro_promocion";
        let datos3 = "fecha_pago_inscripcion,fecha_pago_matricula,fecha_promocion";
        let tipos = "tipo_pago_inscripcion,tipo_pago_matricula,tipo_pago_promocion";
        let Matricula = {};
        let html = '';
        MatriculaG.Id = id;
        MatriculaG.Tipo = tipo;
        $(".CursosG").show(1500);
        $(".CuotasG").hide(1000);

        $("#pago_nombre_inscripcion, #pago_nombre_matricula, #pago_nombre_promocion, #pago_archivo_inscripcion, #pago_archivo_matricula, #pago_archivo_promocion").val('');
        $("#pago_img_ins, #pago_img_mat, #pago_img").attr('src','archivo/default.png');
        
        $("#FormBandeja").show(500);
        $("#FormBandeja .btns").hide(1000);
        $(".ArchivosG").hide();
        if( tipo == 'Valida' ){
            $("#FormBandeja .btns").show(1500);
            $(".ArchivosG").show();
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
            valida = true;
            if( Matricula.tipo_mat == 1 && ( 'monto_promocion,nro_promocion,tipo_pago_promocion,fecha_promocion'.split(key).length > 1 ) ){
                valida = false;
            }
            else if( Matricula.tipo_mat == 2 && ( 'monto_promocion,nro_promocion,tipo_pago_promocion,fecha_promocion'.split(key).length > 1 ) && Matricula['monto_promocion']*1 <= 0 ){
                valida = false;
            }

            if( key == 'monto_pago_inscripcion' ){
                MatriculaG.TotalIns = value*1;
            }
            if( key == 'monto_pago_matricula' ){
                MatriculaG.TotalMat = value*1;
            }
    
            if( tipo == 'Valida' && datos.split(key).length > 1 && valida ){
                $("#FormBandeja ."+key).html( "<input type='text' class='form-control' id='txt_"+key+"' name='txt_"+key+"' value='"+ $.trim(value) + "' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'>" ).removeClass("form-control");
            }
            else if( tipo == 'Valida' && datos2.split(key).length > 1 && valida ){
                $("#FormBandeja ."+key).html( "<input type='text' class='form-control' id='txt_"+key+"' name='txt_"+key+"' value='"+ $.trim(value) + "'>" ).removeClass("form-control");
            }
            else if( tipo == 'Valida' && tipos.split(key).length > 1 && valida ){
                $("#FormBandeja ."+key).html( "<select class='form-control' id='slct_"+key+"' name='slct_"+key+"'>"+$("#slct_tipo_demo").html() + "</select>").removeClass("form-control");
                if( Matricula[key+'_id']*1 > 0 ){
                    $("#FormBandeja #slct_"+key).val( Matricula[key+'_id']*1 );
                }
            }
            else if( tipo == 'Valida' && datos3.split(key).length > 1 && valida ){
                $("#FormBandeja ."+key).html( "<input type='text' class='form-control fecha2' readonly id='txt_"+key+"' name='txt_"+key+"' value='"+ $.trim(value) + "'>" ).removeClass("form-control");
            }
            else{
                $("#FormBandeja ."+key).text( $.trim(value) ).addClass("form-control");
            }
        });
        /*****************************************************************/
        html = '';
        $.each( Matricula.curso, (key, value) =>{
            btn = "&nbsp;"
            if( tipo == 'Valida' ){
                btn = "<a class='btn btn-primary' data-toggle='modal' data-target='#ModalListaProgramacion' data-filtros='estado:1|especialidad_id:"+Matricula.mmd_especialidad_id[key]+"' data-tipotabla='1'><i class='fa fa-list'></i></a>";
                btn += "<input type='hidden' name='mmd_id[]' value='"+Matricula.mmd_id[key]+"'>"+
                        "<input type='hidden' id='mmd_programacion_id_"+Matricula.mmd_programacion_id[key]+"' name='mmd_programacion_id[]' value='"+Matricula.mmd_programacion_id[key]+"'>";
            }
            html+=  "<tr id='tr_programacion_id_"+Matricula.mmd_programacion_id[key]+"'>"+
                        "<td>"+value+"</td>"+
                        "<td>"+Matricula.frecuencia[key]+"</td>"+
                        "<td>"+Matricula.inicio[key]+"</td>"+
                        "<td>"+Matricula.horario[key]+"</td>"+
                        "<td>"+btn+"</td>"+
                    "</tr>";
        });
        $("#FormBandeja .cursos").html(html);
        /*****************************************************************/
        /*****************************************************************/
        let total = 0;
        html = '';
        $("#FormBandeja .curso_pagos").html(html);
        MatriculaG.TotalCur=[];
        MatriculaG.TotalIds=[];

        $.each( $.trim(Matricula.nro_pago).split(","), (key, value) =>{
            total += Matricula.monto_pago.split(",")[key]*1;
            if( value != '' && Matricula.monto_pago.split(",")[key]*1 > 0 ){
                btnaux = MatriculaG.BtnAuxNo;
                if( Matricula.archivo.split(",")[key] != '' ){
                    btnaux = MatriculaG.BtnAuxSi.replace("#", Matricula.archivo.split(",")[key]+"?time="+time);
                }
                if( MatriculaG.Tipo == 'Valida' ){
                    MatriculaG.TotalIds.push(Matricula.matricula_detalle_id.split(",")[key]);
                    MatriculaG.TotalCur[ Matricula.matricula_detalle_id.split(",")[key] ] = Matricula.monto_pago.split(",")[key]*1;
                    html=  "<tr>"+
                                "<input type='hidden' name='matricula_detalle_id[]' value='"+ Matricula.matricula_detalle_id.split(",")[key] +"'>"+
                                "<td>"+Matricula.curso[key]+"</td>"+
                                "<td>"+"<input type='text' class='form-control' name='txt_nro_pago_certificado[]' value='"+ value + "'>"+"</td>"+
                                "<td>"+"<input type='text' class='form-control' id='"+Matricula.matricula_detalle_id.split(",")[key]+"' name='txt_monto_pago_certificado[]' value='"+ Matricula.monto_pago.split(",")[key] + "'>"+"</td>"+
                                "<td>"+"<select class='form-control' name='slct_tipo_pago[]'>"+$("#slct_tipo_demo").html() + "</select>"+"</td>"+
                                "<td>"+"<input type='text' class='form-control fecha2' readonly name='txt_fecha_pago_certificado[]' value='"+ Matricula.fecha_pago.split(",")[key] + "'>"+"</td>"+
                                "<td>"+btnaux+"</td>"+
                                "<td>"+
                                    '<input type="text" readonly class="form-control" id="pago_nombre_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'"  name="pago_nombre_certificado[]" value="" readonly="">'+
                                    '<input type="text" style="display: none;" class="mant" id="pago_archivo_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'" name="pago_archivo_certificado[]">'+
                                    '<label class="btn btn-warning  btn-flat margin">'+
                                        '<i class="fa fa-file-pdf-o fa-lg"></i>'+
                                        '<i class="fa fa-file-word-o fa-lg"></i>'+
                                        '<i class="fa fa-file-image-o fa-lg"></i>'+
                                        '<input type="file" style="display: none;" class="mant" onchange="masterG.onImagen(event,\'#pago_nombre_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'\',\'#pago_archivo_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'\',\'#pago_img_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'\');">'+
                                    '</label>'+
                                    '<label class="btn btn-danger  btn-flat margin" onClick="Detalle.Limpiar(\'_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'\',\'_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'\')">'+
                                        '<i class="fa fa-remove fa-lg"></i>'+
                                    '</label>'+
                                    '<a id="">'+
                                    '<img id="pago_img_certificado'+Matricula.matricula_detalle_id.split(",")[key]+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                                    '</a>'+
                                "</td>"+
                            "</tr>";
                }
                else{
                    html=  "<tr>"+
                                "<td>"+Matricula.curso[key]+"</td>"+
                                "<td>"+value+"</td>"+
                                "<td>"+Matricula.monto_pago.split(",")[key]+"</td>"+
                                "<td>"+Matricula.tipo_pago.split(",")[key]+"</td>"+
                                "<td>"+Matricula.fecha_pago.split(",")[key]+"</td>"+
                                "<td>"+btnaux+"</td>"+
                            "</tr>";
                }
                $("#FormBandeja .curso_pagos").append(html);
                $("#FormBandeja .curso_pagos tr:last-child select").val(Matricula.tipo_pago_id.split(",")[key]*1);
            }
        });
        html=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td class='text-right' colspan='2'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .curso_pagos").append(html);
        /*****************************************************************/
        $("#FormBandeja .fecha2").datetimepicker({
            format: "yyyy-mm-dd",
            language: 'es',
            showMeridian: true,
            time:true,
            minView:2,
            autoclose: true,
            todayBtn: false
        });
        MatriculaG.TotalCuo=[];
        AjaxBandeja.LoadCuotas(Detalle.HTMLLoadCuotas);
        AjaxBandeja.LoadPagos(Detalle.HTMLLoadPagos);
        msjG.mensaje('info','Matricula seleccionada:'+ id,3000);
    },
    HTMLLoadPagos: (result) => {
        let html = '';
        let total = 0;
        $.each(result.data,function(index,r){
            let ids = '';
            let ds = '';
            let ddc = '';
            let importe = 0; //Pendiente
            let comprometido = 0;
            let pagado = 0;
            
            if( $.trim(r.saldo)!='' ){
                ds = r.curso;
                importe = r.saldo*1;
                comprometido = r.precio*1;
                MatriculaG.TotalCur[r.matricula_detalle_id] += $.trim(r.saldo)*1;
            }
            else{
                comprometido = r.monto_cronograma*1;
            }

            if( $.trim(r.salcd)!='' ){
                ds = r.cuotacd+' / FV:'+r.fecha_cronograma;
                if( r.tipo_matricula == 2 ){
                    ds = r.cuotacd;
                }
                importe = r.salcd*1;
                ids = "<input type='hidden' name='salcd_id' value='"+$.trim(r.salcd_id)+"'>"+
                        "<input type='hidden' name='salcd' value='"+$.trim(r.salcd)+"'>";
                MatriculaG.TotalCuo[r.matricula_id+"_"+r.cuota] += $.trim(r.salcd)*1;
            }
            else if( $.trim(r.cuota_cronograma)!='' ){
                ds = r.cuota_cronograma+' / FV:'+r.fecha_cronograma;
                if( r.tipo_matricula == 2 ){
                    ds = r.cuota_cronograma;
                }
                importe = r.monto_cronograma*1;
            }

            pagado = comprometido - importe;

            if( index == 0 ){
                if( $.trim(r.salsi)!='' ){
                    html+=  "<tr id='trid_"+index+"_0'>"+
                                "<input type='hidden' name='salsi_id' value='"+$.trim(r.salsi_id)+"'>"+
                                "<input type='hidden' name='salsi' value='"+$.trim(r.salsi)+"'>"+
                                "<td>Inscripción</td>"+
                                "<td>"+$.trim(r.presi)+"</td>"+
                                "<td>"+(r.presi*1 - r.salsi*1).toFixed(2)+"</td>"+
                                "<td>"+$.trim(r.salsi)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsi)*1;
                    MatriculaG.TotalIns += $.trim(r.salsi)*1; //Suma Total Inscripción
                }

                if( $.trim(r.salsm)!='' ){
                    html+=  "<tr id='trid_"+index+"_0m'>"+
                                "<input type='hidden' name='salsm_id' value='"+$.trim(r.salsm_id)+"'>"+
                                "<input type='hidden' name='salsm' value='"+$.trim(r.salsm)+"'>"+
                                "<td>Matrícula</td>"+
                                "<td>"+$.trim(r.presm)+"</td>"+
                                "<td>"+(r.presm*1 - r.salsm*1).toFixed(2)+"</td>"+
                                "<td>"+$.trim(r.salsm)+"</td>"+
                            "</tr>";
                    total+= $.trim(r.salsm)*1;
                    MatriculaG.TotalMat += $.trim(r.salsm)*1; //Suma Total Matrícula
                }
            }

            if( importe*1>0 ){
                html+=  "<tr id='trid_"+index+"'>"+ ids + 
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
        let total = 0; let validar = false;
        $("#FormBandeja .cuotas").html(html);
        const d = new Date();
        let time = d.getTime();
        $.each(result.data,function(index,r){
            let importe = r.monto_cuota;
            if( importe*1>0 || $.trim(r.nro_cuota) != ''){
                validar = true;
                btnaux = MatriculaG.BtnAuxNo;
                if( $.trim(r.archivo_cuota) != '' ){
                    btnaux = MatriculaG.BtnAuxSi.replace("#", r.archivo_cuota+"?time="+time);
                }
                if( MatriculaG.Tipo == 'Valida' ){
                    MatriculaG.TotalIds.push(r.matricula_id+"_"+r.cuota);
                    MatriculaG.TotalCuo[r.matricula_id+"_"+r.cuota] = $.trim(importe)*1;
                    html=  "<tr>"+
                                "<input type='hidden' name='cuota_id[]' value='"+$.trim(r.id)+"'>"+
                                "<td>"+$.trim(r.cuota)+"</td>"+
                                "<td>"+"<input type='text' class='form-control' name='txt_nro_cuota[]' value='"+ $.trim(r.nro_cuota) + "'>"+"</td>"+
                                "<td>"+"<input type='text' class='form-control' id='"+r.matricula_id+"_"+r.cuota+"' name='txt_monto_cuota[]' value='"+ $.trim(importe) + "'>"+"</td>"+
                                "<td>"+"<select class='form-control' name='slct_tipo_pago_cuota[]'>"+$("#slct_tipo_demo").html() + "</select>"+"</td>"+
                                "<td>"+"<input type='text' class='form-control fecha2' readonly name='txt_fecha_pago_cuota[]' value='"+ $.trim(r.fecha_pago_cuota) + "'>"+"</td>"+
                                "<td>"+btnaux+"</td>"+
                                "<td>"+
                                    '<input type="text" readonly class="form-control" id="pago_nombre_cuota'+r.id+'"  name="pago_nombre_cuota[]" value="" readonly="">'+
                                    '<input type="text" style="display: none;" class="mant" id="pago_archivo_cuota'+r.id+'" name="pago_archivo_cuota[]">'+
                                    '<label class="btn btn-warning  btn-flat margin">'+
                                        '<i class="fa fa-file-pdf-o fa-lg"></i>'+
                                        '<i class="fa fa-file-word-o fa-lg"></i>'+
                                        '<i class="fa fa-file-image-o fa-lg"></i>'+
                                        '<input type="file" style="display: none;" class="mant" onchange="masterG.onImagen(event,\'#pago_nombre_cuota'+r.id+'\',\'#pago_archivo_cuota'+r.id+'\',\'#pago_img_cuota'+r.id+'\');">'+
                                    '</label>'+
                                    '<label class="btn btn-danger  btn-flat margin" onClick="Detalle.Limpiar(\'_cuota'+r.id+'\',\'_cuota'+r.id+'\')">'+
                                        '<i class="fa fa-remove fa-lg"></i>'+
                                    '</label>'+
                                    '<a id="">'+
                                    '<img id="pago_img_cuota'+r.id+'" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">'+
                                    '</a>'+
                                "</td>"+
                            "</tr>";
                }
                else{
                    html=  "<tr>"+
                                "<td>"+$.trim(r.cuota)+"</td>"+
                                "<td>"+$.trim(r.nro_cuota)+"</td>"+
                                "<td>"+$.trim(importe)+"</td>"+
                                "<td>"+$.trim(r.tipo_pago_cuota)+"</td>"+
                                "<td>"+$.trim(r.fecha_pago_cuota)+"</td>"+
                                "<td>"+btnaux+"</td>"+
                            "</tr>";
                }
                total+= $.trim(importe)*1;
                $("#FormBandeja .cuotas").append(html);
                $("#FormBandeja .cuotas tr:last-child select").val(r.tipo_pago_cuota_id*1);
            }
        });
        html=  "<tr style='background-color: #F9CE88;' id='total'>"+
                    "<td>&nbsp;</td>"+
                    "<td class='text-right'>Total:</td>"+
                    "<td>"+total.toFixed(2)+"</td>"+
                "</tr>";
        $("#FormBandeja .cuotas").append(html);
        $("#FormBandeja .fecha2").datetimepicker({
            format: "yyyy-mm-dd",
            language: 'es',
            showMeridian: true,
            time:true,
            minView:2,
            autoclose: true,
            todayBtn: false
        });
        if( validar ){
            $(".CuotasG").show(1500);
            $(".CursosG").hide(1000);
        }
    },
    HTMLActualizaEstadoMat: (result) =>{
        if(result.rst == 1){
            $("#FormBandeja .observacion").val('');
            msjG.mensaje('success','Se proceso correctamente',3000);
            AjaxBandeja.BandejaValida(Valida.HTMLBandejaValida);
        }
    },
    Limpiar: (t1, i1) => {
        $("#pago_nombre"+t1+", #pago_archivo"+t1).val('');
        $("#pago_img"+i1).attr('src','archivo/default.png');
    },
    ExportarFicha: (id) =>{
        window.open('ReportDinamic/Reporte.PDFRE@ExportMatricula?matricula_id='+id, '_blank');
    }
}

let Modal = {
    HTMLListaProgramacion: (result)=>{
        var html="";
        $('#TableListaprogramacion').DataTable().destroy();
        MatriculaG.Programacion = [];
        $.each(result.data.data,function(index,r){
            MatriculaG.Programacion[r.id] = r;
            validasem="style='display:none;'";
            if(r.tipo_curso==1){
                validasem='';
            }
            html+="<tr id='trid_"+r.id+"'>"+
                "<td class='persona'>"+r.persona+"</td>"+
                "<td class='sucursal'>"+r.sucursal+"</td>"+
                "<td class='curso'>"+r.curso+"</td>"+
                "<td>"+r.turno+"</td>"+
                "<td>"+r.dia+"</td>"+
                "<td class='aula' "+validasem+">"+r.aula+"</td>"+
                "<td>"+r.fecha_inicio.split(" ")[0]+"</td>"+
                "<td>"+r.fecha_final.split(" ")[0]+"</td>"+
                "<td>"+r.fecha_inicio.split(" ")[1]+"</td>"+
                "<td>"+r.fecha_final.split(" ")[1]+"</td>"+
                "<td>"+
                '<span class="btn btn-primary btn-sm" onClick="Modal.SeleccionarProgramacion('+r.id+')"+><i class="glyphicon glyphicon-ok"></i></span>';
                
            html+="</td>";
            html+="</tr>";
        });
        $("#TableListaprogramacion tbody").html(html); 
        $("#TableListaprogramacion").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "lengthMenu": [10],
            "language": {
                "info": "Mostrando página "+result.data.current_page+" / "+result.data.last_page+" de "+result.data.total,
                "infoEmpty": "No éxite registro(s) aún",
            },
            "initComplete": function () {
                $('#TableListaprogramacion_paginate ul').remove();
                masterG.CargarPaginacion('Modal.HTMLListaProgramacion','AjaxBandeja.ListaProgramacion',result.data,'#TableListaprogramacion_paginate');
            } 
        });
    },
    SeleccionarProgramacion: (id) =>{
        if( $.trim( $("#mmd_programacion_id_"+id).val() ) != '' ){
            msjG.mensaje('warning','Ya cuenta con el curso "'+MatriculaG.Programacion[id].curso+'" para esta inscripción',7000);
        }
        else{
            $("#tr_programacion_id_"+ MatriculaG.Programacion_Id +" td:eq(0)").html(MatriculaG.Programacion[id].curso);
            $("#tr_programacion_id_"+ MatriculaG.Programacion_Id +" td:eq(1)").html(MatriculaG.Programacion[id].dia);
            $("#tr_programacion_id_"+ MatriculaG.Programacion_Id +" td:eq(2)").html(MatriculaG.Programacion[id].fecha_inicio.split(" ")[1]+ " - "+ MatriculaG.Programacion[id].fecha_final.split(" ")[1]);
            $("#tr_programacion_id_"+ MatriculaG.Programacion_Id +" td:eq(3) a").attr("data-programacion_id", id);
            $("#mmd_programacion_id_"+ MatriculaG.Programacion_Id ).attr("value", id);
            $("#mmd_programacion_id_"+ MatriculaG.Programacion_Id ).attr("id", "mmd_programacion_id_"+id);
            $("#tr_programacion_id_"+ MatriculaG.Programacion_Id ).attr("id", "tr_programacion_id_"+id);
            $("#ModalListaProgramacion .modal-footer button").click();
        }
    },
}
</script>
