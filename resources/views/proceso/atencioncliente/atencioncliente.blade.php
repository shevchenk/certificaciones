@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

    @include( 'proceso.atencioncliente.js.atencioncliente_ajax' )
    @include( 'proceso.atencioncliente.js.atencioncliente' )
@stop

@section('content')
<section class="content-header">
    <h1>Atención al Cliente
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> - </a></li>
        <li class="active">Atención al Cliente</li>
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
                                                <input type="text" class="form-control" name="txt_dni" id="txt_dni" placeholder="Buscar DNI" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Ape. Paterno</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Buscar Paterno" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>                                          
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Ape. Materno</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Buscar Materno" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>   
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Nombres</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" placeholder="Buscar Nombres" onkeypress="return masterG.enterGlobal(event,'#txt_paterno',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Carrera</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_carrera" id="txt_carrera" placeholder="Buscar Carrera" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Email</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Buscar Email" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-1">
                                        <div class="form-group">
                                            <label><h4>Teléfono</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_telefono" id="txt_telefono" placeholder="Buscar Teléfono" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col-xs-2">
                                        <div class="form-group">
                                            <label><h4>Celular</h4></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="Buscar Celular" onkeypress="return masterG.enterGlobal(event,'#txt_nombre',1);">
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
                    <label class="control-label col-sm-1" for="" style="text-align: left;">TELÉFONO / CELULAR:</label>
                    <div class="col-sm-4" id="div_celular" style="padding-top: 7px;"></div>
                  </div>
                </form>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>SEMINARIOS MATRICULADOS</center></div>
                <div class="panel-body">
                    <div class="col-md-12 table-responsive">
                        <table id="t_matricula" class="table table-striped">
                            <thead style="background-color: #A9D08E;color:black">
                                <tr>
                                    <th>Mod.</th>
                                    <th>Seminarios</th>
                                    <th>Docente</th>
                                    <th>Fecha de Seminario</th>
                                    <th>Horario</th>
                                    <th>Local del Seminario</th>
                                    <th>Registrar</th>
                                </tr>
                            </thead>
                            <tbody id="tb_matricula">
                            </tbody>
                        </table>
                        <div class="row" style="padding: 5px 15px; text-align: right; padding-top: 0px;">
                            <input type="button" class="btn btn-success btn-lg" onClick="ConfirmarEntregaPersonal(0);" id="btnpersonal" name="" value="Registrar Llamada Personal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="button" class="btn btn-default btn-lg" onClick="btnregresar_curso();" id="btnregresar" name="" value="Regresar">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading" style="background-color: #FFBC9B;color:black"><center>ATENCIÓN PENDIENTE</center></div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="col-sm-2 text-center">
                            <label class="control-label">Fecha Inicial - Registro</label>
                            <div class="input-group">
                              <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                              <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" value="<?php echo date('Y-m-d');?>" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center">
                            <label class="control-label">Fecha Final - Registro</label>
                            <div class="input-group">
                              <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                              <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" value="<?php echo date('Y-m-d');?>" readonly/>
                            </div>
                        </div>
                        <div class="col-md-1" style="padding:24px">
                            <span class="btn btn-primary btn-md" onclick="GenerarPendientes();" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Listar</span>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table id="t_matricula" class="table table-striped">
                            <thead style="background-color: #FFBC9B;color:black">
                                <tr>
                                    <th>DNI</th>
                                    <th>Persona</th>
                                    <th>Tipo Registro</th>
                                    <th>Fecha de Registro</th>
                                    <th>Comentario</th>
                                    <th>Fecha de Respuesta</th>
                                    <th>Respuesta</th>
                                    <th>Registrar</th>
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
    <!-- -->

</section><!-- .content -->
@stop
@section('form')
     @include( 'proceso.atencioncliente.form.atencioncliente' )
@stop

