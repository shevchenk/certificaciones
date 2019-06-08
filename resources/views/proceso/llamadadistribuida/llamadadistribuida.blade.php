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

    @include( 'proceso.llamadadistribuida.js.llamadadistribuida_ajax' )
    @include( 'proceso.llamadadistribuida.js.llamadadistribuida' )
@stop

@section('content')
<section class="content-header">
    <h1>Personas Distribuidas
        <small>Llamada</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Personas Distribuidas</a></li>
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
                                    <th>
                                        <label><h4>Fecha Distribuida</h4></label>
                                        <input type="text" class="form-control" name="txt_fecha_distribucion" id="txt_fecha_distribucion" placeholder="Buscar Fecha Distribuida" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Ultimo Estado</h4></label>
                                        <input type="text" class="form-control" name="txt_tipo_llamada" id="txt_tipo_llamada" placeholder="Buscar Último Estado" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Ape. Paterno</h4></label>
                                        <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Buscar Paterno" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Ape. Materno</h4></label>
                                        <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Buscar Materno" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Nombres</h4></label>
                                        <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" placeholder="Buscar Nombres" onkeypress="return masterG.enterGlobal(event,'#txt_paterno',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Carrera</h4></label>
                                        <input type="text" class="form-control" name="txt_carrera" id="txt_carrera" placeholder="Buscar Carrera" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                    </th>
                                    <th>
                                        <label><h4>Email</h4></label>
                                        <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Buscar Email" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                    </th>
                                    <th>
                                        <label><h4>Teléfono</h4></label>
                                        <input type="text" class="form-control" name="txt_telefono" id="txt_telefono" placeholder="Buscar Teléfono" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Celular</h4></label>
                                        <input type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="Buscar Celular" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);" style="width: 100px;">
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
                                    <th>[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Fecha Distribuida</th>
                                  <th>Último Estado</th>
                                  <th>Nombres</th>
                                  <th>Ape. Paterno</th>
                                  <th>Ape. Materno</th>
                                  <th>Carrera</th>
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

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading" style="background-color: #FFBC9B;color:black"><center>ATENCIÓN PENDIENTE</center></div>
                <div class="panel-body">
                    <div class="col-md-12 table-responsive">
                        <table id="t_matricula" class="table table-striped">
                            <thead style="background-color: #FFBC9B;color:black">
                                <tr>
                                    <th>DNI</th>
                                    <th>Persona</th>
                                    <th>Fecha de Llamada</th>
                                    <th>Teleoperador(a)</th>
                                    <th>Tipo Llamada</th>
                                    <th>Fecha Programada</th>
                                    <th>Comentario</th>
                                    <th>Llamar</th>
                                </tr>
                            </thead>
                            <tbody id="tb_llamada_pendiente">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section><!-- .content -->
@stop
@section('form')
     @include( 'proceso.llamadadistribuida.form.llamadadistribuida' )
@stop

