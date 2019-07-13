@extends('layout.master')  

@section('include')
@parent
{{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
{{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
{{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

{{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

@include( 'reporte.llamada.js.llamada' )
@include( 'reporte.llamada.js.llamada_ajax' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Estadistico de llamadas
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">Estadistico de llamadas</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="IndiceMatForm">
                        <div class="box-body no-padding">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial - Llamada</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final - Llamada</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Registro de Llamadas</label>
                                    <select class="form-control selectpicker show-menu-arrow" id="slct_ultimo_registro" name="slct_ultimo_registro">
                                        <option value="0" selected>.::Todas las Llamadas::.</option>
                                        <option value="1">.::Último Registro::.</option>
                                    </select>
                                </div>
                            </div>
                            <!--div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final - Distribuida</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin_dis" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin_dis" name="txt_fecha_fin_dis" readonly/>
                                    </div>
                                </div>
                            </div-->
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Vendedor</label>
                                    <select class="form-control selectpicker show-menu-arrow" data-actions-box='true' data-live-search="true" id="slct_vendedor" name="slct_vendedor[]" multiple>
                                        <option>.::Todo::.</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fuente</label>
                                    <select class="form-control selectpicker show-menu-arrow" data-actions-box='true' id="slct_fuente" name="slct_fuente[]" multiple>
                                        <option>.::Todo::.</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Tipo</label>
                                    <select class="form-control selectpicker show-menu-arrow" data-actions-box='true' id="slct_tipo" name="slct_tipo[]" multiple>
                                        <option>.::Todo::.</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Empresa</label>
                                    <select class="form-control selectpicker show-menu-arrow" data-actions-box='true' id="slct_empresa" name="slct_empresa[]" multiple>
                                        <option>.::Todo::.</option>
                                    </select>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body no-padding">
                        <div class="col-md-8 col-sm-12">
                            <table class="table table-striped">
                                <head>
                                    <th colspan="2" style="text-align: center;">Listado de Reportes</th>
                                </head>
                                <tbody>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportLlamadas'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte General de Llamadas</td>
                                    </tr>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportNoInteresados'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte de Llamadas no Interesado Resumen</td>
                                    </tr>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportNoInteresadosDetalle'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte de Llamadas no Interesado Detalle</td>
                                    </tr>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportInteresados'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte de Llamadas Interesados</td>
                                    </tr>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportLlamadasDetalle'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte de Llamadas Detalle</td>
                                    </tr>
                                    <tr>
                                        <td><a class='btn btn-success btn-lg btnexport' href='' target="_blank" data-reporte='ExportClientePotencial'><i class="glyphicon glyphicon-download-alt"></i></i></a></td>
                                        <td>Reporte de Llamadas - Clientes Potenciales</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- .box-body -->
                </div><!-- .box -->
            </div><!-- .col -->
        </div><!-- .row -->
    </section><!-- .content -->
        @stop

