@extends('layout.master')  

@section('include')
@parent
{{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
{{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
{{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

{{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
{{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
{{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

{{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

@include( 'reporte.controlpago.js.controlpago_ajax' )
@include( 'reporte.controlpago.js.controlpago' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Reporte de Control de Pagos
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">FC</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="PaeForm">
                        <div class="box-body table-responsive no-padding">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label class="control-label">Carrera / Módulo:</label>
                                    <select id="slct_especialidad" name="slct_especialidad[]" class='selectpicker form-control' multiple data-actions-box='true' data-live-search="true">
                                        <option>.::Seleccione Carrera / Módulo::.</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Inicio / Curso:</label>
                                    <select id="slct_curso" name="slct_curso[]" class='selectpicker form-control' multiple data-actions-box='true' data-live-search="true">
                                        <option>.::Seleccione Inicio / Curso::.</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">DNI:</label>
                                    <input type="text" class="form-control" placeholder="DNI" id="txt_dni" name="txt_dni">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label class="control-label">Paterno:</label>
                                    <input type="text" class="form-control" placeholder="Paterno" id="txt_paterno" name="txt_paterno">
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Materno:</label>
                                    <input type="text" class="form-control" placeholder="Materno" id="txt_materno" name="txt_materno">
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Nombre:</label>
                                    <input type="text" class="form-control" placeholder="Nombre" id="txt_nombre" name="txt_nombre">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label class="control-label">Tipo de Vendedor:</label>
                                    <select id="slct_medio_captacion_id" name="slct_medio_captacion_id[]" class='selectpicker form-control' multiple data-actions-box='true' data-live-search="true">
                                        <option>.::Seleccione Inicio / Curso::.</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Como llegó aqui:</label>
                                    <select id="slct_medio_captacion_id2" name="slct_medio_captacion_id2[]" class='selectpicker form-control' multiple data-actions-box='true' data-live-search="true">
                                        <option>.::Seleccione Inicio / Curso::.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label class="control-label">Fecha Inscripción - Inicio:</label>
                                    <div class="input-group">
                                      <span class="input-group-addon spn_limpiar" style="cursor: pointer;"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></span>
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Fecha Inscripción - Final:</label>
                                    <div class="input-group">
                                      <span class="input-group-addon spn_limpiar" style="cursor: pointer;"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></span>
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                                <div class="col-md-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar</i></a>
                                </div>
                                <div class="col-md-1" style="padding:24px">&nbsp;
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table id="TableReporte" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #DDEBF7;" colspan='6'>DATOS DEL ALUMNO</th>
                                            <th style="background-color: #88BAE3;" colspan='14'>DATOS ADICIONALES DEL ALUMNO</th>
                                            <th style="background-color: #E2EFDA;" colspan='12'>DATOS DEL CURSO DE FORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FCE4D6;" colspan='4'>PAGO POR CURSO INDEPENDIENTE</th>
                                            <th style="background-color: #E2EFDA;" colspan='4'>PAGO POR CONJUNTO DE CURSOS</th>
                                            <th style="background-color: #FFF2CC;" colspan='4'>PAGO POR INSCRIPCIÓN</th>
                                            <th style="background-color: #FFF2CC;" colspan='4'>PAGO POR MATRÍCULA</th>
                                            <th style="background-color: #DDEBF7;" colspan='1'>PAGO DE SALDOS</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>DEUDA Y NOTA FINAL DEL CURSO</th>
                                            <th style="background-color: #DDEBF7;" colspan='4'>PROGRAMACION Y PAGO - CUOTAS</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>PAGO Y DEUDA DE SALDOS - CUOTAS</th>
                                            <th style="background-color: #FCE4D6;" colspan='1'>A LA FECHA</th>
                                            <th style="background-color: #FCD5B4;" colspan='12'>DATOS DE LA VENTA</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #DDEBF7;">DNI</th>
                                            <th style="background-color: #DDEBF7;">Nombres</th>
                                            <th style="background-color: #DDEBF7;">Paterno</th>
                                            <th style="background-color: #DDEBF7;">Materno</th>
                                            <th style="background-color: #DDEBF7;">Celular</th>
                                            <th style="background-color: #DDEBF7;">Email</th>

                                            <th style="background-color: #88BAE3;">Estado Civil</th>
                                            <th style="background-color: #88BAE3;">Sexo</th>
                                            <th style="background-color: #88BAE3;">Fecha Nacimiento</th>
                                            <th style="background-color: #88BAE3;">País Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Colegio</th>
                                            <th style="background-color: #88BAE3;">Distrito Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Provincia Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Región Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Distrito Dirección</th>
                                            <th style="background-color: #88BAE3;">Provincia Dirección</th>
                                            <th style="background-color: #88BAE3;">Región Dirección</th>
                                            <th style="background-color: #88BAE3;">Tenencia</th>
                                            <th style="background-color: #88BAE3;">Dirección</th>
                                            <th style="background-color: #88BAE3;">Referencia</th>

                                            <th style="background-color: #E2EFDA;">Empresa</th>
                                            <th style="background-color: #E2EFDA;">Fecha de Inscripción</th>
                                            <th style="background-color: #E2EFDA;">Tipo de Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Carrera / Módulo</th>
                                            <th style="background-color: #E2EFDA;">Inicio / Curso</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Horario</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            <th style="background-color: #E2EFDA; min-width: 220px !important;">Observación</th>
                                            <th style="background-color: #E2EFDA; min-width: 220px !important;">Promociones</th>
                                            
                                            <th style="background-color: #FCE4D6;">Nro Pago</th>
                                            <th style="background-color: #FCE4D6;">Monto Pago</th>
                                            <th style="background-color: #FCE4D6;">Tipo Pago</th>
                                            <th style="background-color: #FCE4D6;">Fecha Pago</th>

                                            <th style="background-color: #E2EFDA;">Nro Recibo PCC</th>
                                            <th style="background-color: #E2EFDA;">Monto PCC</th>
                                            <th style="background-color: #E2EFDA;">Tipo Pago</th>
                                            <th style="background-color: #E2EFDA;">Fecha Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Monto Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>
                                            <th style="background-color: #FFF2CC;">Fecha Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Monto Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>
                                            <th style="background-color: #FFF2CC;">Fecha Pago</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>

                                            <th style="background-color: #FCE4D6;">Deuda a la Fecha</th>
                                            <th style="background-color: #FCE4D6;">Promedio Final del Curso</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Cuota / Fecha Programada / Monto Programado</th>
                                            <th style="background-color: #DDEBF7; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>
                                            <th style="background-color: #DDEBF7;">Total Pago x Cuotas</th>
                                            <th style="background-color: #DDEBF7;">Total Monto Pagado</th>

                                            <th style="background-color: #FCE4D6; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>
                                            <th style="background-color: #FCE4D6; min-width: 140px !important;">Cuota / Monto Deuda</th>
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>

                                            <th style="background-color: #FCD5B4;">Sede de Inscripción</th>
                                            <th style="background-color: #FCD5B4;">Recogo del Certificado</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Cajero(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Vendedor(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Centro de Operación</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Tipo de Vendedor</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Como llegó aquí</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Registrador(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Estado de la Matrícula</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Fecha del Estado de la Matrícula</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Nro Expediente</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Fecha de Expediente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="background-color: #DDEBF7;">DNI</th>
                                            <th style="background-color: #DDEBF7;">Nombres</th>
                                            <th style="background-color: #DDEBF7;">Paterno</th>
                                            <th style="background-color: #DDEBF7;">Materno</th>
                                            <th style="background-color: #DDEBF7;">Celular</th>
                                            <th style="background-color: #DDEBF7;">Email</th>

                                            <th style="background-color: #88BAE3;">Estado Civil</th>
                                            <th style="background-color: #88BAE3;">Sexo</th>
                                            <th style="background-color: #88BAE3;">Fecha Nacimiento</th>
                                            <th style="background-color: #88BAE3;">País Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Colegio</th>
                                            <th style="background-color: #88BAE3;">Distrito Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Provincia Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Región Nacimiento</th>
                                            <th style="background-color: #88BAE3;">Distrito Dirección</th>
                                            <th style="background-color: #88BAE3;">Provincia Dirección</th>
                                            <th style="background-color: #88BAE3;">Región Dirección</th>
                                            <th style="background-color: #88BAE3;">Tenencia</th>
                                            <th style="background-color: #88BAE3;">Dirección</th>
                                            <th style="background-color: #88BAE3;">Referencia</th>

                                            <th style="background-color: #E2EFDA;">Empresa</th>
                                            <th style="background-color: #E2EFDA;">Fecha de Inscripción</th>
                                            <th style="background-color: #E2EFDA;">Tipo de Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Carrera / Módulo</th>
                                            <th style="background-color: #E2EFDA;">Inicio / Curso</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Horario</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            <th style="background-color: #E2EFDA;">Observación</th>
                                            <th style="background-color: #E2EFDA;">Promociones</th>
                                            
                                            <th style="background-color: #FCE4D6;">Nro Pago</th>
                                            <th style="background-color: #FCE4D6;">Monto Pago</th>
                                            <th style="background-color: #FCE4D6;">Tipo Pago</th>
                                            <th style="background-color: #FCE4D6;">Fecha Pago</th>

                                            <th style="background-color: #E2EFDA;">Nro Recibo PCC</th>
                                            <th style="background-color: #E2EFDA;">Monto PCC</th>
                                            <th style="background-color: #E2EFDA;">Tipo Pago</th>
                                            <th style="background-color: #E2EFDA;">Fecha Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Monto Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>
                                            <th style="background-color: #FFF2CC;">Fecha Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Monto Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>
                                            <th style="background-color: #FFF2CC;">Fecha Pago</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>

                                            <th style="background-color: #FCE4D6;">Deuda a la Fecha</th>
                                            <th style="background-color: #FCE4D6;">Promedio Final del Curso</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Cuota / Fecha Programada / Monto Programado</th>
                                            <th style="background-color: #DDEBF7; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>
                                            <th style="background-color: #DDEBF7;">Total Pago x Cuotas</th>
                                            <th style="background-color: #DDEBF7;">Total Monto Pagado</th>

                                            <th style="background-color: #FCE4D6; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago / Fecha Pago</th>
                                            <th style="background-color: #FCE4D6; min-width: 140px !important;">Cuota / Monto Deuda</th>
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>

                                            <th style="background-color: #FCD5B4;">Sede de Inscripción</th>
                                            <th style="background-color: #FCD5B4;">Recogo del Certificado</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Cajero(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Vendedor(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Centro de Operación</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Tipo de Vendedor</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Como llegó aquí</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Registrador(a)</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Estado de la Matrícula</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Fecha del Estado de la Matrícula</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Nro Expediente</th>
                                            <th style="background-color: #FCD5B4; min-width: 200px !important;">Fecha de Expediente</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

