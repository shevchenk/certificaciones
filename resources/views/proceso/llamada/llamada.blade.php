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

    @include( 'proceso.llamada.js.llamada_ajax' )
    @include( 'proceso.llamada.js.llamada' )
@stop

@section('content')
<section class="content-header">
    <h1>Alumnos
        <small>Llamada</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Alumnos</a></li>
        <li class="active">Llamada</li>
    </ol>
</section>

<section class="content">
    <div class="row" id="div_alumnos_mat">
        <div class="col-xs-12">
            <div class="box">
                <form id="EspecialidadForm">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableDatos" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4># DNI</h4></label>
                                            <div class="input-group">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Nombres</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" placeholder="Buscar Nombres" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Ape. Paterno</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Buscar Paterno" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                            </div>                                          
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Ape. Materno</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Buscar Materno" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
                                            </div>   
                                        </div>
                                    </th>

                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Email</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Telefono</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Celular</h4></label>
                                        </div>
                                    </th>

                                <!--
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Estado</h4></label>
                                            <div class="input-group">
                                                <select class="form-control" name="slct_estado" id="slct_estado">
                                                    <option value='' selected>.::Todo::.</option>
                                                    <option value='0'>Inactivo</option>
                                                    <option value='1'>Activo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                -->
                                    <th class="col-xs-1">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th># DNI</th>
                                  <th>Nombres</th>
                                  <th>Ape. Paterno</th>
                                  <th>Ape. Materno</th>
                                  <th>Email</th>
                                  <th>Telefono</th>
                                  <th>Celular</th>

                                  <!-- <th>Estado</th> -->
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                    <!--
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>
                    -->
                    </div><!-- .box-body -->
                </form><!-- .form -->
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->

</section><!-- .content -->
@stop
@section('form')
     @include( 'proceso.llamada.form.llamada' )
@stop
