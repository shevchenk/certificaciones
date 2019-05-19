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

@include( 'proceso.alumnocurso.js.alumnocurso_ajax' )
@include( 'proceso.alumnocurso.js.alumnocurso' )

@stop

@section('content')
<style>
.modal { overflow: auto !important; 
</style>
<section class="content-header">
    <h1>Listado de Seminarios
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
                    <h3 class="box-title">SEMINARIOS</h3>
                </div>
                <div class="box-body with-border">
                    <form id="ModalMatriculaForm">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><center>DATOS DEL ALUMNO</center></div>
                                <div class="panel-body">
                                    <div class="col-md-3">
                                        <label>Paterno</label>
                                        <input type="text" class="form-control mant" id="txt_paterno" name="txt_paterno" disabled="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Materno</label>
                                        <input type="text" class="form-control mant" id="txt_materno" name="txt_materno" disabled="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Nombres</label>
                                        <input type="text" class="form-control mant" id="txt_nombre" name="txt_nombre" disabled="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>DNI</label>
                                        <input type="text" class="form-control mant" id="txt_dni" name="txt_dni" disabled="">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="background-color: #A9D08E;color:black"><center>SEMINARIOS MATRICULADOS</center></div>
                                <div class="panel-body">
                                    <div class="col-md-12 table-responsive">
                                        <table id="t_matricula" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Mod.</th>
                                                    <th>Seminarios</th>
                                                    <th>Docente</th>
                                                    <th>Fecha de Seminario</th>
                                                    <th>Horario</th>
                                                    <th>Local del Seminario</th>
                                                    <th>Link del Video</th>
                                                    <th>Comentario del Video</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_matricula">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
@include( 'proceso.alumnocurso.form.alumnocurso' )
@stop
