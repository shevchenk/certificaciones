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

    @include( 'mantenimiento.programacion.js.programacion_ajax' )
    @include( 'mantenimiento.programacion.js.programacion' )
    @include( 'mantenimiento.programacion.js.listadocente_ajax' )
    @include( 'mantenimiento.programacion.js.listadocente2' )
    @include( 'mantenimiento.programacion.js.listapersona_ajax' )
    @include( 'mantenimiento.programacion.js.listapersona' )
    @include( 'mantenimiento.programacion.js.aedocente_ajax' )
    @include( 'mantenimiento.programacion.js.aedocente' )
    @include( 'mantenimiento.programacion.js.aepersona_ajax' )
    @include( 'mantenimiento.programacion.js.aepersona' )
    

@stop

@section('content')
<style>
/*.modal{
 max-height: auto !important;
 overflow-y: scroll;
}*/
.modal { overflow: auto !important; }
</style>
<section class="content-header">
    <h1>Programación de Inicios / Cursos
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Programación</a></li>
        <li class="active"></li>
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
                                    <th class="col-xs-2" style="min-width: 250px;">
                                        <div class="form-group">
                                            <label><h4>Inicio / Curso:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_curso" id="txt_curso" placeholder="Inicio / Curso" onkeypress="return masterG.enterGlobal(event,'#txt_docente',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 180px;">
                                        <div class="form-group">
                                            <label><h4>Docente:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_docente" id="txt_docente" placeholder="Docente" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 150px;">
                                        <div class="form-group">
                                            <label><h4>Local de Estudios:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_sucursal" id="txt_sucursal" placeholder="ODE" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Aula:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_aula" id="txt_aula" placeholder="Aula" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Turno:</h4></label>
                                            <div class="input-group">
                                                <select class="form-control" name="slct_turno" id="slct_turno">
                                                    <option value='' selected>.::Todo::.</option>
                                                    <option value='M'>Mañana</option>
                                                    <option value='T'>Tarde</option>
                                                    <option value='N'>Noche</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Días:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_dia" id="txt_dia" placeholder="Días" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                     <th class="col-xs-2" style="min-width: 150px;">
                                        <div class="form-group">
                                            <label><h4>Fecha Inicio:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_inicio" id="txt_inicio" placeholder="Fecha Inicio" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 150px;">
                                        <div class="form-group">
                                            <label><h4>Fecha Final:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_final" id="txt_final" placeholder="Fecha Final" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Hora Inicio:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_hora_inicio" id="txt_hora_inicio" placeholder="Hora Inicio" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Hora Final:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_hora_final" id="txt_hora_final" placeholder="Hora Final" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 150px;">
                                        <div class="form-group">
                                            <label><h4>Semestre:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_semestre" id="txt_semestre" placeholder="Semestre" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2" style="min-width: 120px;">
                                        <div class="form-group">
                                            <label><h4>Campaña:</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_campaña" id="txt_campaña" placeholder="Campaña" onkeypress="return masterG.enterGlobal(event,'#txt_curso',1);">
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <th class="col-xs-2">
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
                                    <th class="col-xs-1">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Inicio Curso</th>
                                  <th>Docente</th>
                                  <th>ODE</th>
                                  <th>Aula</th>
                                  <th>Turno</th>
                                  <th>Días</th>
                                  <th>Fecha Inicio</th>
                                  <th>Fecha Final</th>
                                  <th>Hora Inicio</th>
                                  <th>Hora Final</th>
                                  <th>Semestre</th>
                                  <th>Campaña</th>
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
     @include( 'mantenimiento.programacion.form.programacion' )
     @include( 'mantenimiento.programacion.form.listadocente2' )
     @include( 'mantenimiento.programacion.form.listapersona' )
     @include( 'mantenimiento.docente.form.docente' )
     @include( 'mantenimiento.persona.form.persona' )
@stop
