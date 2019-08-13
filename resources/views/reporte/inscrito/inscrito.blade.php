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

@include( 'reporte.seminario.js.seminario_ajax' )
@include( 'reporte.seminario.js.seminario' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Reporte de Inscritos de Formación Continua
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
                    <form id="PaeForm">
                        <div class="box-body table-responsive no-padding">
                            <div class="col-sm-12">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial de Inscripción</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_inicial" name="txt_fecha_inicial" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final de Inscripción</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_final" name="txt_fecha_final" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar Resumen</i></a>
                                </div>
                                <div class="col-sm-1" style="padding:24px">&nbsp;
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexportdetalle" name="btnexportdetalle" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar Detalle</i></a>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->

                            <div class="box-body table-responsive no-padding">
                                <table id="TableReporte" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th colspan='5'>DATOS DEL INSCRITO</th>
                                            <th colspan='6'>SOBRE LA INFORMACIÓN CONTINUA</th>
                                            <th colspan='4'>PAGO POR CURSO</th>
                                            <th colspan='2'>PAGO POR CONJUNTO DE CURSOS <hr> PAGO POR INSCRIPCIÓN DE ESPECIALIDAD</th>
                                            <th colspan='6'>DATOS DE LA VENTA</th>
                                        </tr>
                                        <tr class="cabecera">
                                            <th>Nombres</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Celular</th>
                                            <th>Email</th>

                                            <th>Fecha de Inscripción</th>
                                            <th>Donde Estudiará</th>
                                            <th>Empresa</th>
                                            <th>Tipo de Formación Continua</th>
                                            <th>Formación Continua</th>
                                            <th>Fecha a Realizarse</th>
                                        
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>
                                            <th>Tipo Pago</th>
                                            <th>Total Pagado</th>

                                            <th>Nro Recibo PCC / ESP</th>
                                            <th>Monto PCC / ESP</th>

                                            <th>Sede de Inscripción</th>
                                            <th>Recogo del Certificado</th>
                                            <th>Cajero(a)</th>
                                            <th>Vendedor(a)</th>
                                            <th>Supervisor(a)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Nombres</th>
                                            <th>Paterno</th>
                                            <th>Materno</th>
                                            <th>Celular</th>
                                            <th>Email</th>

                                            <th>Fecha de Inscripción</th>
                                            <th>Donde Estudiará</th>
                                            <th>Empresa</th>
                                            <th>Tipo de Formación Continua</th>
                                            <th>Formación Continua</th>
                                            <th>Fecha a Realizarse</th>
                                        
                                            <th>Nro Pago</th>
                                            <th>Monto Pago</th>
                                            <th>Tipo Pago</th>
                                            <th>Total Pagado</th>

                                            <th>Nro Recibo PCC / ESP</th>
                                            <th>Monto PCC / ESP</th>

                                            <th>Sede de Inscripción</th>
                                            <th>Recogo del Certificado</th>
                                            <th>Cajero(a)</th>
                                            <th>Vendedor(a)</th>
                                            <th>Supervisor(a)</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

