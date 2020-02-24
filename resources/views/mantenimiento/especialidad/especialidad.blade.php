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

    @include( 'mantenimiento.especialidad.js.especialidad_ajax' )
    @include( 'mantenimiento.especialidad.js.especialidad' )
@stop

@section('content')
<section class="content-header">
    <h1>Carrera / Módulo
        <small>Mantenimiento</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Mantenimiento</a></li>
        <li class="active">Carrera / Módulo</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <form id="EspecialidadForm">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableEspecialidad" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                    <th class="col-xs-2" style="width:15% !important;">
                                        <div class="form-group">
                                            <label><h4>Carrera / Módulo:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_especialidad" id="txt_especialidad" placeholder="Buscar Carrera / Módulo" onkeypress="return masterG.enterGlobal(event,'#txt_certificado_especialidad',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="width:15% !important;">
                                        <div class="form-group">
                                            <label><h4>Certificado Carrera / Módulo:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_certificado_especialidad" id="txt_certificado_especialidad" placeholder="Buscar Certificado Carrera / Módulo" onkeypress="return masterG.enterGlobal(event,'#txt_especialidad',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-6" style="width:50% !important;">
                                        <div class="form-group">
                                            <label><h4>Inicios / Cursos Asignados:</h4></label>
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
                                    <th class="col-xs-1" style="width:10% !important;">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Carrera / Módulo</th>
                                  <th>Certificado Carrera / Módulo</th>
                                  <th>Inicios / Cursos Asignados</th>
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
     @include( 'mantenimiento.especialidad.form.especialidad' )
@stop
