@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

    @include( 'proceso.alumnosaldo.js.alumnosaldo_ajax' )
    @include( 'proceso.alumnosaldo.js.alumnosaldo' )
@stop

@section('content')
<section class="content-header">
    <h1>Descarga de Certificados
        <small>  </small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> - </a></li>
        <li class="active">Descarga de Certificados</li>
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
                                        <label><h4>DNI</h4></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                            <input type="text" class="form-control" name="txt_dni" id="txt_dni" placeholder="DNI" onkeypress="return masterG.enterGlobal(event,'txt_nombre',1);">
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <label><h4>Ape. Paterno</h4></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                            <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Buscar Paterno" onkeypress="return masterG.enterGlobal(event,'txt_dni',1);">
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <label><h4>Ape. Materno</h4></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                            <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Buscar Materno" onkeypress="return masterG.enterGlobal(event,'txt_dni',1);">
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <label><h4>Nombres</h4></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                            <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" placeholder="Buscar Nombres" onkeypress="return masterG.enterGlobal(event,'txt_dni',1);">
                                        </div>
                                    </th>

                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Curso</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Saldo</h4></label>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th># DNI</th>
                                  <th>Ape. Paterno</th>
                                  <th>Ape. Materno</th>
                                  <th>Nombres</th>
                                  <th>Curso</th>
                                  <th>Saldo</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- .box-body -->
                </form><!-- .form -->
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->

    <!-- NUEVOS PROCESOS -->
    <div id="div_cursos_progra" class="row" style="display: none;">
        <div class="col-xs-12">
            <div class="well well-lg" style="background-color: #FFF;">
                <form class="form-horizontal">
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-1" for="" style="text-align: left;">DNI:</label>
                    <div class="col-sm-2" id="div_dni" style="padding-top: 7px;"></div>
                    <label class="control-label col-sm-1" for="" style="text-align: left;">CURSO:</label>
                    <div class="col-sm-2" id="div_curso" style="padding-top: 7px;"></div>
                  </div>
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-1" for="" style="text-align: left;">NOMBRES: </label>
                    <div class="col-sm-2" id="div_nombres" style="padding-top: 7px;"></div>
                    <label class="control-label col-sm-1" for="" style="text-align: left;">SALDO:</label>
                    <div class="col-sm-2" id="div_saldo" style="padding-top: 7px;"></div>
                  </div>
                </form>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>Histórico de Saldos</center></div>
                <div class="panel-body">
                    <div class="col-md-12 table-responsive">
                        <form id="form_saldos">
                        <table id="t_matricula" class="table table-striped">
                            <thead style="background-color: #A9D08E;color:black">
                                <tr>
                                    <th>Precio.</th>
                                    <th>Pago</th>
                                    <th>N° de Boleta/N° de Operación</th>
                                    <th>Saldo</th>
                                    <th>Tipo Operación</th>
                                    <th>Archivo Pago Actual</th>
                                </tr>
                            </thead>
                            <tbody id="tb_matricula">
                            </tbody>
                        </table>
                        </form>
                        <div class="row" style="padding: 5px 15px; text-align: right; padding-top: 0px;">
                            <input type="button" class="btn btn-default" onClick="btnregresar_curso();" id="btnregresar" name="" value="Regresar">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- -->

</section><!-- .content -->
@stop
@section('form')
@stop

