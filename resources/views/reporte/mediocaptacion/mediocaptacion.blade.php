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

@include( 'reporte.mediocaptacion.js.mediocaptacion_ajax' )
@include( 'reporte.mediocaptacion.js.mediocaptacion' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Reporte Inscritos por medios de captación
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
                    <form id="ReporteForm">
                        <div class="box-body table-responsive no-padding">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label class="control-label">Ode de Estudios:</label>
                                    <select id="slct_sucursal" name="slct_sucursal[]" class="form-control selectpicker show-menu-arrow" multiple data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple>
                                        <option>.::Seleccione Ode de Estudio::.</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Empresas:</label>
                                    <select id="slct_empresa" name="slct_empresa[]" class="form-control selectpicker show-menu-arrow" multiple data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple>
                                        <option>.::Seleccione Empresa::.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-sm-2">
                                    <label class="control-label">Rango Inicial de Fecha Inicio:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_inicial" name="txt_fecha_inicial" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Rango Final de Fecha Inicio:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_final" name="txt_fecha_final" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-sm-2">
                                    <label class="control-label">Rango Inicial de Fecha Inscripción:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ins_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_inscripcion_inicial" name="txt_fecha_inscripcion_inicial" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Rango Final de Fecha Inscripción:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ins_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_inscripcion_final" name="txt_fecha_inscripcion_final" readonly/>
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
                                            <th id="titmedio" class="text-center" style="background-color: #E2EFDA;" colspan='7'>MEDIOS DE CAPTACIÓN</th>
                                        </tr>
                                        <tr id="cabecera">
                                            <th style="background-color: #E2EFDA;">Ode</th>
                                            <th style="background-color: #E2EFDA;">N° Orden</th>
                                            <th style="background-color: #E2EFDA;">Medio de Captación</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr id="cabecera2">
                                            <th style="background-color: #E2EFDA;">Ode</th>
                                            <th style="background-color: #E2EFDA;">N° Orden</th>
                                            <th style="background-color: #E2EFDA;">Medio de Captación</th>
                                            <th class="cabecera" style="background-color: #FFF2CC;">Total</th>
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

