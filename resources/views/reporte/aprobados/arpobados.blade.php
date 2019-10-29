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

{{ Html::style('lib/iCheck/all.css') }}
{{ Html::script('lib/iCheck/icheck.min.js') }}

@include( 'reporte.aprobados.js.aprobados_ajax' )
@include( 'reporte.aprobados.js.aprobados' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Listado de Alumnos Aprobados
            <small>Procesos</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Procesos</li>
            <li class="active">Listado de Alumnos Aprobados</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body no-padding">
                        <form id="AsignacionForm">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial de Aprob</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final de Aprob</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="control-label">Empresa:</label>
                                    <div class="input-group">
                                      <select name="slct_empresas" id="slct_empresas" class="selectpicker"></select>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                            </div>

                            <div class="table-responsive no-padding col-md-12">
                                <table id="TableVisita" class="table table-bordered table-hover">
                                    <thead>
                                        <!--tr>
                                            <th style="background-color: #BDD7EE;color:black; text-align: center;" colspan="11"> DATA </th>
                                            <th style="background-color: #FFF2CC;color:black; text-align: center;" colspan="4"> RESULTADO DE LAS LLAMADAS </th>
                                            <th style="background-color: #FCE4D6;color:black; text-align: center;" colspan="4"> DETALLE DE LOS SI LLAMADOS </th>
                                        </tr-->
                                        <tr>
                                            <th style="background-color: #BDD7EE;color:black">Paterno</th>
                                            <th style="background-color: #BDD7EE;color:black">Materno</th>
                                            <th style="background-color: #BDD7EE;color:black">Nombre</th>
                                            <th style="background-color: #BDD7EE;color:black">Curso</th>
                                            <th style="background-color: #BDD7EE;color:black">Nota</th>
                                            <th style="background-color: #BDD7EE;color:black"># Credito</th>
                                            <th style="background-color: #BDD7EE;color:black">Fecha Aprobado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                        </form>
                    </div><!-- .box-body -->
                </div>
            </div>
        </div><!-- .row -->
    </section><!-- .content -->
        @stop

