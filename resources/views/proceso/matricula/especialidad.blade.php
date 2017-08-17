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

@include( 'proceso.matricula.js.matricula_ajax' )
@include( 'proceso.matricula.js.matricula' )
@include( 'mantenimiento.programacion.js.listapersona_ajax' )
@include( 'mantenimiento.programacion.js.listapersona' )
@include( 'proceso.matricula.js.listaespecialidad_ajax' )
@include( 'proceso.matricula.js.listaespecialidad' )
@include( 'mantenimiento.programacion.js.aepersona_ajax' )
@include( 'mantenimiento.programacion.js.aepersona' )

@stop

@section('content')
<section class="content-header">
    <h1>Matrícula de Especialidades
        <small>Proceso</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Proceso</li>
        <li class="active">Matrícula PAE</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">PAE</h3>
                </div>
                <div class="box-body with-border">
                <form id="ModalMatriculaForm">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_sucursal_id" name="slct_sucursal_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Resp. de la Matrícula</label>
                            <input type="hidden" name="txt_responsable_id" id="txt_responsable_id" class="form-control mant" readonly="">
                            <input type="text" class="form-control mant" id="txt_responsable" name="txt_responsable" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="text" class="form-control mant" id="txt_fecha" name="txt_fecha" readOnly="">
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tipo de Participante</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_participante_id" name="slct_tipo_participante_id">
                                <option value="0">.::Seleccione::.</option>
                                <option value="1">Tipo 1</option>
                                <option value="2">Tipo 2</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" class="form-control" id="txt_dni" name="txt_dni" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="hidden" name="txt_persona_id" id="txt_persona_id" class="form-control" readonly="">
                            <input type="text" class="form-control" id="txt_persona" name="txt_persona" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;&nbsp;&nbsp;</label>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" onclick="TipoModal(1)" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id"  data-persona="ModalMatriculaForm #txt_persona"  data-dni="ModalMatriculaForm #txt_dni">Buscar Persona</button>
                            </span>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Región</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_region_id" name="slct_region_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Provincia</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_provincia_id" name="slct_provincia_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Distrito</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_distrito_id" name="slct_distrito_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Direccion</label>
                            <textarea type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_direccion" name="txt_direccion"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Referencia</label>
                            <textarea type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_referencia" name="txt_referencia"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>ESPECIALIZACIONES MATRICULADAS</center></div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-flat" onclick="ValidarPersona(this)" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id">Agregar Especialización Disponible</button>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cod. Teleoperadora</label>
                                        <input type="hidden" name="txt_marketing_id" id="txt_marketing_id" class="form-control" readonly="">
                                        <input type="text" class="form-control" id="txt_marketing" name="txt_marketing" disabled="">
                                    </div> 
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-flat" onclick="TipoModal(0)"  data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_marketing_id"  data-persona="ModalMatriculaForm #txt_marketing">Buscar Teleoperadora</button>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-md-12">
                                    <table id="t_matricula" class="table">
                                        <thead>
                                            <tr>
                                                <th>Especialidad</th>
                                                <th>Certificado de Especialidad</th>
                                                <th>[]</th>
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
                                            <button type="button" class="btn btn-warning btn-flat" onclick="TipoModal(0)" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_caja_id"  data-persona="ModalMatriculaForm #txt_persona_caja">Buscar Responsable</button>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-md-12">
                                    <table class="table" id="t_pago">
                                        <thead>
                                            <tr>
                                                <th colspan="4" style="text-align:center;">Pago de los Cursos</th>
                                                <th colspan="3" style="text-align:center;">Pago por Certificados</th>
                                            </tr>
                                            <tr>
                                                <th>Especialidad.</th>
                                                <th>N° de Boleta</th>
                                                <th>Importe</th>
                                                <th>Archivo</th>
                                                <th>N° de Boleta</th>
                                                <th>Importe</th>
                                                <th>Archivo</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_pago">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel panel-warning">
                            <div class="panel-heading" style="background-color: #FFE699;color:black"><center>PAGO DE MATRÍCULA</center></div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <input type="checkbox" name="exonerar_matricula" id="exonerar_matricula">Exonerar Matrícula
                                </div>
                                <div class="col-md-12">
                                    <table class="table" id="t_pago_matricula">
                                        <thead>
                                            <tr>
                                                <th colspan="3" style="text-align:center;">Pago de Matricula</th>
                                            </tr>
                                            <tr>
                                                <th>N° de Boleta</th>
                                                <th>Importe</th>
                                                <th>Archivo</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_pago_matricula">
                                            <tr>
                                                <td><input type='text' class='form-control'  id='txt_nro_pago_matricula' name='txt_nro_pago_matricula'></td>
                                                <td><input type='text' class='form-control'  id='txt_monto_pago_matricula' name='txt_monto_pago_matricula' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>
                                                <td>
                                                    <input type="text" readonly class="form-control" id="pago_nombre_matricula"  name="pago_nombre_matricula" value="">
                                                    <input type="text" style="display: none;" id="pago_archivo_matricula" name="pago_archivo_matricula">
                                                    <label class="btn btn-warning  btn-flat margin" id="df">
                                                        <i class="fa fa-file-pdf-o fa-lg"></i>
                                                        <i class="fa fa-file-word-o fa-lg"></i>
                                                        <i class="fa fa-file-image-o fa-lg"></i>
                                                        <input type="file" style="display: none;" onchange="onPagos(null,3);" id="file_matricula">
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                    <button type="submit" class="btn btn-info pull-right" onclick="AgregarEditarAjax()">Guardar Matricula</button>
                </div>
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
@include( 'mantenimiento.programacion.form.listapersona' )
@include( 'proceso.matricula.form.listaespecialidad' )
@include( 'mantenimiento.persona.form.persona' )
@stop
