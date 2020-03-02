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

@include( 'reporte.indicemat.js.indicemat_ajax' )
@include( 'reporte.indicemat.js.indicemat' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Indice Matrticulación
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">Indice Matrticulación</li>
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
                                <table id="TableIndiceMat" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th>N°</th>
                                            <th>Empresa</th>
                                            <th>Tipo Formación</th>
                                            <th>Formación</th>
                                            <th>FREC</th>
                                            <th>Fecha Inicio</th>
                                            <th>Inscritos Últimos 2 Días</th>
                                            <th>Inscritos Último Día</th>
                                            <th>Total Inscritos</th>
                                            <th>Meta MAX</th>
                                            <th>Meta MIN</th>
                                            <th>Inicio Campaña</th>
                                            <th>Dias Campaña</th>
                                            <th>Indice x Día</th>
                                            <th>Días que Falta</th>
                                            <th>Proy. Días Faltantes</th>
                                            <th>Proy. Final</th>
                                            <th>Falta Lograr Meta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>N°</th>
                                            <th>Empresa</th>
                                            <th>Tipo Formación</th>
                                            <th>Formación</th>
                                            <th>FREC</th>
                                            <th>Fecha Inicio</th>
                                            <th>Inscritos Últimos 2 Días</th>
                                            <th>Inscritos Último Día</th>
                                            <th>Total Inscritos</th>
                                            <th>Meta MAX</th>
                                            <th>Meta MIN</th>
                                            <th>Inicio Campaña</th>
                                            <th>Dias Campaña</th>
                                            <th>Indice x Día</th>
                                            <th>Días que Falta</th>
                                            <th>Proy. Días Faltantes</th>
                                            <th>Proy. Final</th>
                                            <th>Falta Lograr Meta</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

