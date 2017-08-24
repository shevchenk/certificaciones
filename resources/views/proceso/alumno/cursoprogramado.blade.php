@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    @include( 'proceso.alumno.js.cursoprogramado_ajax' )
    @include( 'proceso.alumno.js.cursoprogramado' )
@stop

@section('content')
<section class="content-header">
    <h1>Alumnos
        <small>Cursos Programados</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Alumnos</a></li>
        <li class="active">Cursos Programados</li>
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
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4># DNI</h4></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                <input type="text" class="form-control" name="txt_dni" id="txt_dni" placeholder="Buscar # DNI" onkeypress="return masterG.enterGlobal(event,'.input-group',1);">
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
                                  <th>Estado</th>
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
                    <label class="control-label col-sm-3" for="inputSucess3">DNI:</label>
                    <div class="col-sm-9" id="div_dni" style="padding-top: 7px;">44444444</div>
                  </div>
                  <div class="form-group has-primary has-feedback">
                    <label class="control-label col-sm-3" for="inputSucess3">NOMBRES: </label>
                    <div class="col-sm-9" id="div_nombres" style="padding-top: 7px;">Rusbel Arteaga</div>
                  </div>
                </form>
            </div>
        </div>

        <div class="col-xs-12">
            <form id="frmcursoprogramdos">
                <table id="tb_cursos" class="table table-striped">
                    <thead>
                        <tr class="cabecera">
                        <th>#</th>
                        <th>Cursos</th>
                        <th>Fecha Final</th>
                        <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_cursos">
                        <tr>
                        <td>#</td>
                        <td>Curso</td>
                        <td>Fecha Final</td>
                        <td>Nota</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
                <div class="row" style="padding: 5px 15px; text-align: right; padding-top: 0px;">
                    <input type="button" class="btn btn-default" onClick="btnregresar_curso();" id="btnregresar" name="" value="Regresar">
                    <input type="button" class="btn btn-primary" onClick="btnguardar_curso();"  id="btnguardar" name="" value="Guardar">
                </div>
            </form>
        </div>  
    </div>
    <!-- -->

</section><!-- .content -->

<!--
@stop
@section('form')
     @include( 'proceso.alumno.form.alumno' )
@stop

