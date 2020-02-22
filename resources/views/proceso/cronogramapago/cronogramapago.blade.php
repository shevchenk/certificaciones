@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    @include( 'proceso.cronogramapago.js.cronogramapago_ajax' )
    @include( 'proceso.cronogramapago.js.cronogramapago' )
@stop

@section('content')
<section class="content-header">
    <h1>Programación de Pagos - <span id="subtitulo"></span>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Programación</a></li>
        <li class="active">Pago</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <form id="EspecialidadProgramacionForm">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableEspecialidad" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                    <th class="col-xs-2" style="width:20% !important;">
                                        <div class="form-group">
                                            <label><h4>Carrera / Módulo:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_especialidad" id="txt_especialidad" placeholder="Carrera / Módulo" onkeypress="return masterG.enterGlobal(event,'#txt_fecha_inicio',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="width:10% !important;">
                                        <div class="form-group">
                                            <label><h4>Tipo de Programación:</h4></label>
                                            <div class="input-group">
                                                <select class="form-control" name="slct_tipo" id="slct_tipo">
                                                    <option value='' selected>.::Todo::.</option>
                                                    <option value='1'>Pago en Cuota(s)</option>
                                                    <option value='2'>Pago por Curso</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:15% !important;">
                                        <div class="form-group">
                                            <label><h4>Odes a aplicar estos pagos:</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="width:10% !important;">
                                        <div class="form-group">
                                            <label><h4>Escala:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_nro_cuota" id="txt_nro_cuota" placeholder="Escala" onkeypress="return masterG.enterGlobal(event,'#txt_fecha_inicio',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:20% !important;">
                                        <div class="form-group">
                                            <label><h4>Cronograma:</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="width:10% !important;">
                                        <div class="form-group">
                                            <label><h4>Estado:</h4></label>
                                            <div class="input-group">
                                                <select class="form-control" name="slct_estado" id="slct_estado">
                                                    <option value='' selected>.::Todo::.</option>
                                                    <option value='0'>Inactivo</option>
                                                    <option value='1'>Activo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="width:5% !important;">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Carrera / Módulo</th>
                                  <th>Tipo de Programación</th>
                                  <th>Odes a aplicar estos pagos</th>
                                  <th>Escala</th>
                                  <th>Cronograma</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>
                    </div><!-- .box-body -->
                </form><!-- .form -->
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
     @include( 'proceso.cronogramapago.form.cronogramapago' )
@stop
