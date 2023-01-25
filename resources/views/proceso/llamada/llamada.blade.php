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

    {{ Html::style('lib/EasyAutocomplete1.3.5/easy-autocomplete.min.css') }}
    {{ Html::script('lib/EasyAutocomplete1.3.5/jquery.easy-autocomplete.min.js') }}

    @include( 'proceso.llamada.js.llamada_ajax' )
    @include( 'proceso.llamadadistribuida.js.llamadadistribuida' )
    @include( 'mantenimiento.persona.js.persona_adicional_ajax' )
    @include( 'mantenimiento.persona.js.persona_adicional' )
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
                    <div class="col-sm-2 text-center">
                        <label>Fecha Distribuida Inicio:</label>
                        <input type="text" class="form-control fechas" name="txt_fecha_distribucion_ini" id="txt_fecha_distribucion_ini" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-sm-2 text-center">
                        <label>Fecha Distribuida Final:</label>
                        <input type="text" class="form-control fechas" name="txt_fecha_distribucion_fin" id="txt_fecha_distribucion_fin" placeholder="Fecha Final">
                    </div>
                    <div class="col-sm-2 text-center">
                        <br>
                        <span class="btn btn-primary btn-lg btn_consultar">
                            <i class="glyphicon glyphicon-search"></i>Consultar
                        </span>
                    </div>
                    <div class="col-sm-2 text-center">
                        <br>
                        <span class="btn btn-success btn-lg btn_exportar">
                            <i class="glyphicon glyphicon-download"></i>Exportar
                        </span>
                    </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <form id="EspecialidadForm">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableDatos" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                    <th>[-]</th>
                                    <th>
                                        <label><h4>Ultimo Estado</h4></label>
                                        <input type="text" class="form-control" name="txt_tipo_llamada" id="txt_tipo_llamada" placeholder="Último Estado" onkeypress="return masterG.enterGlobal(event,'#txt_nombre_completo',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Fecha Estado</h4></label>
                                        <input type="text" class="form-control" name="txt_fecha_llamada" id="txt_fecha_llamada" placeholder="Fecha Estado" onkeypress="return masterG.enterGlobal(event,'#txt_nombre_completo',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Vendedor</h4></label>
                                        <input type="text" class="form-control" name="txt_vendedor" id="txt_vendedor" placeholder="Vendedor" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Fecha Leads</h4></label>
                                        <input type="text" class="form-control" name="txt_fecha_registro" id="txt_fecha_registro" placeholder="Fecha Leads" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Fecha Distribuida</h4></label>
                                        <input type="text" class="form-control" name="txt_fecha_distribucion" id="txt_fecha_distribucion" placeholder="Fecha Distribuida" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Nombre Completo</h4></label>
                                        <input type="text" class="form-control" name="txt_nombre_completo" id="txt_nombre_completo" placeholder="Nombre Completo" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Celular</h4></label>
                                        <input type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="Celular" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Email</h4></label>
                                        <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Email" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);">
                                    </th>
                                    <th>
                                        <label><h4>Estudios que solicita</h4></label>
                                        <input type="text" class="form-control" name="txt_carrera" id="txt_carrera" placeholder="Estudios que solicita" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);">
                                    </th>
                                    <th>
                                        <label><h4>Fuente</h4></label>
                                        <input type="text" class="form-control" name="txt_fuente" id="txt_fuente" placeholder="Fuente" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Campaña</h4></label>
                                        <input type="text" class="form-control" name="txt_campana" id="txt_campana" placeholder="Materno" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 130px;">
                                    </th>
                                    <th>
                                        <label><h4>Región</h4></label>
                                        <input type="text" class="form-control" name="txt_region" id="txt_region" placeholder="Región" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 100px;">
                                    </th>
                                    <th>
                                        <label><h4>Responsable</h4></label>
                                        <input type="text" class="form-control" name="txt_responsable" id="txt_responsable" placeholder="Región" onkeypress="return masterG.enterGlobal(event,'#txt_tipo_llamada',1);" style="width: 150px;">
                                    </th>
                                    <th>[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>[-]</th>
                                  <th>Último Estado</th>
                                  <th>Fecha Estado</th>
                                  <th>Vendedor</th>
                                  <th>Fecha Leads</th>
                                  <th>Fecha Distribuida</th>
                                  <th>Nombre Completo</th>
                                  <th>Celular</th>
                                  <th>Email</th>
                                  <th>Estudios que solicita</th>
                                  <th>Fuente</th>
                                  <th>Campaña</th>
                                  <th>Región</th>
                                  <th>Responsable</th>
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
                                    <th>Interesado</th>
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
     @include( 'proceso.llamadadistribuida.form.persona' )
@stop

