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

{{ Html::style('lib/iCheck/all.css') }}
{{ Html::script('lib/iCheck/icheck.min.js') }}

@include( 'proceso.asignacion.js.asignacion_ajax' )
@include( 'proceso.asignacion.js.asignacion' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Asignación de Interesados
            <small>Proceso</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Procesos</li>
            <li class="active">Asignación de Interesados</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body no-padding">
                        <form id="AsignacionForm">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial de Registro</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final de Registro</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="control-label">Empresas:</label>
                                    <div class="input-group">
                                      <select name="slct_empresas" id="slct_empresas" class="selectpicker" onchange="AjaxVisita.Trabajadores(SlctCargarTrabajador);"></select>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <span class="btn btn-primary btn-md" id="btn_generar" name="btn_generar"><i class="glyphicon glyphicon-search"></i> Buscar</span>
                                </div>
                            </div>

                            <div class="table-responsive no-padding col-md-12">
                                <table id="TableVisita" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #BDD7EE;color:black; text-align: center;" colspan="11"> DATA </th>
                                            <th style="background-color: #FFF2CC;color:black; text-align: center;" colspan="4"> RESULTADO DE LAS LLAMADAS </th>
                                            <th style="background-color: #FCE4D6;color:black; text-align: center;" colspan="4"> DETALLE DE LOS SI LLAMADOS </th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #BDD7EE;color:black">Empresa</th>
                                            <th style="background-color: #BDD7EE;color:black">Fecha de<br>la Carga</th>
                                            <th style="background-color: #BDD7EE;color:black">Fecha Inicio<br>del leads</th>
                                            <th style="background-color: #BDD7EE;color:black">Fecha Final<br>del leads</th>
                                            <th style="background-color: #BDD7EE;color:black">Nombre de la campaña</th>
                                            <th style="background-color: #BDD7EE;color:black">Carrera que solicita</th>
                                            <th style="background-color: #BDD7EE;color:black">Clientes<br>Potenciales</th>
                                            <th style="background-color: #BDD7EE;color:black">Costo<br>por<br>leads</th>
                                            <th style="background-color: #BDD7EE;color:black">Costo<br>de la<br>Data</th>
                                            <th style="background-color: #BDD7EE;color:black">Asig</th>
                                            <th style="background-color: #BDD7EE;color:black">No<br>Asig</th>
                                            <th style="background-color: #FFF2CC;color:black">Números<br>de No<br>llamados</th>
                                            <th style="background-color: #FFF2CC;color:black">Números<br>de Si<br>llamados</th>
                                            <th style="background-color: #FFF2CC;color:black">Números<br>de<br>Convertidos</th>
                                            <th style="background-color: #FFF2CC;color:black">Costo<br>por<br>Convertido</th>
                                            <th style="background-color: #FCE4D6;color:black">Número<br>de<br>Interesados</th>
                                            <th style="background-color: #FCE4D6;color:black">Número<br>de<br>Pendientes</th>
                                            <th style="background-color: #FCE4D6;color:black">Número<br>de No<br>Interesado</th>
                                            <th style="background-color: #FCE4D6;color:black">Número<br>de<br>AENN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">&nspm;</td>
                                            <td >Total</td>
                                            <td id="tdasig" >0</td>
                                            <td id="tdnoasig" >0</td>
                                            <td id="tdnocall" >0</td>
                                            <td id="tdcall" >0</td>
                                            <td id="tdconvertido" >0</td>
                                            <td id="tdto_convertido" >0</td>
                                            <td id="tdinteresado" >0</td>
                                            <td id="tdpendiente" >0</td>
                                            <td id="tdnointeresado" >0</td>
                                            <td id="tdotros" >0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">&nspm;</td>
                                            <td >Seleccionados</td>
                                            <td id="chkasig"> - </td>
                                            <td id="chknoasig">0</td>
                                            <td id="chknocall">0</td>
                                            <td id="chkcall"> - </td>
                                            <td id="chkconvertido"> - </td>
                                            <td id="chkto_convertido"> - </td>
                                            <td id="chkinteresado">0</td>
                                            <td id="chkpendiente">0</td>
                                            <td id="chknointeresado">0</td>
                                            <td id="chkotros">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                            <hr>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Trabajadores:</label>
                                    <select id="slct_trabajador" name="slct_trabajador[]" class="selectpicker form-control show-menu-arrow" data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple>
                                      <option value="">.::Seleccione::.</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <label class="control-label"><h3>Cantidad de Interesados Seleccionados:</h3></label>
                                    <label class="control-label"><h1 id="txt_contador">0</h1></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #F4AA39;color:black">
                                            <tr>
                                                <th colspan="2" style="text-align: center;"> Asignación </th>
                                            </tr>
                                            <tr>
                                                <th><label>Trabajador</label></th>
                                                <th><label>Cantidad Asignada</label>
                                                    <input type="text" id="nro_asignacion" onkeypress="return masterG.validaNumeros(event);" onkeyup="AsignaNro(2)" class="form-control" value="0">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_asignar">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-1" style="padding:24px">
                                <span onclick="Guardar();" class="btn btn-primary btn-md" id="btn_asignar" name="btn_asignar"><i class="glyphicon glyphicon-search"></i> Asignar </span>
                            </div>
                        </form>
                    </div><!-- .box-body -->
                </div>
            </div>
        </div><!-- .row -->
    </section><!-- .content -->
        @stop

