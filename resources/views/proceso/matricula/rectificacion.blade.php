@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    @include( 'proceso.matricula.js.rectificacion_ajax' )
    @include( 'proceso.matricula.js.rectificacion' )

    @include( 'proceso.matricula.js.rectificalistaprogramacion_ajax' )
    @include( 'proceso.matricula.js.rectificalistaprogramacion' )
@stop

@section('content')
<section class="content-header">
    <h1>Rectificaci&oacute;n de Matriculas
        <small>Alumnos</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Alumnos</a></li>
        <li class="active">Rectificaci&oacute;n de Matriculas</li>
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
                                                <!-- <div class="input-group-addon"><i class="fa fa-search"></i></div> -->
                                                <!-- <input type="text" class="form-control" name="txt_dni" id="txt_dni" placeholder="Buscar # DNI" onkeypress="return masterG.enterGlobal(event,'.input-group',1);"> -->
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
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Direccion</h4></label>
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
                                  <th>Dirección</th>

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

    <!-- NUEVOS PROCESOS -->
    <div id="div_cursos_progra" class="row" style="display: none;">
        <div class="col-xs-12">
            <div class="well well-lg" style="background-color: #FFF;">
                <form class="form-horizontal">
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-1" for="" style="text-align: left;">DNI:</label>
                    <div class="col-sm-4" id="div_dni" style="padding-top: 7px;"></div>
                    <label class="control-label col-sm-1" for="" style="text-align: left;">EMAIL:</label>
                    <div class="col-sm-4" id="div_email" style="padding-top: 7px;"></div>
                  </div>
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-1" for="" style="text-align: left;">NOMBRES: </label>
                    <div class="col-sm-4" id="div_nombres" style="padding-top: 7px;"></div>
                    <label class="control-label col-sm-1" for="" style="text-align: left;">TELEFONO:</label>
                    <div class="col-sm-4" id="div_telefono" style="padding-top: 7px;"></div>
                  </div>
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-1" for="" style="text-align: left;">DIRECCION: </label>
                    <div class="col-sm-4" id="div_direccion" style="padding-top: 7px;"></div>
                    <label class="control-label col-sm-1" for="" style="text-align: left;">CELULAR:</label>
                    <div class="col-sm-4" id="div_celular" style="padding-top: 7px;"></div>
                  </div>
                </form>
            </div>
        </div>

        <div class="col-xs-12">
            <form id="frmcursoprogramdos">
                <div class="box-body table-responsive no-padding">
                    <table id="tb_tabla2" class="table table-striped">
                        <thead>
                            <tr class="cabecera">
                            <th>#</th>
                            <th>Tipo Participante</th>
                            <th>ODE</th>
                            <th>Paterno</th>
                            <th>Materno</th>
                            <th>Nombre</th>
                            <th>Fecha Matri.</th>
                            <th width="8%">[]</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_cursos">
                            <tr>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            </tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                    <div class="row" style="padding: 5px 15px; text-align: right; padding-top: 0px;">
                        <input type="button" class="btn btn-default" onClick="btnregresar_curso();" id="btnregresar" name="" value="Regresar">
                        <!-- <input type="button" class="btn btn-primary" onClick="btnguardar_curso();"  id="btnguardar" name="" value="Guardar"> -->
                    </div>
                </div>
            </form>
        </div>  

    </div>
    <!-- -->

    <!-- Detalles -->
    <br/><br/>
    <div class="col-xs-12" id="div_tabla2_deta" style="display: none;">
        <form id="frmtabla2deta">
            <div class="box-body table-responsive no-padding">
                <table id="tb_tabla2_deta" class="table table-striped">
                    <thead>
                        <tr class="cabecera" style="text-align: center;">
                            <th colspan='7'>DETALLES DE MATRICULA</th>
                        </tr>

                        <tr class="cabecera">
                        <th>#</th>
                        <th>Curso</th>
                        <th>Docente</th>
                        <th>Dia</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Final</th>
                        <th width="7%">[]</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_tabla2">
                        <tr>
                        <td>#</td>
                        <td>#</td>
                        <td>#</td>
                        <td>#</td>
                        <td>#</td>
                        <td>#</td>
                        <td>#</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
                <div class="row" style="padding: 5px 15px; text-align: right; padding-top: 0px;">
                    <input type="button" class="btn btn-default" onClick="btnregresar_curso();" id="btnregresar2" name="" value="Regresar">
                </div>
            </div>
        </form>
    </div>
    <!-- -->    


</section><!-- .content -->
@stop

@section('form')
@include( 'proceso.matricula.form.rectificalistaprogramacion' )
@stop
