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
                                            <th style="background-color: #E2EFDA;" colspan='10'>DATOS DEL CURSO DE FORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FCE4D6;" colspan='3'>PAGO POR CURSO INDEPENDIENTE</th>
                                            <th style="background-color: #E2EFDA;" colspan='3'>PAGO POR CONJUNTO DE CURSOS</th>
                                            <th style="background-color: #FFF2CC;" colspan='3'>PAGO POR INSCRIPCIÓN</th>
                                            <th style="background-color: #FFF2CC;" colspan='3'>PAGO POR MATRÍCULA</th>
                                            <th style="background-color: #DDEBF7;" colspan='1'>PAGO DE SALDOS</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>DEUDA Y NOTA FINAL DEL CURSO</th>
                                            <th style="background-color: #DDEBF7;" colspan='2'>PROGRAMACION Y PAGO - CUOTAS</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>PAGO Y DEUDA DE SALDOS - CUOTAS</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>A LA FECHA</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #DDEBF7;">DNI</th>
                                            <th style="background-color: #DDEBF7;">Nombres</th>
                                            <th style="background-color: #DDEBF7;">Paterno</th>
                                            <th style="background-color: #DDEBF7;">Materno</th>
                                            <th style="background-color: #DDEBF7;">Celular</th>
                                            <th style="background-color: #DDEBF7;">Email</th>

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
                                            
                                            <th style="background-color: #FCE4D6;">Nro Pago</th>
                                            <th style="background-color: #FCE4D6;">Monto Pago</th>
                                            <th style="background-color: #FCE4D6;">Tipo Pago</th>

                                            <th style="background-color: #E2EFDA;">Nro Recibo PCC</th>
                                            <th style="background-color: #E2EFDA;">Monto PCC</th>
                                            <th style="background-color: #E2EFDA;">Tipo Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Monto Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Monto Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Monto Pago / Nro Pago / Tipo Pago</th>

                                            <th style="background-color: #FCE4D6;">Deuda a la Fecha</th>
                                            <th style="background-color: #FCE4D6;">Promedio Final del Curso</th>

                                            <th style="background-color: #DDEBF7; min-width: 210px !important;">Cuota / Fecha Programada / Monto Programado</th>
                                            <th style="background-color: #DDEBF7; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago</th>

                                            <th style="background-color: #FCE4D6; min-width: 260px !important;">Cuota / Monto Pago / Nro Pago / Tipo Pago</th>
                                            <th style="background-color: #FCE4D6; min-width: 140px !important;">Cuota / Monto Deuda</th>
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>
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
                                            
                                            <th style="background-color: #FCE4D6;">Nro Pago</th>
                                            <th style="background-color: #FCE4D6;">Monto Pago</th>
                                            <th style="background-color: #FCE4D6;">Tipo Pago</th>

                                            <th style="background-color: #E2EFDA;">Nro Recibo PCC</th>
                                            <th style="background-color: #E2EFDA;">Monto PCC</th>
                                            <th style="background-color: #E2EFDA;">Tipo Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Monto Inscripción</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>

                                            <th style="background-color: #FFF2CC;">Nro Recibo Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Monto Matrícula</th>
                                            <th style="background-color: #FFF2CC;">Tipo Pago</th>

                                            <th style="background-color: #DDEBF7;">Monto Pago / Nro Pago / Tipo Pago</th>

                                            <th style="background-color: #FCE4D6;">Deuda a la Fecha</th>
                                            <th style="background-color: #FCE4D6;">Promedio Final del Curso</th>

                                            <th style="background-color: #DDEBF7;">Cuota / Fecha Programada / Monto Programado</th>
                                            <th style="background-color: #DDEBF7;">Cuota / Monto Pago / Nro Pago / Tipo Pago</th>

                                            <th style="background-color: #FCE4D6;">Cuota / Monto Pago / Nro Pago / Tipo Pago</th>
                                            <th style="background-color: #FCE4D6;">Cuota / Monto Deuda</th>
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>
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

