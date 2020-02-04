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

@include( 'reporte.asesoria.js.asesoria_ajax' )
@include( 'reporte.asesoria.js.asesoria' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Asesoría al estudiante
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> </li>
            <li class="active"> </li>
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
                                    <select id="slct_especialidad_id" name="slct_especialidad_id" class='selectpicker form-control'>
                                        <option>.::Seleccione Especialidad::.</option>
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
                            <div class="col-md-5">
                                <table id="TableCurso" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #FFF2CC;">Cod</th>
                                            <th style="background-color: #FFF2CC;">Unidad Académica</th>
                                            <th style="background-color: #FFF2CC;">Crédito</th>
                                            <th style="background-color: #FFF2CC;">Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="TableReporte" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #DDEBF7;" colspan='6'>DATOS DEL ALUMNO</th>
                                            <th id="notacurso" style="background-color: #FFF2CC;" colspan='5'>CURSOS</th>
                                        </tr>
                                        <tr id="cabecera">
                                            <th style="background-color: #DDEBF7;">DNI</th>
                                            <th style="background-color: #DDEBF7;">Nombres</th>
                                            <th style="background-color: #DDEBF7;">Paterno</th>
                                            <th style="background-color: #DDEBF7;">Materno</th>
                                            <th style="background-color: #DDEBF7;">Celular</th>
                                            <th style="background-color: #DDEBF7;">Email</th>

                                            <th style="background-color: #FFF2CC;">Total Cursos</th>
                                            <th style="background-color: #FFF2CC;">Total Relación</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C1</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C2</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C3</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C4</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr id="cabecera2">
                                            <th style="background-color: #DDEBF7;">DNI</th>
                                            <th style="background-color: #DDEBF7;">Nombres</th>
                                            <th style="background-color: #DDEBF7;">Paterno</th>
                                            <th style="background-color: #DDEBF7;">Materno</th>
                                            <th style="background-color: #DDEBF7;">Celular</th>
                                            <th style="background-color: #DDEBF7;">Email</th>

                                            <th style="background-color: #FFF2CC;">Total Cursos</th>
                                            <th style="background-color: #FFF2CC;">Total Relación</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C1</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C2</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C3</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C4</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">C5</th>
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
@section('form')
     @include( 'proceso.atencioncliente.form.atencioncliente' )
@stop
