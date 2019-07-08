@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    @include( 'mantenimiento.programaciondocente.js.miscursosdocente_ajax' )
    @include( 'mantenimiento.programaciondocente.js.miscursosdocente' )

@stop

@section('content')
<style>
/*.modal{
 max-height: auto !important;
 overflow-y: scroll;
}*/
.modal { overflow: auto !important; 
</style>
<section class="content-header">
    <h1>Mis Cursos Programados
        <small>Mantenimiento</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Mantenimiento</a></li>
        <li class="active">Mis Cursos Programados</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <form id="ProgramacionForm">
                    <input type="hidden" class="mant" name="txt_tipo_curso" value="1">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableProgramacion" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Local de Estudios:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_sucursal" id="txt_sucursal" placeholder="ODE" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Seminario:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_curso" id="txt_curso" placeholder="Seminario" onkeypress="return masterG.enterGlobal(event,'#txt_inicio',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Inicio:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_inicio" id="txt_inicio" placeholder="Inicio" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Final:</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_final" id="txt_final" placeholder="Final" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>ODE</th>
                                  <th>Curso</th>
                                  <th>Inicio</th>
                                  <th>Final</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- .box-body -->
                </form><!-- .form -->
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
     @include( 'mantenimiento.programaciondocente.form.programaciondocente' )
@stop
