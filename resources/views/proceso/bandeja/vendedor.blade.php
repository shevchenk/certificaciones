@extends('layout.masterV2')  

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
    
    @include( 'proceso.bandeja.js.vendedor_ajax' )
    @include( 'proceso.bandeja.js.vendedor' )
@stop

@section('content')
<style>
.modal { overflow: auto !important; }
</style>
<section class="content-header">
    <h1>Vendedor
        <small>Bandeja de Matrículas</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Proceso</a></li>
        <li class="active">Bandeja</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Bandeja a Validar</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Bandeja Histórica</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="col-xs-12 box">
                        <form id="BandejaValidaForm">
                            <h2>.::Bandeja a Validar::.</h2>
                            <div class="box-body table-responsive no-padding">                        
                                <table id="TableBandejaValida" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-xs-1" style="min-width: 125px;">
                                                <input type="text" class="col-md-12 input-lg fecha" name="txt_fecha_matricula" id="txt_fecha_matricula" placeholder="Seleccione Fecha Matrícula">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_trabajador" id="txt_trabajador" placeholder="Buscar Persona Marketing" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_alumno" id="txt_alumno" placeholder="Buscar Alumno" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_carrera" id="txt_carrera" placeholder="Buscar Carrera/Módulo" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_curso" id="txt_curso" placeholder="Buscar Curso" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2 text-center">
                                                <label tabindex="-1" class="label programacion" style="font-size:250% !important;">Programación</label>
                                            </th>
                                            <th class="col-xs-1 text-center">
                                                <label class="label" style="font-size:150% !important;">Estado<br>Matrícula</label>
                                                <select class="col-md-12 input-lg hidden" name="slct_estado_mat" id="slct_estado_mat">
                                                    <option value='Observado' selected>.::Observado - Estado Matrícula::.</option>
                                                </select>
                                            </th>
                                            <th class="col-xs-1" style="min-width: 125px;">
                                                <input type="text" class="col-md-12 input-lg fecha" name="txt_fecha_estado" id="txt_fecha_estado" placeholder="Seleccione Fecha Estado">
                                            </th>
                                            <th class="col-xs-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Fecha Matrícula</th>
                                            <th>Persona Marketing</th>
                                            <th>Alumno</th>
                                            <th>Carrera / Módulo</th>
                                            <th>Curso</th>
                                            <th>Programación</th>
                                            <th>Estado Matrícula</th>
                                            <th>Fecha Estado</th>
                                            <th>[-]</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                        </form><!-- .form -->
                    </div><!-- .box -->        
                </div>

                <div class="tab-pane" id="tab_2">
                    <div class="col-xs-12 box">
                        <form id="BandejaHistoricaForm">
                            <h2>.::Bandeja Histórica::.</h2>
                            <div class="box-body table-responsive no-padding">                        
                                <table id="TableBandejaHistorica" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-xs-1" style="min-width: 125px;">
                                                <input type="text" class="col-md-12 input-lg fecha" name="txt_fecha_matricula" id="txt_fecha_matricula" placeholder="Seleccione Fecha Matrícula">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_trabajador" id="txt_trabajador" placeholder="Buscar Persona Marketing" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_alumno" id="txt_alumno" placeholder="Buscar Alumno" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_carrera" id="txt_carrera" placeholder="Buscar Carrera/Módulo" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2">
                                                <input type="text" class="col-md-12 input-lg" name="txt_curso" id="txt_curso" placeholder="Buscar Curso" onkeypress="return masterG.enterGlobal(event,'.programacion',1);">
                                            </th>
                                            <th class="col-xs-2 text-center">
                                                <label tabindex="-1" class="label programacion" style="font-size:250% !important;">Programación</label>
                                            </th>
                                            <th class="col-xs-1 text-center">
                                                <select class="col-md-12 input-lg" name="slct_estado_mat" id="slct_estado_mat">
                                                    <option value='Marketing' selected>.::Todo - Estado Matrícula::.</option>
                                                    <option value='Pendiente'>Pendiente</option>
                                                    <option value='Pre Aprobado'>Pre Aprobado</option>
                                                    <option value='Aprobado'>Aprobado</option>
                                                    <option value='Anulado'>Anulado</option>
                                                </select>
                                            </th>
                                            <th class="col-xs-1" style="min-width: 125px;">
                                                <input type="text" class="col-md-12 input-lg fecha" name="txt_fecha_estado" id="txt_fecha_estado" placeholder="Seleccione Fecha Estado">
                                            </th>
                                            <th class="col-xs-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Fecha Matrícula</th>
                                            <th>Persona Marketing</th>
                                            <th>Alumno</th>
                                            <th>Carrera / Módulo</th>
                                            <th>Curso</th>
                                            <th>Programación</th>
                                            <th>Estado Matrícula</th>
                                            <th>Fecha Estado</th>
                                            <th>[-]</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                        </form><!-- .form -->
                    </div><!-- .box -->
                </div>
            </div>
        </div><!-- .nav-tabs -->
    </div><!-- .row -->

    <select class="hidden mant" id="slct_tipo_demo">
        <option value="1.1">Transferencia - BCP</option>
        <option value="1.2">Transferencia - Scotiabank</option>
        <option value="1.3">Transferencia - BBVA</option>
        <option value="1.4">Transferencia - Interbank</option>
        <option value="2.1">Depósito - BCP</option>
        <option value="2.2">Depósito - Scotiabank</option>
        <option value="2.3">Depósito - BBVA</option>
        <option value="2.4">Depósito - Interbank</option>
        <option value="3.3" selected>Caja</option>
    </select>
    <form id="FormBandeja">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Datos del Inscrito</div>
                </div>
                <div class="box-body">
                    <div class="col-lg-3 col-md-6 ">
                        <label>DNI:</label>
                        <span class="form-control dni"></span>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label>Paterno:</label>
                        <span class="form-control paterno"></span>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label>Materno:</label>
                        <span class="form-control materno"></span>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label>Nombre:</label>
                        <span class="form-control nombre"></span>
                    </div>
                    <div class="col-lg-12">
                        <label>Observación:</label>
                        <textarea disabled class="form-control obs"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Datos de Formación Continua</div>
                </div>
                <div class="box-body">
                    <div class="col-lg-4 col-md-4 ">
                        <label>Fecha de Inscripción:</label>
                        <span class="form-control fecha_matricula"></span>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label>Estado Matrícula:</label>
                        <span class="form-control estado_mat"></span>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label>Fecha Estado:</label>
                        <span class="form-control fecha_estado"></span>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label>Lugar de Inscripción:</label>
                        <span class="form-control sucursal"></span>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label>Lugar de Estudios:</label>
                        <span class="form-control lugar_estudio"></span>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <label>Carrera / Módulo:</label>
                        <span class="form-control formacion"></span>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color: #DDEBF7;">
                                <th class="text-center">Curso</th>
                                <th class="text-center">Frecuencia</th>
                                <th class="text-center">Horario</th>
                            </tr>
                        </thead>
                        <tbody class="cursos"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Pago de Inscripción</div>
                </div>
                <div class="box-body">
                    <div class="col-lg-4">
                        <label>Nro Pago:</label>
                        <span class="form-control nro_pago_inscripcion"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Monto Pago:</label>
                        <span class="form-control monto_pago_inscripcion"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Tipo Pago:</label>
                        <span class="form-control tipo_pago_inscripcion"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Pago de Matrícula</div>
                </div>
                <div class="box-body">
                    <div class="col-lg-4">
                        <label>Nro Pago:</label>
                        <span class="form-control nro_pago_matricula"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Monto Pago:</label>
                        <span class="form-control monto_pago_matricula"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Tipo Pago:</label>
                        <span class="form-control tipo_pago_matricula"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Pago de Promoción</div>
                </div>
                <div class="box-body">
                    <div class="col-lg-4">
                        <label>Nro Pago:</label>
                        <span class="form-control nro_promocion"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Monto Pago:</label>
                        <span class="form-control monto_promocion"></span>
                    </div>
                    <div class="col-lg-4">
                        <label>Tipo Pago:</label>
                        <span class="form-control tipo_pago_promocion"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Pago por Curso</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color: #DDEBF7;">
                                <th class="text-center">Nro Pago</th>
                                <th class="text-center">Monto Pago</th>
                                <th class="text-center">Tipo Pago</th>
                            </tr>
                        </thead>
                        <tbody class="curso_pagos"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Pago por Cuota</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color: #DDEBF7;">
                                <th class="text-center">Cuota</th>
                                <th class="text-center">Nro Pago</th>
                                <th class="text-center">Monto Pago</th>
                                <th class="text-center">Tipo Pago</th>
                            </tr>
                        </thead>
                        <tbody class="cuotas"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="box box-info">
                <div class="box-header bg-navy-active text-center">
                    <div class="box-title">Datos de Deuda</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color: #DDEBF7;">
                                <th class="text-center">Saldo</th>
                                <th class="text-center">Cuota Completa</th>
                                <th class="text-center">Importe</th>
                            </tr>
                        </thead>
                        <tbody class="deudas"></tbody>
                    </table>
                    <hr>
                    <label class="btns">Texto para Anulado:</label>
                    <textarea rows=3 class="form-control observacion btns" placeholder="Solo texto para Anulado"></textarea>
                </div>
                <div class="box-footer text-right btns">
                    <a class="btn btn-danger btn-lg Anulado"> <i class="fa fa-trash">&nbsp;Anulado</i></a>
                    <a class="btn btn-success btn-lg Aprobado"> <i class="fa fa-check">&nbsp;Actualizar y pasar a Pendiente</i></a>
                </div>
            </div>
        </div>
    </div>
    </form>
</section><!-- .content -->
@stop

@section('form')
     
@stop
