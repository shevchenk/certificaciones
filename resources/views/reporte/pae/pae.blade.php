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

@include( 'reporte.pae.js.pae_ajax' )
@include( 'reporte.pae.js.pae' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>PAE
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">PAE</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="PaeForm">
                        <div class="box-body table-responsive no-padding">
                            <div class="col-sm-12">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Export</i></a>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport2" name="btnexport2" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Export Desglosado</i></a>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                            <div class="box-body table-responsive no-padding">
                                <table id="TablePae" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th colspan='7'>Alumnos</th>
                                            <th colspan='3'>Matrícula</th>
                                            <th colspan='2'>Inscripción</th>
                                            <th colspan='2'>Matricula</th>
                                            <th colspan='5'>Curso(s)</th>
                                            <th colspan='2'>Promocion(es)</th>
                                            <th colspan='2'>Pagos</th>
                                            <th colspan='3'>Responsables</th>
                                        </tr>
                                        <tr class="cabecera">
                                            <th>DNI</th>
                                            <th>Nombres</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
                                            <th>Dirección</th>

                                            <th>Fecha  Matrícula</th>
                                            <th>ODE</th>
                                            <th>Tipo de Participante</th>
                                            
                                            <th>Nro Pago Ins</th>
                                            <th>Monto Pago Ins</th>
                                            <th>Nro Pago Mat</th>
                                            <th>Monto Pago Mat</th>

                                            <th>Modalidad</th>
                                            <th>Cursos</th>
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                        <!--
                                            <th>Curso</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                            <th>Curso</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                        -->
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>

                                            <th>Sub Total Curso</th>
                                            <th>Total Pagado</th>

                                            <th>Marketing</th>
                                            <th>Caja</th>
                                            <th>Matrícula</th>
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
                                            <th>Dirección</th>

                                            <th>Fecha  Matrícula</th>
                                            <th>ODE</th>
                                            <th>Tipo de Participante</th>
                                            
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>

                                            <th>Modalidad</th>
                                            <th>Cursos</th>
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                        <!--
                                            <th>Curso</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                            <th>Curso</th>
                                            <th>Nro Pago Certificado</th>
                                            <th>Monto Pago Certificado</th>
                                        -->
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>

                                            <th>Sub Total Sem</th>
                                            <th>Total Pagado</th>

                                            <th>Marketing</th>
                                            <th>Caja</th>
                                            <th>Matrícula</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

