@extends('layout.master')

@section('include')
    @parent
    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    {{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

    @include( 'proceso.formatos.js.cargaalumno_ajax' )
    @include( 'proceso.formatos.js.cargaalumno' )

@stop

@section('content')
<section class="content-header">
    <h1>Alumnos
        <small>Formato para Carga de Datos</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Alumnos</a></li>
        <li class="active">Formato para Carga de Datos</li>
    </ol>
</section>

        <!-- Main content -->
        <section class="content">
            <!-- Inicia contenido -->
            <div class="form-group">
                <form id="form" name="form" action="" method="post">
                    <div class="col-sm-12">&nbsp;</div>
                    <div class="col-sm-12" style="background-color: #FFF;">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                          <div class="form-group text-center">
                              <label class="control-label">Tipo Formato</label>
                              <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_formato_carga" name="slct_formato_carga">
                                  <option value="0">.:: Seleccione una opción :.</option>
                                  <option value="S"> Seminario </option>
                                  <option value="M"> Matricula(PAE) </option>
                              </select>
                          </div>
                        </div>
                        <div class="col-sm-2 text-center">
                            <label class="control-label">Fecha Inicial</label>
                            <div class="input-group">
                              <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                              <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_inicial" name="txt_fecha_inicial" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center">
                            <label class="control-label">Fecha Final</label>
                            <div class="input-group">
                              <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                              <input type="text" class="form-control fecha" placeholder="AAAA-MM" id="txt_fecha_final" name="txt_fecha_final" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="form-group text-center">
                              <label>&nbsp;</label>
                              <div class="col-sm-1" style="padding:24px">
                                  <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Generar Formato</i></a>
                              </div>
                          </div>
                        </div>
                    </div>
                </form>

              <!--
                <hr>
                <br><br>
                <div class="col-sm-12">
                    <div class="col-sm-4">
                    &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th style="text-align:center">No pasaron</th>
                            </head>
                            <tbody id="resultado">
                                <tr>
                                <td>&nbsp;</td>
                                </tr>
                            </body>
                        </table>
                    </div>
                </div>
              -->
            </div><!-- /.box -->
            <!-- Finaliza contenido -->
        </div>
    </section><!-- /.content -->
@stop
