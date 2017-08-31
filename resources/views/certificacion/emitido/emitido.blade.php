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

@include( 'certificacion.emitido.js.emitido_ajax' )
@include( 'certificacion.emitido.js.emitido' )

@stop

@section('content')
<section class="content-header">
    <h1>Emitidos
        <small>Proceso</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Proceso</li>
        <li class="active">Emitidos</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Procesos - Emitidos</h3>
                </div>
                <div class="box-body with-border">
                <form id="BandejaForm">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading" style="background-color: #337ab7;color:#fff">
                                <center>.::Listado::.</center>
                            </div>
                            <div class="panel-body table-responsive no-padding">
                                <div class="col-md-12">
                                    <table id="TableBandeja" class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="cabecera">
                                                <th class="col-xs-1" style="text-align: center">
                                                    <div class="form-group">
                                                        <label><h4>[-]</h4></label>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>ODE:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_sucursal" id="txt_sucursal" placeholder="ODE" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>DNI:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_dni" id="txt_dni" placeholder="DNI" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Paterno:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Paterno" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Materno:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Materno" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Nombre:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" placeholder="Nombre" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Trámite:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_tramite" id="txt_tramite" placeholder="Trámite" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Fecha Ingreso:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_fecha_ingreso" id="txt_fecha_ingreso" placeholder="Fecha Ingreso" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-2" style="min-width: 150px;">
                                                    <div class="form-group">
                                                        <label><h4>Fecha Trámite:</h4></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                            <input type="text" class="form-control" name="txt_fecha_tramite" id="txt_fecha_tramite" placeholder="Fecha Trámite" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="col-xs-1" style="text-align: center">
                                                    <div class="form-group">
                                                        <label><h4>[-]</h4></label>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
    @include( 'certificacion.emitido.form.emitido' )
@stop
