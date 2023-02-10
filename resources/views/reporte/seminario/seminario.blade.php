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
                                <div class="col-sm-2" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                                <div class="col-sm-3" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar Resumen</i></a>
                                </div>
                                <div class="col-sm-3" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexportdetalle" name="btnexportdetalle" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar para Ficha de Inscripción</i></a>
                                </div>
                                <!-- <div class="col-sm-3" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexportpdf" name="btnexportpdf" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Exportar Ficha PDF</i></a>
                                </div> -->
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->

                            <div class="box-body table-responsive no-padding">
                                <table id="TableReporte" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #F2DCDB;" colspan='6'>DATOS DEL INSCRITO</th>
                                            <th style="background-color: #C5D9F1;" colspan='11'>SOBRE LA INFORMACIÓN CONTINUA</th>
                                            <th style="background-color: #FFFF00;" colspan='4'>PAGO POR CURSO</th>
                                            <th style="background-color: #8DB4E2;" colspan='3'>PAGO POR CONJUNTO DE CURSOS</th>
                                            <th style="background-color: #8DB4E2;" colspan='3'>PAGO POR INSCRIPCIÓN</th>
                                            <th style="background-color: #FCD5B4;" colspan='6'>DATOS DE LA VENTA</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #F2DCDB;">DNI</th>
                                            <th style="background-color: #F2DCDB;">Nombres</th>
                                            <th style="background-color: #F2DCDB;">Paterno</th>
                                            <th style="background-color: #F2DCDB;">Materno</th>
                                            <th style="background-color: #F2DCDB;">Celular</th>
                                            <th style="background-color: #F2DCDB;">Email</th>

                                            <th style="background-color: #C5D9F1;">Validó Email?</th>
                                            <th style="background-color: #C5D9F1;">Fecha de Inscripción</th>
                                            <th style="background-color: #C5D9F1;">Donde Estudiará</th>
                                            <th style="background-color: #C5D9F1;">Empresa</th>
                                            <th style="background-color: #C5D9F1;">Tipo de Formación Continua</th>
                                            <th style="background-color: #C5D9F1;">Carrera / Módulo</th>
                                            <th style="background-color: #C5D9F1;">Inicio / Curso</th>
                                            <th style="background-color: #C5D9F1;">Frecuencia</th>
                                            <th style="background-color: #C5D9F1;">Horario</th>
                                            <th style="background-color: #C5D9F1;">Turno</th>
                                            <th style="background-color: #C5D9F1;">Inicio</th>
                                        
                                            <th style="background-color: #FFFF00;">Nro Pago</th>
                                            <th style="background-color: #FFFF00;">Monto Pago</th>
                                            <th style="background-color: #FFFF00;">Tipo Pago</th>
                                            <th style="background-color: #FFFF00;">Total Pagado</th>

                                            <th style="background-color: #8DB4E2;">Nro Recibo PCC</th>
                                            <th style="background-color: #8DB4E2;">Monto PCC</th>
                                            <th style="background-color: #8DB4E2;">Tipo Pago</th>

                                            <th style="background-color: #8DB4E2;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #8DB4E2;">Monto Inscripción</th>
                                            <th style="background-color: #8DB4E2;">Tipo Pago</th>

                                            <th style="background-color: #FCD5B4;">Sede de Inscripción</th>
                                            <th style="background-color: #FCD5B4;">Recogo del Certificado</th>
                                            <th style="background-color: #FCD5B4;">Cajero(a)</th>
                                            <th style="background-color: #FCD5B4;">Vendedor(a)</th>
                                            <th style="background-color: #FCD5B4;">Medio Captación</th>
                                            <th style="background-color: #FCD5B4;">Supervisor(a)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="background-color: #F2DCDB;">DNI</th>
                                            <th style="background-color: #F2DCDB;">Nombres</th>
                                            <th style="background-color: #F2DCDB;">Paterno</th>
                                            <th style="background-color: #F2DCDB;">Materno</th>
                                            <th style="background-color: #F2DCDB;">Celular</th>
                                            <th style="background-color: #F2DCDB;">Email</th>

                                            <th style="background-color: #C5D9F1;">Validó Email?</th>
                                            <th style="background-color: #C5D9F1;">Fecha de Inscripción</th>
                                            <th style="background-color: #C5D9F1;">Donde Estudiará</th>
                                            <th style="background-color: #C5D9F1;">Empresa</th>
                                            <th style="background-color: #C5D9F1;">Tipo de Formación Continua</th>
                                            <th style="background-color: #C5D9F1;">Carrera / Módulo</th>
                                            <th style="background-color: #C5D9F1;">Inicio / Curso</th>
                                            <th style="background-color: #C5D9F1;">Frecuencia</th>
                                            <th style="background-color: #C5D9F1;">Horario</th>
                                            <th style="background-color: #C5D9F1;">Turno</th>
                                            <th style="background-color: #C5D9F1;">Inicio</th>
                                        
                                            <th style="background-color: #FFFF00;">Nro Pago</th>
                                            <th style="background-color: #FFFF00;">Monto Pago</th>
                                            <th style="background-color: #FFFF00;">Tipo Pago</th>
                                            <th style="background-color: #FFFF00;">Total Pagado</th>

                                            <th style="background-color: #8DB4E2;">Nro Recibo PCC</th>
                                            <th style="background-color: #8DB4E2;">Monto PCC</th>
                                            <th style="background-color: #8DB4E2;">Tipo Pago</th>

                                            <th style="background-color: #8DB4E2;">Nro Recibo Inscripción</th>
                                            <th style="background-color: #8DB4E2;">Monto Inscripción</th>
                                            <th style="background-color: #8DB4E2;">Tipo Pago</th>

                                            <th style="background-color: #FCD5B4;">Sede de Inscripción</th>
                                            <th style="background-color: #FCD5B4;">Recogo del Certificado</th>
                                            <th style="background-color: #FCD5B4;">Cajero(a)</th>
                                            <th style="background-color: #FCD5B4;">Vendedor(a)</th>
                                            <th style="background-color: #FCD5B4;">Medio Captación</th>
                                            <th style="background-color: #FCD5B4;">Supervisor(a)</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

