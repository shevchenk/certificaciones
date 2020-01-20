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

@include( 'reporte.evaluacion.js.evaluacion_ajax' )
@include( 'reporte.evaluacion.js.evaluacion' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Reporte Detalles de Evaluaciones
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
                                    <label class="control-label">Especialidad:</label>
                                    <select id="slct_especialidad" name="slct_especialidad[]" class='selectpicker form-control' multiple data-actions-box='true'>
                                        <option>.::Seleccione Especialidad::.</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Curso:</label>
                                    <select id="slct_curso" name="slct_curso[]" class='selectpicker form-control' multiple data-actions-box='true'>
                                        <option>.::Seleccione Curso::.</option>
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
                                            <th style="background-color: #E2EFDA;" colspan='9'>DATOS DEL CURSO DE FORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FFF2CC;" colspan='5'>NOTA DEL CURSO</th>
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
                                            <th style="background-color: #E2EFDA;">Nombre del Módulo</th>
                                            <th style="background-color: #E2EFDA;">Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            
                                            <th style="background-color: #FFF2CC;">Tipo Evaluación</th>
                                            <th style="background-color: #FFF2CC;">Peso Evaluación</th>
                                            <th style="background-color: #FFF2CC;">Fecha de Ejecución</th>
                                            <th style="background-color: #FFF2CC;">Nota</th>
                                            <th style="background-color: #FFF2CC;">Promedio Final</th>
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
                                            <th style="background-color: #E2EFDA;">Nombre del Módulo</th>
                                            <th style="background-color: #E2EFDA;">Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            
                                            <th style="background-color: #FFF2CC;">Tipo Evaluación</th>
                                            <th style="background-color: #FFF2CC;">Peso Evaluación</th>
                                            <th style="background-color: #FFF2CC;">Fecha de Ejecución</th>
                                            <th style="background-color: #FFF2CC;">Nota</th>
                                            <th style="background-color: #FFF2CC;">Promedio Final</th>
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

