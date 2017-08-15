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
@include( 'proceso.matricula.js.listaprogramacion_ajax' )
@include( 'proceso.matricula.js.listaprogramacion' )
@include( 'mantenimiento.programacion.js.aepersona_ajax' )
@include( 'mantenimiento.programacion.js.aepersona' )

@stop

@section('content')
<section class="content-header">
    <h1>Matrícula PAE
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
                            <input type="text" class="form-control" id="txt_responsable" name="txt_responsable" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="text" class="form-control" id="txt_fecha" name="txt_fecha" readOnly="">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="hidden" name="txt_persona_id" id="txt_persona_id" class="form-control mant" readonly="">
                            <input type="text" class="form-control" id="txt_persona" name="txt_persona" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;&nbsp;&nbsp;</label>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id"  data-persona="ModalMatriculaForm #txt_persona"  data-dni="ModalMatriculaForm #txt_dni">Buscar</button>
                            </span>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Direccion</label>
                            <input type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_direccion" name="txt_direccion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Región</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_region_id" name="slct_region_id">
                                <option value="0">.::Seleccione::.</option>
                                <option value="1">Lima</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Provincia</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_provincia_id" name="slct_provincia_id">
                                <option value="0">.::Seleccione::.</option>
                                <option value="1">Callao</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Distrito</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_distrito_id" name="slct_distrito_id">
                                <option value="0">.::Seleccione::.</option>
                                <option value="1">Ventanilla</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><center>CURSOS MATRICULADOS</center></div>
                            <div class="panel-body">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id"  data-persona="ModalMatriculaForm #txt_persona"  data-dni="ModalMatriculaForm #txt_dni">Agregar Curso con Programación</button>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cod. Teleoperadora</label>
                                        <input type="hidden" name="txt_marketing_id" id="txt_marketing_id" class="form-control mant" readonly="">
                                        <input type="text" class="form-control" id="txt_marketing" name="txt_marketing" disabled="">
                                    </div> 
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_marketing_id"  data-persona="ModalMatriculaForm #txt_marketing">Buscar</button>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-md-12">
                                    <table id="t_matricula" class="table">
                                        <thead>
                                            <tr>
                                                <th>Mod.</th>
                                                <th>Cursos</th>
                                                <th>Fecha de Inicio</th>
                                                <th>Horario</th>
                                                <th>Local</th>
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
                            <div class="panel-heading"><center>PAGOS</center></div>
                            <div class="panel-body">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Responsable de Caja</label>
                                        <input type="hidden" name="txt_persona_caja_id" id="txt_persona_caja_id" class="form-control mant" readonly="">
                                        <input type="text" class="form-control" id="txt_persona_caja" name="txt_persona_caja" disabled="">
                                    </div> 
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;&nbsp;&nbsp;</label>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_caja_id"  data-persona="ModalMatriculaForm #txt_persona_caja">Buscar</button>
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
                                                <th>Nombre del Curso.</th>
                                                <th>N° de Boleta</th>
                                                <th>Importe</th>
                                                <th>Img</th>
                                                <th>N° de Boleta</th>
                                                <th>Importe</th>
                                                <th>Img</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_pago">
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
@include( 'proceso.matricula.form.listaprogramacion' )
@include( 'mantenimiento.persona.form.persona' )
@stop
