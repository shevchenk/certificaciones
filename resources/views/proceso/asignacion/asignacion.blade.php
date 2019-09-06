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

@include( 'proceso.asignacion.js.asignacion_ajax' )
@include( 'proceso.asignacion.js.asignacion' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Asignación de Interesados
            <small>Proceso</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Procesos</li>
            <li class="active">Asignación de Interesados</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body no-padding">
                        <form id="IndiceMatForm">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial de Registro</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final de Registro</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                            </div>
                        </form><!-- .form -->
                    

                        <form id="AsignacionForm">
                            <div class="table-responsive no-padding col-sm-5">
                                <table id="TableVisita" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th># Total Interesados</th>
                                            <th># Interesados Sin Asignar</th>
                                            <th># Interesados Asignados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div><!-- .box-body -->
                            <hr>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Asignar a:</label>
                                    
                                    <select id="slct_trabajador" name="slct_trabajador" class="selectpicker form-control show-menu-arrow" data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple>
                                      <option value="">.::Seleccione::.</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Asignar a:</label>
                                    <select id="slct_tipo_asignar" name="slct_tipo_asignar" class="selectpicker form-control show-menu-arrow">
                                        <option value="">.::Todos::.</option>
                                        <option value="1">Sin Asignar</option>
                                        <option value="2">Asignados</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1" style="padding:24px">
                                <span onclick="Guardar();" class="btn btn-primary btn-md" id="btn_asignar" name="btn_asignar"><i class="glyphicon glyphicon-search"></i> Asignar </span>
                            </div>
                        </form>
                    </div><!-- .box-body -->
                </div>
            </div>
        </div><!-- .row -->
    </section><!-- .content -->
        @stop

