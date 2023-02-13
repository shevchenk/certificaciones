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
        <h1>Reporte Índice de Inscripción
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
                                            <th class="text-center" style="background-color: #DDEBF7;" colspan='7'>DATOS DE LA FORMACIÓN CONTINUA</th>
                                            <th class="text-center" style="background-color: #E2EFDA;" colspan='7'>INSCRIPCIÓN DE LA SEMANA ANTERIOR</th>
                                            <th class="text-center" style="background-color: #E2EFDA;" colspan='7'>INSCRIPCIÓN ÚLTIMOS 7 DÍAS</th>
                                            <th class="text-center" style="background-color: #E2EFDA;" colspan='3'>INSCRIPCIONES</th>
                                            <th class="text-center" style="background-color: #FFF2CC;" colspan='11'>CALCULO DE ÍNDICE DE INSCRIPCIÓN</th>
                                        </tr>
                                        <tr id="cabecera">
                                            <th style="background-color: #DDEBF7;">Ode</th>
                                            <th style="background-color: #DDEBF7;">Empresa</th>
                                            <th style="background-color: #DDEBF7;">Carrera / Módulo</th>
                                            <th style="background-color: #DDEBF7;">Inicio / Curso</th>
                                            <th style="background-color: #DDEBF7;">Frecuencia</th>
                                            <th style="background-color: #DDEBF7;">Horario</th>
                                            <th style="background-color: #DDEBF7;">Fecha de Inicio</th>

                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-13 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-12 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-11 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-10 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-9 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-8 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-7 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-6 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-5 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-4 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-3 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-2 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-1 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d');?></th>
                                            <th style="background-color: #E2EFDA;">Semana Anterior</th>
                                            <th style="background-color: #E2EFDA;">Últimos 7 días</th>
                                            <th style="background-color: #E2EFDA;">Total Inscrito</th>
                                            
                                            <th style="background-color: #FFF2CC;">Meta Máxima</th>
                                            <th style="background-color: #FFF2CC;">Meta Mínima</th>
                                            <th style="background-color: #FFF2CC;">Inicio Campaña</th>
                                            <th style="background-color: #FFF2CC;">Días Campaña</th>
                                            <th style="background-color: #FFF2CC;">Días que falta</th>
                                            <th style="background-color: #FFF2CC;">Índice Semana Anterior</th>
                                            <th style="background-color: #FFF2CC;">Índice Últimos 7 días</th>
                                            <th style="background-color: #FFF2CC;">Proy. días Faltantes</th>
                                            <th style="background-color: #FFF2CC;">Proy. Final</th>
                                            <th style="background-color: #FFF2CC;">Falta para lograr meta</th>
                                            <th style="background-color: #FFF2CC;">Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr id="cabecera2">
                                            <th style="background-color: #DDEBF7;">Ode</th>
                                            <th style="background-color: #DDEBF7;">Empresa</th>
                                            <th style="background-color: #DDEBF7;">Carrera / Módulo</th>
                                            <th style="background-color: #DDEBF7;">Inicio / Curso</th>
                                            <th style="background-color: #DDEBF7;">Frecuencia</th>
                                            <th style="background-color: #DDEBF7;">Horario</th>
                                            <th style="background-color: #DDEBF7;">Fecha de Inicio</th>

                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-13 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-12 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-11 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-10 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-9 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-8 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-7 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-6 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-5 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-4 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-3 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-2 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d',strtotime('-1 day'));?></th>
                                            <th style="background-color: #E2EFDA;"><?php echo date('Y-m-d');?></th>
                                            <th style="background-color: #E2EFDA;">Semana Anterior</th>
                                            <th style="background-color: #E2EFDA;">Últimos 7 días</th>
                                            <th style="background-color: #E2EFDA;">Total Inscrito</th>
                                            
                                            <th style="background-color: #FFF2CC;">Meta Máxima</th>
                                            <th style="background-color: #FFF2CC;">Meta Mínima</th>
                                            <th style="background-color: #FFF2CC;">Inicio Campaña</th>
                                            <th style="background-color: #FFF2CC;">Días Campaña</th>
                                            <th style="background-color: #FFF2CC;">Días que falta</th>
                                            <th style="background-color: #FFF2CC;">Índice Semana Anterior</th>
                                            <th style="background-color: #FFF2CC;">Índice Últimos 7 días</th>
                                            <th style="background-color: #FFF2CC;">Proy. días Faltantes</th>
                                            <th style="background-color: #FFF2CC;">Proy. Final</th>
                                            <th style="background-color: #FFF2CC;">Falta para lograr meta</th>
                                            <th style="background-color: #FFF2CC;">Observación</th>
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

