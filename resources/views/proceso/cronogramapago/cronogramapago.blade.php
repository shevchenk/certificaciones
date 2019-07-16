@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    {{ Html::script('lib/jQueryUI/jquery-ui.min.js') }}

    @include( 'proceso.cronogramapago.js.cronogramapago_ajax' )
    @include( 'proceso.cronogramapago.js.cronogramapago' )
@stop

@section('content')
<section class="content-header">
    <h1>Programación de Especialidad
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Programación</a></li>
        <li class="active">Especialidad</li>
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
                                            <label><h4>Especialidad:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_especialidad" id="txt_especialidad" placeholder="Buscar Especialidad" onkeypress="return masterG.enterGlobal(event,'#txt_fecha_inicio',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:5% !important;">
                                        <div class="form-group">
                                            <label><h4>Código Inicio:</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:10% !important;">
                                        <div class="form-group">
                                            <label><h4>Fecha Inicio:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_fecha_inicio" id="txt_fecha_inicio" placeholder="Fecha Inicio" onkeypress="return masterG.enterGlobal(event,'#txt_especialidad',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:20% !important;">
                                        <div class="form-group">
                                            <label><h4>Horarios:</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:30% !important;">
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
                                  <th>Especialidad</th>
                                  <th>Código de Inicio</th>
                                  <th>Fecha de Inicio</th>
                                  <th>Horarios</th>
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
