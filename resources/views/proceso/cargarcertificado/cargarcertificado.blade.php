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

@include( 'proceso.cargarcertificado.js.cargarcertificado_ajax' )
@include( 'proceso.cargarcertificado.js.cargarcertificado' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Cargar Certificado
            <small>Proceso</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Proceso</li>
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
                                    <select id="slct_especialidad" name="slct_especialidad[]" class='selectpicker form-control' multiple data-actions-box='true'>
                                        <option>.::Seleccione Carrera / Módulo::.</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Inicio / Curso:</label>
                                    <select id="slct_curso" name="slct_curso[]" class='selectpicker form-control' multiple data-actions-box='true'>
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
                                <div class="col-md-1" style="padding:24px">&nbsp;
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table id="TableReporte" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #DDEBF7;" colspan='6'>DATOS DEL ALUMNO</th>
                                            <th style="background-color: #E2EFDA;" colspan='10'>DATOS DEL CURSO DE FORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>CERTIFICADOS</th>
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
                                            
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>
                                            <th style="background-color: #FCE4D6;">Certificado</th>
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
                                            
                                            <th style="background-color: #FCE4D6;">Deuda Total</th>
                                            <th style="background-color: #FCE4D6;">Certificado</th>
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

