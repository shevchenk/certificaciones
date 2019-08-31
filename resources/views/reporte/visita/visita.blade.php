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

@include( 'reporte.visita.js.visita_ajax' )
@include( 'reporte.visita.js.visita' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Visitas a Plataforma
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">Visita a Plataforma</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="IndiceMatForm">
                        <div class="box-body no-padding">
                            <div class="col-sm-12">
                                <!--div class="col-sm-2 text-center">
                                    <label class="control-label">Tipo</label>
                                    <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_curso" name="slct_tipo_curso">
                                        <option value="1" selected>Curso</option>
                                        <option value="2">Seminario</option>
                                    </select>
                                </div-->
                                <!--div class="col-sm-2 text-center">
                                    <label class="control-label">Tipo Fecha</label>
                                    <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_fecha" name="slct_tipo_fecha">
                                        <option value="1" selected>Programación</option>
                                        <option value="2">Matrícula</option>
                                    </select>
                                </div-->
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial de Inscripción</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final de Inscripción</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar</i></a>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                            <div class="box-body table-responsive no-padding">
                                <table id="TableVisita" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th>N°</th>
                                            <th>Fecha de Registro</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Nombre</th>
                                            <th>Celular</th>
                                            <th>Email</th>

                                            <th>Distrito</th>
                                            <th>Provincia</th>
                                            <th>Región</th>
                                            <th>Referencia</th>

                                            <th>Sede de Registro</th>
                                            <th>Medio Publicitario</th>
                                            <th>Interesado en</th>
                                            <th>Frecuencia</th>
                                            <th>Horario</th>

                                            <th>Estado</th>
                                            <th>Sub Estado</th>
                                            <th>Detalle Sub Estado</th>
                                            <th>Fecha Programada</th>
                                            <th>Persona Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

