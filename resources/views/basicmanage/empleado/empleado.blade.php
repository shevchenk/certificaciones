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

    @include( 'basicmanage.empleado.js.empleado_ajax' )
    @include( 'basicmanage.empleado.js.empleado' )
    @include( 'basicmanage.empleado.js.listapersona_ajax' )
    @include( 'basicmanage.empleado.js.listapersona' )
    @include( 'basicmanage.persona.js.agregar_editar_persona_ajax' )
    @include( 'basicmanage.persona.js.agregar_editar_persona' )
@stop

@section('content')
<section class="content-header">
    <h1>Empleados
        <small>Mantenimiento</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Mantenimiento</a></li>
        <li class="active">Empleados</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <form id="EmpleadoForm">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableEmpleado" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                  <th>Persona</th>
                                  <th>Sucursal</th>
                                  <th>Cargo</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>     
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Persona</th>
                                  <th>Sucursal</th>
                                  <th>Cargo</th>
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
     @include( 'basicmanage.empleado.form.empleado' )
     @include( 'basicmanage.empleado.form.listapersona' )
     @include( 'basicmanage.persona.form.persona' )
@stop
