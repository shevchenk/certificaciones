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

@include( 'proceso.matricula.js.matricula_ajax' )
@include( 'proceso.matricula.js.matricula' )
@include( 'mantenimiento.programacion.js.listapersona_ajax' )
@include( 'mantenimiento.programacion.js.listapersona' )
@include( 'proceso.matricula.js.listaprogramacion_ajax' )
@include( 'proceso.matricula.js.listaprogramacion' )
@include( 'mantenimiento.programacion.js.aepersona_ajax' )
@include( 'mantenimiento.programacion.js.aepersona' )
@include( 'mantenimiento.trabajador.js.listatrabajador_ajax' )
@include( 'mantenimiento.trabajador.js.listatrabajador' )

@stop

@section('content')
<style>
.modal { overflow: auto !important; }
</style>
<section class="content-header">
    <h1>Inscripción de Cursos
        <small>Proceso</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Proceso</li>
        <li class="active">Inscripción PAE</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">CURSOS</h3>
                </div>
                <div class="box-body with-border">
                    <form id="ModalMatriculaForm">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><center>DATOS DEL ALUMNO</center></div>
                                <div class="panel-body">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Resp. de la Inscripción</label>
                                            <input type="hidden" name="txt_responsable_id" id="txt_responsable_id" class="form-control mant" readonly="">
                                            <input type="text" class="form-control mant" id="txt_responsable" name="txt_responsable" disabled="">
                                        </div> 
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input type="hidden" class="form-control mant" id="txt_tipo_matricula" name="txt_tipo_matricula" readOnly="" value="1">
                                            <input type="text" class="form-control mant" id="txt_fecha" name="txt_fecha" readOnly="">
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo de Participante</label>
                                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_participante_id" name="slct_tipo_participante_id">
                                                <option value="0">.::Seleccione::.</option>
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
                                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-epersona=1 data-filtros="estado:1" data-personaid="ModalMatriculaForm #txt_persona_id"  data-persona="ModalMatriculaForm #txt_persona"  data-dni="ModalMatriculaForm #txt_dni" data-buscaralumno="1">Buscar Persona</button>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Código del Alumno</label>
                                            <input type="text" class="form-control" id="txt_codigo_interno" name="txt_codigo_interno">
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Direccion</label>
                                            <textarea type="text"  onkeypress="return masterG.validaAlfanumerico(event, this);" class="form-control" id="txt_direccion" name="txt_direccion"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
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
                                <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>CURSOS MATRICULADOS</center></div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListaprogramacion" data-filtros="estado:1|tipo_curso:1">Agregar Curso con Programación</button>
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
                                                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#ModalListatrabajador" data-filtros="estado:1|rol_id:1" data-personaid="ModalMatriculaForm #txt_marketing_id"  data-persona="ModalMatriculaForm #txt_marketing">Buscar Teleoperadora</button>
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
                                                    <th>Local de Estudios</th>
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
                                                <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#ModalListatrabajador" data-filtros="estado:1|rol_id:2" data-personaid="ModalMatriculaForm #txt_persona_caja_id"  data-persona="ModalMatriculaForm #txt_persona_caja">Buscar Responsable</button>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;&nbsp;&nbsp;</label>
                                        </div> 
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group" id="t_pago_promocion">
                                            <label>Promoción:</label>
                                            <br>
                                            <div class="col-sm-4">
                                                <label>Nro:</label>
                                                <input type="text" class="form-control" id="txt_nro_promocion" name="txt_nro_promocion" placeholder="Nro" disabled>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Monto:</label>
                                                <input type="text" class="form-control" id="txt_monto_promocion" name="txt_monto_promocion" placeholder="Monto" disabled>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" readonly class="form-control" id="pago_nombre_promocion"  name="pago_nombre_promocion" value="">
                                                <input type="text" style="display: none;" id="pago_archivo_promocion" name="pago_archivo_promocion">
                                                <label class="btn btn-warning  btn-flat margin">
                                                    <i class="fa fa-file-pdf-o fa-lg"></i>
                                                    <i class="fa fa-file-word-o fa-lg"></i>
                                                    <i class="fa fa-file-image-o fa-lg"></i>
                                                    <input type="file" style="display: none;" onchange="onPagos(null, 5);" id="file_promocion">
                                                </label>
                                            </div>
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
                                                    <th>Archivo</th>
                                                    <th>N° de Boleta</th>
                                                    <th>Importe</th>
                                                    <th>Archivo</th>
                                                    <th>[]</th>
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
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="exonerar_matricula" id="exonerar_matricula" >
                                                Exonerar Matrícula
                                            </label>
                                        </div>
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
                                                        <label class="btn btn-warning  btn-flat margin">
                                                            <i class="fa fa-file-pdf-o fa-lg"></i>
                                                            <i class="fa fa-file-word-o fa-lg"></i>
                                                            <i class="fa fa-file-image-o fa-lg"></i>
                                                            <input type="file" style="display: none;" onchange="onPagos(null, 3);" id="file_matricula">
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading" style="background-color: #FFE699;color:black"><center>PAGO DE INSCRIPCIÓN</center></div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="exonerar_inscripcion" id="exonerar_inscripcion" >
                                                Exonerar Inscripción
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" id="t_pago_inscripcion">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:center;">Pago de Inscripción</th>
                                                </tr>
                                                <tr>
                                                    <th>N° de Boleta</th>
                                                    <th>Importe</th>
                                                    <th>Archivo</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_pago_inscripcion">
                                                <tr>
                                                    <td><input type='text' class='form-control'  id='txt_nro_pago_inscripcion' name='txt_nro_pago_inscripcion'></td>
                                                    <td><input type='text' class='form-control'  id='txt_monto_pago_inscripcion' name='txt_monto_pago_inscripcion' onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);'></td>
                                                    <td>
                                                        <input type="text" readonly class="form-control" id="pago_nombre_inscripcion"  name="pago_nombre_inscripcion" value="">
                                                        <input type="text" style="display: none;" id="pago_archivo_inscripcion" name="pago_archivo_inscripcion">
                                                        <label class="btn btn-warning  btn-flat margin">
                                                            <i class="fa fa-file-pdf-o fa-lg"></i>
                                                            <i class="fa fa-file-word-o fa-lg"></i>
                                                            <i class="fa fa-file-image-o fa-lg"></i>
                                                            <input type="file" style="display: none;" onchange="onPagos(null, 4);" id="file_inscripcion">
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                    <button type="submit" class="btn btn-info pull-right" onclick="AgregarEditarAjax()">Guardar Matricula</button>
                </div>
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
@include( 'mantenimiento.programacion.form.listapersona' )
@include( 'proceso.matricula.form.listaprogramacioncurso' )
@include( 'mantenimiento.persona.form.persona' )
@include( 'mantenimiento.trabajador.form.listatrabajador' )
@stop
