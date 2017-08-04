@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    @include( 'basicmanage.cargo.js.cargo_ajax' )
    @include( 'basicmanage.cargo.js.cargo' )
@stop

@section('content')
<section class="content-header">
    <h1>Cargos
        <small>Mantenimiento</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Mantenimiento</a></li>
        <li class="active">Cargos</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table id="TableCargo" class="table table-bordered table-hover">
                        <thead>
                            <tr class="cabecera">
                              <th>Cargo</th>
                              <th>Estado</th>
                              <th>[-]</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr class="cabecera">
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
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
     @include( 'basicmanage.cargo.form.cargo' )
@stop
