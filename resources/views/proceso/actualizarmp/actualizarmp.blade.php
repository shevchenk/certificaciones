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

@include( 'proceso.actualizarmp.js.actualizarmp_ajax' )
@include( 'proceso.actualizarmp.js.actualizarmp' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Actualizar Programación - Masivo
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
                                    <select id="slct_curso" name="slct_curso[]" class='selectpicker form-control' multiple data-size="15" data-actions-box='true' data-live-search="true">
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
                                            <th style="background-color: #E2EFDA;" colspan='8'>DATOS DEL CURSO DE FORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FCE4D6;" colspan='2'>Actualizar</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #E2EFDA;">Tipo de Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Carrera / Módulo</th>
                                            <th style="background-color: #E2EFDA;">Inicio / Curso</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Horario</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            
                                            <th style="background-color: #FCE4D6;">Cant</th>
                                            <th style="background-color: #FCE4D6;">[]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="background-color: #E2EFDA;">Tipo de Formación Continua</th>
                                            <th style="background-color: #E2EFDA;">Carrera / Módulo</th>
                                            <th style="background-color: #E2EFDA;">Inicio / Curso</th>
                                            <th style="background-color: #E2EFDA;">Local</th>
                                            <th style="background-color: #E2EFDA;">Frecuencia</th>
                                            <th style="background-color: #E2EFDA;">Horario</th>
                                            <th style="background-color: #E2EFDA;">Turno</th>
                                            <th style="background-color: #E2EFDA;">Inicio</th>
                                            
                                            <th style="background-color: #FCE4D6;">Cant</th>
                                            <th style="background-color: #FCE4D6;">[]</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                </div><!-- .box -->
            </div><!-- .col -->
            <div class="col-md-12">
                <div class="box">
                    <form id="MPForm" class="hidden">
                        <div class="box-body">
                            <div class="col-md-12 table-responsive">
                                <table id="MPTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #9FD08D;" colspan='8'>DATOS DEL CURSO DE FORMACIÓN CONTINUA SELECCIONADO</th>
                                            <th style="background-color: #F7B38B;" colspan='8'>Nro de Afectados</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #9FD08D;">Tipo de Formación Continua</th>
                                            <th style="background-color: #9FD08D;">Carrera / Módulo</th>
                                            <th style="background-color: #9FD08D;">Inicio / Curso</th>
                                            <th style="background-color: #9FD08D;">Local</th>
                                            <th style="background-color: #9FD08D;">Frecuencia</th>
                                            <th style="background-color: #9FD08D;">Horario</th>
                                            <th style="background-color: #9FD08D;">Turno</th>
                                            <th style="background-color: #9FD08D;">Inicio</th>

                                            <th style="background-color: #F7B38B;">Cant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Se dividirá en:</label>
                                <input type="text" class="form-control mant" id="txt_cant" placeholder="Ingrese Cantidad">
                                <input type="hidden" class="form-control mant" id="txt_especialidad_id" name="txt_especialidad_id">
                                <input type="hidden" class="form-control mant" id="txt_programacion_id" name="txt_programacion_id">
                                <input type="hidden" class="form-control mant" id="txt_paterno" name="txt_paterno">
                                <input type="hidden" class="form-control mant" id="txt_materno" name="txt_materno">
                                <input type="hidden" class="form-control mant" id="txt_nombre" name="txt_nombre">
                                <input type="hidden" class="form-control mant" id="txt_dni" name="txt_dni">
                            </div>
                            <div class="col-md-1">
                                <br>
                                <span class="btn btn-warning btn-md" id="btn_DividirMP">
                                    <i class="glyphicon glyphicon-search"></i> Generar
                                </span>
                            </div>
                            
                            <div class="col-md-12 plantilla">
                                <fieldset style="border-inline: dotted;">
                                    <legend>Bloque #1:</legend>
                                    <div class="col-md-2">
                                        <label class="control-label">Cantidad a actualizar</label>
                                        <input type="text" class="form-control" id="txt_cant_0" name="txt_cant[]" placeholder="Ingrese Cantidad">
                                    </div>
                                    <div class="col-md-8 validar">
                                        <label class="control-label">Carrera / Módulo:</label>
                                        <select id="slct_especialidad_0" name="slct_especialidad[]" onChange="MP.ListarProgramacion(this);" class='selectpicker form-control' data-actions-box='true'>
                                            <option>.::Seleccione Carrera / Módulo::.</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <label class="control-label">Inicio / Curso:</label>
                                        <select id="slct_programacion_0" name="slct_programacion[]" class='selectpicker form-control' data-size="15" data-actions-box='true' data-live-search="true">
                                            <option value = ''>.::Seleccione Inicio / Curso::.</option>
                                        </select>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12 agregados">
                            </div>

                            <div class="col-md-12">
                                <br>
                                <div class="col-md-3">
                                    <span class="btn btn-info btn-lg" id="btn_ActualizarMP">
                                        <i class="glyphicon glyphicon-edit"></i> Actualizar
                                    </span>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                </div><!-- .box -->
            </div><!-- .col -->
        </div><!-- .row -->
    </section><!-- .content -->
        @stop

