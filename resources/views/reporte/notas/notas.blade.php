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

@include( 'reporte.notas.js.notas_ajax' )
@include( 'reporte.notas.js.notas' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>NOTAS
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">NOTAS</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="NotasForm">
                        <div class="box-body table-responsive no-padding">
                            <div class="col-sm-12">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2">
                                    <label class="control-label">Matricula Desde:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                        <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_inicial" name="txt_fecha_inicial" readonly=""/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Matricula Hasta:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                        <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_final" name="txt_fecha_final" readonly=""/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Programación Desde:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini_progra" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_inicial_progra" name="txt_fecha_inicial_progra" readonly=""/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Programación Hasta:</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin_progra" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_final_progra" name="txt_fecha_final_progra" readonly=""/>
                                    </div>  
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                        <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                    </div>
                                    <div class="col-sm-1" style="padding:24px">
                                        <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Export</i></a>
                                    </div>
                                </div>
                            </div><!-- .box-body -->
                        </form><!-- .form -->
                            <div class="box-body table-responsive no-padding">
                                <table id="TableNotas" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th colspan='6'>Alumnos</th>
                                            <th colspan='6'>Programación</th>
                                            <th colspan='1'>Nota del Curso</th>
                                            <th colspan='2'>Certificado</th>
                                            <th colspan='2'>Promoción</th>
                                        </tr>
                                        <tr class="cabecera">
                                            <th>DNI</th>
                                            <th>Nombres</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
                                            
                                            <th>ODE</th>
                                            <th>Fecha de Inicio</th>
                                            <th>Fecha de Fin</th>
                                            <th>Docente</th>
                                            <th>Modalidad</th>
                                            <th>Curso</th>

                                            <th>Nota del Curso</th>

                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>

                                            <th>Nro Pago Promoción</th>
                                            <th>Monto Pago Promoción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>DNI</th>
                                            <th>Nombres</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
                                            
                                            <th>ODE</th>
                                            <th>Fecha de Inicio</th>
                                            <th>Fecha de Fin</th>
                                            <th>Docente</th>
                                            <th>Modalidad</th>
                                            <th>Curso</th>

                                            <th>Nota del Curso</th>

                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>

                                            <th>Nro Pago Promoción</th>
                                            <th>Monto Pago Promoción</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

