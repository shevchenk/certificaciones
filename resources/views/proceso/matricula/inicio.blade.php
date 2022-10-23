@extends('layout.master')  

@section('include')
@parent
{{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
{{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
{{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

{{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
{{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
{{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

{{ Html::style('lib/iCheck/all.css') }}
{{ Html::script('lib/iCheck/icheck.min.js') }}

{{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

{{ Html::style('lib/EasyAutocomplete1.3.5/easy-autocomplete.min.css') }}
{{ Html::script('lib/EasyAutocomplete1.3.5/jquery.easy-autocomplete.min.js') }}

@include( 'proceso.matricula.js.especialidad_ajax' )
@include( 'proceso.matricula.js.especialidad' )
@include( 'mantenimiento.programacion.js.listapersona_ajax' )
@include( 'mantenimiento.programacion.js.listapersona' )
@include( 'proceso.matricula.js.listaespecialidad_ajax' )
@include( 'proceso.matricula.js.listainicio' )
@include( 'proceso.matricula.js.listaprogramacion_ajax' )
@include( 'mantenimiento.programacion.js.aepersona_ajax' )
@include( 'mantenimiento.programacion.js.aepersona' )
@include( 'mantenimiento.persona.js.persona_adicional' )
@include( 'mantenimiento.persona.js.persona_adicional_ajax' )
@include( 'mantenimiento.trabajador.js.listatrabajador_ajax' )
@include( 'mantenimiento.trabajador.js.listatrabajador' )
@include( 'proceso.matricula.js.listallamada_ajax' )
@include( 'proceso.matricula.js.listallamada' )

@stop

@section('content')
<style>
.modal { overflow: auto !important; }
</style>
<section class="content-header">
    <h1>Inscripción pago por Inicio
        <small>Proceso</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Proceso</li>
        <li class="active">Inscripción</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <form id="ModalMatriculaForm">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><center>DATOS DEL ALUMNO</center></div>
                                <div class="panel-body">
                                    <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>DNI</label>
                                            <input type="text" class="form-control" id="txt_dni" name="txt_dni" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nombre Completo</label>
                                            <input type="hidden" name="txt_ode_estudio_id" id="txt_ode_estudio_id" value="">
                                            <input type="hidden" name="txt_persona_id" id="txt_persona_id" class="form-control" readonly="">
                                            <input type="text" class="form-control" id="txt_persona" name="txt_persona" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-epersona=1 data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id" data-persona="ModalMatriculaForm #txt_persona"  data-dni="ModalMatriculaForm #txt_dni" data-buscaralumno="1">Buscar Persona</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ODE Inscripción</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_sucursal_id" name="slct_sucursal_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lugar Recojo Documento</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_sucursal_destino_id" name="slct_sucursal_destino_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo de Participante</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_participante_id" name="slct_tipo_participante_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Resp. de la Inscripción</label>
                                            <input type="hidden" name="txt_responsable_id" id="txt_responsable_id" class="form-control mant" readonly="">
                                            <input type="text" class="form-control mant" id="txt_responsable" name="txt_responsable" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input type="hidden" class="form-control mant" id="txt_tipo_matricula" name="txt_tipo_matricula" readOnly="" value="2">
                                            <input type="text" class="form-control mant" id="txt_fecha" name="txt_fecha" readOnly="">
                                        </div> 
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label>Región</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_region_id" name="slct_region_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label>Provincia</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_provincia_id" name="slct_provincia_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label>Distrito</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_distrito_id" name="slct_distrito_id">
                                                <option value="0">.::Seleccione::.</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label class="col-md-12">Código del Alumno</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i>EI112</i>
                                                </div>
                                            <input type="hidden" class="form-control col-md-4 col-xs-4 mant" id="txt_codigo_interno_base" value="EI112" disabled>
                                            <input type="text" class="form-control col-md-8 col-xs-8" id="txt_codigo_interno" name="txt_codigo_interno">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label>Direccion</label>
                                            <textarea type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_direccion" name="txt_direccion"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="display: none;">
                                        <div class="form-group">
                                            <label>Referencia</label>
                                            <textarea type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_referencia" name="txt_referencia"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>INICIOS / CURSOS DE LA CARRERA / MÓDULO SELECCIONADA</center></div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaespecialidad">Buscar Carrera / Módulo</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-12">
                                        <table id="t_matricula" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Carrera / Módulo</th>
                                                    <th>Inicio / Curso</th>
                                                    <th>Modalidad</th>
                                                    <th>Fecha de Inicio</th>
                                                    <th>Horario</th>
                                                    <th>Frecuencia</th>
                                                    <th>Local del Inicio / Curso</th>
                                                    <th>[ * ]</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_matricula">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading" style="background-color: #FFE699;color:black"><center>PAGOS</center></div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Responsable de Caja</label>
                                            <input type="hidden" name="txt_persona_caja_id" id="txt_persona_caja_id" class="form-control" readonly="">
                                            <input type="text" class="form-control" id="txt_persona_caja" name="txt_persona_caja" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#ModalListatrabajador" data-filtros="estado:1|rol_id:2" data-personaid="ModalMatriculaForm #txt_persona_caja_id"  data-persona="ModalMatriculaForm #txt_persona_caja">Buscar Responsable</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" id="t_pago">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" style="text-align:center;">Pago del Curso &nbsp;&nbsp;
                                                        <button type="button" onclick="ActivarPago(1);" class="btn btn-warning btn-flat">Activar Pago por Promoción</button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nombre del Curso</th>
                                                    <th>N° de Boleta/N° de Operación</th>
                                                    <th>Importe</th>
                                                    <th>Tipo Operación</th>
                                                    <th>Archivo Pago</th>
                                                    <th>Archivo DNI</th>
                                                    <th>[]</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_pago">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" style="display: none;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="exonerar_inscripcion" id="exonerar_inscripcion" >
                                                Pagar Inscripción
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" id="t_pago_inscripcion">
                                            <thead class="bg-info">
                                                <tr>
                                                    <th colspan="6" style="text-align:center;">Pago de Inscripción &nbsp;&nbsp;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nombre de la Especialidad</th>
                                                    <th>N° de Boleta/N° de Operación</th>
                                                    <th>Importe</th>
                                                    <th>Tipo Operación</th>
                                                    <th>Archivo Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_pago_promocion">
                                                <tr>
                                                    <td class="promocion_seminario"></td>
                                                    <td><input type="text" class="form-control" id="txt_nro_pago_inscripcion" name="txt_nro_pago_inscripcion" value="0" placeholder="Nro" readonly=""></td>
                                                    <td>
                                                        <div class='input-group'>
                                                            <div class='input-group-addon'>
                                                            <i id="precio">0.00</i>
                                                            </div>
                                                            <div id="txt_monto_pago_inscripcion_ico" class="has-warning has-feedback">
                                                                <input type='text' class='form-control'  id='txt_monto_pago_inscripcion' name='txt_monto_pago_inscripcion' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda2(0,this);' readonly="">
                                                                <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                                                            </div>
                                                        </div>
                                                        <div id="i_monto_deuda_inscripcion_ico" class="has-warning has-feedback">
                                                            <div class='input-group-addon'>
                                                            <label>Deuda:</label>
                                                            <label id='i_monto_deuda_inscripcion'>0</label>
                                                            <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><select class='form-control'  id='slct_tipo_pago_inscripcion' name='slct_tipo_pago_inscripcion' disabled="">
                                                        <option value='0'>.::Seleccione::.</option>
                                                        <option value='1.1'>Transferencia - BCP</option>
                                                        <option value='1.2'>Transferencia - Scotiabank</option>
                                                        <option value='1.3'>Transferencia - BBVA</option>
                                                        <option value='1.4'>Transferencia - Interbank</option>
                                                        <option value='2.1'>Depósito - BCP</option>
                                                        <option value='2.2'>Depósito - Scotiabank</option>
                                                        <option value='2.3'>Depósito - BBVA</option>
                                                        <option value='2.4'>Depósito - Interbank</option>
                                                        <option value='3.0'>Caja</option>
                                                        </select></td>
                                                    <td>
                                                        <input type="text"  readOnly class="form-control input-sm" id="pago_nombre_inscripcion"  name="pago_nombre_inscripcion" value="">
                                                        <input type="text" style="display: none;" id="pago_archivo_inscripcion" name="pago_archivo_inscripcion">
                                                        <label class="btn btn-default btn-flat margin btn-xs">
                                                            <i class="fa fa-file-image-o fa-3x"></i>
                                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                                            <i class="fa fa-file-word-o fa-3x"></i>
                                                            <input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,'#pago_nombre_inscripcion','#pago_archivo_inscripcion','#pago_img_ins');" >
                                                        </label>
                                                        <div>
                                                        <a id="pago_href">
                                                        <img id="pago_img_ins" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                                                        </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12" style="display: none;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="exonerar_matricula" id="exonerar_matricula" >
                                                Pagar Matrícula
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" id="t_pago_matricula">
                                            <thead class="bg-info">
                                                <tr>
                                                    <th colspan="5" style="text-align:center;">Pago de Matrícula &nbsp;&nbsp;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nombre de la Especialidad</th>
                                                    <th>N° de Boleta/N° de Operación</th>
                                                    <th>Importe</th>
                                                    <th>Tipo Operación</th>
                                                    <th>Archivo Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="promocion_seminario"></td>
                                                    <td><input type="text" class="form-control" id="txt_nro_pago_matricula" name="txt_nro_pago_matricula" value="0" placeholder="Nro" readonly=""></td>
                                                    <td>
                                                        <div class='input-group'>
                                                            <div class='input-group-addon'>
                                                            <i id="precio_mat">0.00</i>
                                                            </div>
                                                            <div id="txt_monto_pago_matricula_ico" class="has-warning has-feedback">
                                                                <input type='text' class='form-control'  id='txt_monto_pago_matricula' name='txt_monto_pago_matricula' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda3(0,this);' readonly="">
                                                                <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                                                            </div>
                                                        </div>
                                                        <div id="i_monto_deuda_matricula_ico" class="has-warning has-feedback">
                                                            <div class='input-group-addon'>
                                                            <label>Deuda:</label>
                                                            <label id='i_monto_deuda_matricula'>0</label>
                                                            <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><select class='form-control'  id='slct_tipo_pago_matricula' name='slct_tipo_pago_matricula' disabled="">
                                                        <option value='0'>.::Seleccione::.</option>
                                                        <option value='1.1'>Transferencia - BCP</option>
                                                        <option value='1.2'>Transferencia - Scotiabank</option>
                                                        <option value='1.3'>Transferencia - BBVA</option>
                                                        <option value='1.4'>Transferencia - Interbank</option>
                                                        <option value='2.1'>Depósito - BCP</option>
                                                        <option value='2.2'>Depósito - Scotiabank</option>
                                                        <option value='2.3'>Depósito - BBVA</option>
                                                        <option value='2.4'>Depósito - Interbank</option>
                                                        <option value='3.0'>Caja</option>
                                                        </select></td>
                                                    <td>
                                                        <input type="text"  readOnly class="form-control input-sm" id="pago_nombre_matricula"  name="pago_nombre_matricula" value="">
                                                        <input type="text" style="display: none;" id="pago_archivo_matricula" name="pago_archivo_matricula">
                                                        <label class="btn btn-default btn-flat margin btn-xs">
                                                            <i class="fa fa-file-image-o fa-3x"></i>
                                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                                            <i class="fa fa-file-word-o fa-3x"></i>
                                                            <input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,'#pago_nombre_matricula','#pago_archivo_matricula','#pago_img_mat');" >
                                                        </label>
                                                        <div>
                                                        <a id="pago_href">
                                                        <img id="pago_img_mat" class="img-circle" style="height: 80px;width: 140px;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                                                        </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table" id="t_pago_cuota">
                                            <thead class="bg-info">
                                                <tr>
                                                    <th colspan="5" style="text-align:center;" id="titpago">Pago de Cuotas de la Especialidad &nbsp;&nbsp;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th id="subtitpago">Cuotas Programadas</th>
                                                    <th>N° de Boleta/N° de Operación</th>
                                                    <th>Importe</th>
                                                    <th>Tipo Operación</th>
                                                    <th>Archivo Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_pago_cuota">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table" id="t_adicional">
                                            <thead class="bg-info">
                                                <tr>
                                                    <th style="text-align:center;">Adicional &nbsp;&nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_adicional">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading"><center>MARKETING:</center></div>
                                <div class="panel-body">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Medio de Captación</label>
                                            <select name="slct_medio_captacion_id" id="slct_medio_captacion_id" class="form-control selectpicker" onchange="ValidaMedioCaptacion()"></select>
                                        </div> 
                                    </div>
                                    <div class="col-md-5 validamediocaptacion" style="display: none;">
                                        <div class="form-group">
                                            <label>Persona Marketing</label>
                                            <input type="hidden" name="txt_marketing_id" id="txt_marketing_id" class="form-control" readonly="">
                                            <input type="text" class="form-control" id="txt_marketing" name="txt_marketing" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-2 validamediocaptacion" style="display: none;">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                            <span class="input-group-btn">
                                                <button type="button" id="btn_marketing" data-id="ModalMatriculaForm #btn_marketing" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListatrabajador" data-filtros="estado:1|rol_id:1" data-filtros2="" data-personaid="ModalMatriculaForm #txt_marketing_id"  data-persona="ModalMatriculaForm #txt_marketing">Buscar Persona Marketing</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-2 validamediocaptacion" style="display: none;">
                                        <div class="form-group">
                                            <label>ID Llamada</label>
                                            <input class="form-control" type="text" id="txt_llamada_id" name="txt_llamada_id" readonly>
                                            <span class="input-group-btn">
                                                <button type="button" id="btn_llamadas" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#ModalLlamada" data-persona_id="#ModalMatriculaForm #txt_marketing_id" data-persona="#ModalMatriculaForm #txt_marketing" data-id="#ModalMatriculaForm #txt_llamada_id">Selecciona LLamada</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Como llegó aquí</label>
                                            <select name="slct_medio_captacion_id2" id="slct_medio_captacion_id2" class="form-control selectpicker"></select>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><center>OBSERVACIONES:</center></div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <textarea class="form-control" id="txt_observacion" name="txt_observacion">S/O</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group"> 
                            <label></label>
                        </div>

                    </form>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right" onclick="AgregarEditarAjax()">Guardar Inscripción</button>
                </div>
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
@include( 'mantenimiento.programacion.form.listapersona' )
@include( 'proceso.matricula.form.listainicio' )
@include( 'proceso.matricula.form.listaprogramacioncurso' )
@include( 'mantenimiento.persona.form.persona' )
@include( 'mantenimiento.trabajador.form.listatrabajador' )
@include( 'proceso.matricula.form.llamadas' )
@stop
